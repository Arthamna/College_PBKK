<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UploadRejectedException;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Shop;

/**
 * Secure image upload handling (php2shell / webshell hardening):
 *  - size, extension and content-sniffed MIME type must all agree;
 *  - the image is decoded and re-encoded with GD, which drops any appended
 *    payload (polyglot files) and metadata;
 *  - the stored name is system generated with a canonical extension;
 *  - files live in writable/ (outside the document root) and are streamed
 *    back through a controller, never executed by the web server.
 */
class UploadService
{
    public const string PRODUCTS = 'products';
    public const string PROOFS   = 'proofs';

    private const array DIRECTORIES = [self::PRODUCTS, self::PROOFS];

    private const array CANONICAL_EXTENSION = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    /** Upper bound on decoded pixels (guards against decompression bombs). */
    private const int MAX_PIXELS = 25_000_000;

    private const string NAME_PATTERN = '/^[a-f0-9]{32}\.(jpg|png|webp)$/';

    public function __construct(private readonly Shop $config)
    {
    }

    public function store(UploadedFile $file, string $directory): string
    {
        $this->assertKnownDirectory($directory);

        if (! $file->isValid() || $file->hasMoved()) {
            throw UploadRejectedException::because('Unggahan gagal. Silakan pilih berkas lagi.');
        }

        if ($file->getSizeByUnit('kb') > $this->config->maxUploadKb) {
            throw UploadRejectedException::because(
                sprintf('Ukuran berkas maksimal %d KB.', $this->config->maxUploadKb),
            );
        }

        $extension = strtolower($file->getClientExtension());
        $mime      = $file->getMimeType();

        $acceptedMimes = $this->config->allowedImages[$extension] ?? null;

        if ($acceptedMimes === null || ! in_array($mime, $acceptedMimes, true)) {
            throw UploadRejectedException::because('Format berkas tidak diizinkan. Gunakan JPG, PNG, atau WEBP.');
        }

        $clean = $this->reencode((string) file_get_contents($file->getTempName()), $mime);
        $name  = bin2hex(random_bytes(16)) . '.' . self::CANONICAL_EXTENSION[$mime];
        $dir   = $this->directoryPath($directory);

        if (! is_dir($dir) && ! mkdir($dir, 0750, true) && ! is_dir($dir)) {
            throw UploadRejectedException::because('Direktori unggahan tidak dapat dibuat.');
        }

        if (file_put_contents($dir . $name, $clean, LOCK_EX) === false) {
            throw UploadRejectedException::because('Berkas tidak dapat disimpan.');
        }

        return $name;
    }

    /**
     * Decodes and re-encodes an image so only pixel data survives.
     *
     * @throws UploadRejectedException when the bytes are not a decodable image
     */
    public function reencode(string $binary, string $mime): string
    {
        try {
            $info = getimagesizefromstring($binary);

            if ($info === false || $info[0] * $info[1] > self::MAX_PIXELS) {
                throw UploadRejectedException::because('Gambar tidak valid atau resolusinya terlalu besar.');
            }

            $image = imagecreatefromstring($binary);
        } catch (\ErrorException) {
            throw UploadRejectedException::because('Berkas bukan gambar yang valid.');
        }

        if ($image === false) {
            throw UploadRejectedException::because('Berkas bukan gambar yang valid.');
        }

        ob_start();

        match ($mime) {
            'image/png'  => $this->savePng($image),
            'image/webp' => imagewebp($image, null, 85),
            default      => imagejpeg($image, null, 90),
        };

        return (string) ob_get_clean();
    }

    private function savePng(\GdImage $image): void
    {
        imagesavealpha($image, true);
        imagepng($image);
    }

    /**
     * Resolves a stored name to an absolute path, or null when it is not a valid, existing upload.
     */
    public function path(string $directory, string $name): ?string
    {
        $this->assertKnownDirectory($directory);

        if (preg_match(self::NAME_PATTERN, $name) !== 1) {
            return null;
        }

        $path = $this->directoryPath($directory) . $name;

        return is_file($path) ? $path : null;
    }

    public function mimeType(string $name): string
    {
        return match (pathinfo($name, PATHINFO_EXTENSION)) {
            'png'   => 'image/png',
            'webp'  => 'image/webp',
            default => 'image/jpeg',
        };
    }

    public function delete(string $directory, ?string $name): void
    {
        if ($name === null) {
            return;
        }

        $path = $this->path($directory, $name);

        if ($path !== null) {
            unlink($path);
        }
    }

    private function directoryPath(string $directory): string
    {
        return rtrim(WRITEPATH, '/') . '/uploads/' . $directory . '/';
    }

    private function assertKnownDirectory(string $directory): void
    {
        if (! in_array($directory, self::DIRECTORIES, true)) {
            throw new \InvalidArgumentException('Unknown upload directory.');
        }
    }
}
