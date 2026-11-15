<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\UploadRejectedException;
use App\Services\UploadService;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Shop;

final class UploadServiceTest extends CIUnitTestCase
{
    private UploadService $uploads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uploads = new UploadService(new Shop());
    }

    public function testPathRejectsTraversalAndUnexpectedNames(): void
    {
        foreach (['../../.env', '../index.php', 'shell.php', 'a.png', str_repeat('a', 32) . '.php'] as $name) {
            $this->assertNull($this->uploads->path(UploadService::PRODUCTS, $name), $name);
        }
    }

    public function testUnknownDirectoryIsRefused(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uploads->path('../../app', str_repeat('a', 32) . '.png');
    }

    public function testNonUploadedFileIsRejected(): void
    {
        $this->expectException(UploadRejectedException::class);

        // is_uploaded_file() is false for anything that did not arrive via HTTP POST.
        $this->uploads->store(new UploadedFile('/etc/passwd', 'x.png', 'image/png', 10, UPLOAD_ERR_OK), UploadService::PROOFS);
    }

    public function testMimeTypeIsDerivedFromStoredExtensionOnly(): void
    {
        $this->assertSame('image/png', $this->uploads->mimeType('x.png'));
        $this->assertSame('image/webp', $this->uploads->mimeType('x.webp'));
        $this->assertSame('image/jpeg', $this->uploads->mimeType('x.jpg'));
    }

    public function testReencodeStripsAppendedPhpPayload(): void
    {
        $png = file_get_contents(__DIR__ . '/../_support/pixel.png');

        $clean = $this->uploads->reencode($png . '<?php system($_GET["c"]); ?>', 'image/png');

        $this->assertStringNotContainsString('<?php', $clean);
        $this->assertStringNotContainsString('system(', $clean);
        $this->assertNotFalse(getimagesizefromstring($clean));
    }

    public function testReencodeRejectsNonImageBytes(): void
    {
        $this->expectException(UploadRejectedException::class);

        $this->uploads->reencode('<?php echo 1; ?>', 'image/png');
    }
}
