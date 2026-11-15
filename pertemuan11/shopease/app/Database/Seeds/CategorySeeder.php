<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    private const array CATEGORIES = [
        ['E-Book', 'e-book', 'Buku digital dalam format PDF dan EPUB.'],
        ['Template', 'template', 'Template desain, dokumen, dan presentasi.'],
        ['Software', 'software', 'Lisensi aplikasi dan alat bantu produktivitas.'],
        ['Kursus Online', 'kursus-online', 'Kelas video dan materi belajar mandiri.'],
    ];

    public function run(): void
    {
        $categories = $this->db->table('categories');
        $now        = date('Y-m-d H:i:s');

        foreach (self::CATEGORIES as [$name, $slug, $description]) {
            if ($categories->where('slug', $slug)->countAllResults() > 0) {
                continue;
            }

            $categories->insert([
                'name'        => $name,
                'slug'        => $slug,
                'description' => $description,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
