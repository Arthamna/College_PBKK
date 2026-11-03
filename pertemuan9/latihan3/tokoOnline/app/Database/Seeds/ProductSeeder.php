<?php

namespace App\Database\Seeds;

use App\Models\ProductModel;
use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
	public function run()
	{
		$products = new ProductModel();
		$examples = [
			['Tas Kanvas Harian', 'Tas kanvas ringan untuk menemani kuliah, bekerja, dan perjalanan singkat. Kompartemen luas dengan jahitan yang rapi.', 89000, 15],
			['Tumbler Stainless', 'Botol minum stainless berkapasitas 500 ml. Praktis dibawa dan membantu mengurangi penggunaan botol sekali pakai.', 125000, 12],
			['Buku Catatan Linen', 'Buku catatan bersampul linen dengan kertas bergaris. Tempat menyimpan ide, rencana, dan catatan harian Anda.', 45000, 24],
			['Lampu Meja Minimalis', 'Lampu meja dengan pencahayaan hangat untuk sudut baca dan ruang kerja yang nyaman.', 179000, 8],
			['Mug Keramik Natural', 'Mug keramik 300 ml dengan pegangan nyaman. Pilihan sederhana untuk menikmati kopi atau teh favorit.', 65000, 18],
			['Organizer Meja', 'Rapikan alat tulis dan aksesori kecil di meja kerja dengan organizer serbaguna.', 79000, 10],
			['Pouch Serbaguna', 'Pouch ringkas untuk menyimpan kabel, alat tulis, atau perlengkapan sehari-hari.', 39000, 20],
			['Jam Dinding Klasik', 'Jam dinding berdesain bersih yang cocok untuk ruang belajar dan ruang keluarga.', 149000, 0],
			['Tempat Pensil Kanvas', 'Tempat pensil berbahan kanvas dengan ritsleting, cukup untuk perlengkapan belajar harian.', 35000, 16],
			['Alas Meja Kerja', 'Alas meja berpermukaan lembut agar area kerja terasa lebih rapi dan nyaman.', 99000, 6],
			['Rak Buku Mini', 'Rak buku berukuran ringkas untuk menata buku favorit di atas meja.', 135000, 5],
			['Set Pulpen Gel', 'Paket tiga pulpen gel dengan tinta hitam untuk menulis lancar setiap hari.', 25000, 30],
			['Kotak Penyimpanan', 'Kotak penyimpanan ringan untuk menjaga barang kecil tetap tertata.', 55000, 9],
		];

		foreach ($examples as [$name, $description, $price, $stock])
		{
			if ($products->where('name', $name)->first() === NULL)
			{
				$products->insert(compact('name', 'description', 'price', 'stock'));
			}
		}
	}
}
