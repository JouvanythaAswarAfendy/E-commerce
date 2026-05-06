<?php

use App\Models\Product;
use App\Models\User;

$user = User::first(); // Assuming there's at least one user (admin)

$products = [
    [
        'name' => 'Tempat Tisu Kristal Handmade Aesthetic',
        'description' => "Tempat tisu berbahan manik-manik kristal transparan yang dirangkai secara handmade dengan desain elegan dan modern. Memberikan sentuhan estetik pada ruangan seperti kamar, ruang tamu, maupun meja kerja.\n\nDibuat dengan detail yang rapi dan kokoh, produk ini tidak hanya berfungsi sebagai tempat tisu, tetapi juga sebagai dekorasi yang mempercantik suasana. Cocok untuk kamu yang suka tampilan minimalis namun tetap berkelas.\n\nSpesifikasi:\nMaterial: Manik kristal transparan\nWarna: Bening / Clear\nTipe: Handmade\nFungsi: Tempat tisu & dekorasi ruangan\n\nKelebihan:\nDesain aesthetic & elegan\nCocok untuk berbagai konsep ruangan\nFinishing rapi dan kuat\nBisa jadi hadiah unik & berkesan",
        'category_id' => 1, // Dekorasi
        'price' => 150000,
        'stock' => 10,
        'images' => ['images/tempat-tisu(1).jpeg'],
        'created_by' => $user->id,
        'status' => 'active'
    ],
    [
        'name' => 'Gantungan Kunci Karakter Pinguin',
        'description' => "Gantungan kunci berbahan manik-manik yang dirangkai secara handmade dengan bentuk karakter pinguin yang lucu dan menggemaskan. Memiliki warna cerah dan desain bulat yang unik, cocok untuk mempercantik tas, kunci, maupun aksesoris sehari-hari.\n\nDibuat dengan detail dan ketelitian sehingga menghasilkan produk yang kuat, ringan, dan tetap nyaman digunakan. Cocok untuk kamu yang suka aksesoris unik, playful, dan estetik.\n\nSpesifikasi:\nMaterial: Manik-manik warna-warni\nTipe: Handmade\nModel: Pinguin\nFungsi: Gantungan kunci & aksesoris tas\n\nKelebihan:\nDesain super gemoy & eye-catching\nHandmade (lebih unik & eksklusif)\nRingan dan praktis dibawa\nCocok untuk hadiah atau koleksi",
        'category_id' => 4, // Gantungan Kunci
        'price' => 15000,
        'stock' => 10,
        'images' => ['images/pinguin.jpeg'],
        'created_by' => $user->id,
        'status' => 'active'
    ],
    [
        'name' => 'Bunga Mawar Manik Handmade',
        'description' => "Bunga mawar hias berbahan manik-manik yang dirangkai secara handmade dengan detail yang menyerupai bunga asli. Memiliki warna cerah dan tampilan yang segar, cocok dijadikan dekorasi ruangan seperti ruang tamu, kamar, atau sebagai hadiah.\n\nTidak memerlukan perawatan seperti bunga asli, sehingga lebih awet dan praktis.\n\nSpesifikasi:\nMaterial: Manik-manik & kawat\nTipe: Handmade\nModel: Mawar\nFungsi: Dekorasi ruangan / hadiah\n\nKelebihan:\nTampilan cantik & realistis\nTahan lama (tidak layu)\nCocok untuk dekorasi atau gift\nHandmade with detail rapi",
        'category_id' => 1, // Dekorasi
        'price' => 20000,
        'stock' => 10,
        'images' => ['images/bunga-mawar.jpeg'],
        'created_by' => $user->id,
        'status' => 'active'
    ],
    [
        'name' => 'Bros Bunga Manik Handmade',
        'description' => "Bros berbentuk bunga dengan kombinasi manik-manik dan detail kelopak transparan yang memberikan kesan elegan dan mewah. Dirangkai secara handmade dengan warna yang cantik dan beragam, cocok digunakan sebagai aksesoris hijab, baju, atau hadiah spesial.\n\nDesainnya yang unik membuat tampilan lebih anggun dan standout tanpa terlihat berlebihan.\n\nSpesifikasi:\nMaterial: Manik-manik & resin/plastik\nTipe: Handmade\nModel: Bunga (varian warna)\nFungsi: Bros hijab / aksesoris outfit\n\nKelebihan:\nElegan & feminin\nPilihan warna menarik\nCocok untuk acara formal & casual\nHandmade (lebih eksklusif)",
        'category_id' => 2, // Aksesori
        'price' => 5000,
        'stock' => 10,
        'images' => ['images/bros.jpeg'],
        'created_by' => $user->id,
        'status' => 'active'
    ],
];

foreach ($products as $productData) {
    Product::updateOrCreate(['name' => $productData['name']], $productData);
}

echo "Products updated successfully!\n";
