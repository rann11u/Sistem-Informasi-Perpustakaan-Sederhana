<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Book;

class SampleBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori Buku
        $catTekno = Category::firstOrCreate(['name' => 'Teknologi & Komputer'], [
            'description' => 'Buku seputar IT, pemrograman, dan rekayasa perangkat lunak',
        ]);

        $catSastra = Category::firstOrCreate(['name' => 'Sastra & Novel'], [
            'description' => 'Koleksi novel, sastra, dan fiksi terpopuler',
        ]);

        $catSains = Category::firstOrCreate(['name' => 'Sains & Matematika'], [
            'description' => 'Koleksi ilmu pengetahuan alam, fisika, dan matematika',
        ]);

        $catSejarah = Category::firstOrCreate(['name' => 'Sejarah & Budaya'], [
            'description' => 'Koleksi wawasan sejarah nusantara dan peradaban dunia',
        ]);

        // 2. Contoh Koleksi Buku
        $books = [
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Belajar Pemrograman Laravel Modern',
                'author'           => 'Ahmad Rian',
                'publisher'        => 'Informatika',
                'publication_year' => 2024,
                'isbn'             => '978-602-001-1',
                'stock'            => 5,
            ],
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Laskar Pelangi',
                'author'           => 'Andrea Hirata',
                'publisher'        => 'Bentang Pustaka',
                'publication_year' => 2018,
                'isbn'             => '978-979-306-2',
                'stock'            => 3,
            ],
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Bumi Manusia',
                'author'           => 'Pramoedya Ananta Toer',
                'publisher'        => 'Hasta Mitra',
                'publication_year' => 2015,
                'isbn'             => '978-979-973-3',
                'stock'            => 4,
            ],
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Algoritma dan Struktur Data',
                'author'           => 'Rinaldi Munir',
                'publisher'        => 'Informatika',
                'publication_year' => 2021,
                'isbn'             => '978-602-002-4',
                'stock'            => 2,
            ],
            [
                'category_id'      => $catSejarah->id,
                'title'            => 'Sejarah Lengkap Indonesia',
                'author'           => 'Adi Sudirman',
                'publisher'        => 'Diva Press',
                'publication_year' => 2019,
                'isbn'             => '978-602-391-5',
                'stock'            => 3,
            ],
            [
                'category_id'      => $catSains->id,
                'title'            => 'Kosmos: Menjelajahi Alam Semesta',
                'author'           => 'Carl Sagan',
                'publisher'        => 'KPG',
                'publication_year' => 2020,
                'isbn'             => '978-602-481-6',
                'stock'            => 0, // Sengaja 0 untuk melihat tampilan badge stok habis
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(['title' => $bookData['title']], $bookData);
        }
    }
}
