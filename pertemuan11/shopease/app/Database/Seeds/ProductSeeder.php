<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    /** [category slug, name, slug, description, price (IDR), stock] */
    private const array PRODUCTS = [
        ['e-book', 'Panduan CodeIgniter 4 Lengkap', 'panduan-codeigniter-4', 'E-book 320 halaman: routing, model, validasi, hingga deployment.', 89000, 50],
        ['e-book', 'PHP 8 Modern untuk Pemula', 'php-8-modern-pemula', 'Enum, match, readonly, dan type system dengan contoh nyata.', 75000, 40],
        ['e-book', 'Desain Basis Data MySQL', 'desain-basis-data-mysql', 'Normalisasi, indeks, dan transaksi untuk aplikasi e-commerce.', 99000, 30],
        ['template', 'Template Landing Page Minimalis', 'template-landing-minimalis', 'Paket 12 section HTML/CSS siap pakai, responsif.', 129000, 25],
        ['template', 'Template Laporan Tugas Akhir', 'template-laporan-tugas-akhir', 'Template Word dan LaTeX sesuai kaidah penulisan ilmiah.', 59000, 60],
        ['template', 'UI Kit Dashboard Admin', 'ui-kit-dashboard-admin', 'Komponen Figma untuk dashboard admin dan laporan.', 149000, 20],
        ['software', 'Lisensi Editor Kode Pro 1 Tahun', 'lisensi-editor-kode-pro', 'Lisensi editor kode dengan dukungan ekstensi lengkap.', 450000, 15],
        ['software', 'Aplikasi Pencatat Keuangan', 'aplikasi-pencatat-keuangan', 'Lisensi seumur hidup untuk 2 perangkat.', 199000, 35],
        ['software', 'Paket Ikon Vektor 2.000+', 'paket-ikon-vektor', 'Ikon SVG konsisten untuk web dan aplikasi mobile.', 79000, 3],
        ['kursus-online', 'Kelas Laravel & CodeIgniter', 'kelas-laravel-codeigniter', 'Video 24 jam dengan proyek toko online.', 349000, 100],
        ['kursus-online', 'Kelas UI/UX untuk Developer', 'kelas-ui-ux-developer', 'Belajar Figma dan prinsip desain dari nol.', 279000, 80],
        ['kursus-online', 'Kelas Keamanan Aplikasi Web', 'kelas-keamanan-web', 'OWASP Top 10, hardening PHP, dan praktik upload aman.', 319000, 0],
    ];

    public function run(): void
    {
        $products = $this->db->table('products');
        $now      = date('Y-m-d H:i:s');

        foreach (self::PRODUCTS as [$categorySlug, $name, $slug, $description, $price, $stock]) {
            if ($products->where('slug', $slug)->countAllResults() > 0) {
                continue;
            }

            $category = $this->db->table('categories')->where('slug', $categorySlug)->get()->getRowArray();

            if ($category === null) {
                continue;
            }

            $products->insert([
                'category_id' => $category['id'],
                'name'        => $name,
                'slug'        => $slug,
                'description' => $description,
                'price'       => $price,
                'stock'       => $stock,
                'image'       => null,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
