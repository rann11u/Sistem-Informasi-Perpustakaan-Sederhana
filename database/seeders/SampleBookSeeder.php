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

        $catBisnis = Category::firstOrCreate(['name' => 'Ekonomi & Bisnis'], [
            'description' => 'Buku manajemen, kewirausahaan, dan ekonomi modern',
        ]);

        $catPsikologi = Category::firstOrCreate(['name' => 'Psikologi & Pengembangan Diri'], [
            'description' => 'Buku psikologi populer, kebiasaan, dan pengembangan diri',
        ]);

        // 2. Koleksi 30 Contoh Buku
        $books = [
            // Teknologi & Komputer (7)
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
                'category_id'      => $catTekno->id,
                'title'            => 'Algoritma dan Struktur Data',
                'author'           => 'Rinaldi Munir',
                'publisher'        => 'Informatika',
                'publication_year' => 2021,
                'isbn'             => '978-602-002-4',
                'stock'            => 4,
            ],
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Clean Code: Panduan Menulis Kode Rapi',
                'author'           => 'Robert C. Martin',
                'publisher'        => 'Prentice Hall',
                'publication_year' => 2019,
                'isbn'             => '978-013-235-0',
                'stock'            => 3,
            ],
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Desain Basis Data Relasional & SQL',
                'author'           => 'Fathansyah',
                'publisher'        => 'Informatika',
                'publication_year' => 2020,
                'isbn'             => '978-602-003-8',
                'stock'            => 6,
            ],
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Jaringan Komputer untuk Pemula',
                'author'           => 'Onno W. Purbo',
                'publisher'        => 'Andi Publisher',
                'publication_year' => 2022,
                'isbn'             => '978-979-290-1',
                'stock'            => 2,
            ],
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Dasar-Dasar Kecerdasan Buatan',
                'author'           => 'Suyanto',
                'publisher'        => 'Informatika',
                'publication_year' => 2023,
                'isbn'             => '978-602-004-5',
                'stock'            => 0,
            ],
            [
                'category_id'      => $catTekno->id,
                'title'            => 'Arsitektur Perangkat Lunak Pragmatis',
                'author'           => 'David Thomas',
                'publisher'        => 'Addison-Wesley',
                'publication_year' => 2020,
                'isbn'             => '978-013-595-7',
                'stock'            => 3,
            ],

            // Sastra & Novel (7)
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Laskar Pelangi',
                'author'           => 'Andrea Hirata',
                'publisher'        => 'Bentang Pustaka',
                'publication_year' => 2018,
                'isbn'             => '978-979-306-2',
                'stock'            => 5,
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
                'category_id'      => $catSastra->id,
                'title'            => 'Laut Bercerita',
                'author'           => 'Leila S. Chudori',
                'publisher'        => 'KPG',
                'publication_year' => 2017,
                'isbn'             => '978-602-424-6',
                'stock'            => 6,
            ],
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Cantik Itu Luka',
                'author'           => 'Eka Kurniawan',
                'publisher'        => 'Gramedia Pustaka Utama',
                'publication_year' => 2018,
                'isbn'             => '978-602-030-9',
                'stock'            => 2,
            ],
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Pulang',
                'author'           => 'Tere Liye',
                'publisher'        => 'Republika',
                'publication_year' => 2016,
                'isbn'             => '978-602-082-2',
                'stock'            => 5,
            ],
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Perahu Kertas',
                'author'           => 'Dee Lestari',
                'publisher'        => 'Bentang Pustaka',
                'publication_year' => 2019,
                'isbn'             => '978-602-291-4',
                'stock'            => 3,
            ],
            [
                'category_id'      => $catSastra->id,
                'title'            => 'Ronggeng Dukuh Paruk',
                'author'           => 'Ahmad Tohari',
                'publisher'        => 'Gramedia Pustaka Utama',
                'publication_year' => 2017,
                'isbn'             => '978-979-220-1',
                'stock'            => 0,
            ],

            // Sains & Matematika (4)
            [
                'category_id'      => $catSains->id,
                'title'            => 'Kosmos: Menjelajahi Alam Semesta',
                'author'           => 'Carl Sagan',
                'publisher'        => 'KPG',
                'publication_year' => 2020,
                'isbn'             => '978-602-481-6',
                'stock'            => 3,
            ],
            [
                'category_id'      => $catSains->id,
                'title'            => 'Sejarah Singkat Waktu',
                'author'           => 'Stephen Hawking',
                'publisher'        => 'Gramedia Pustaka Utama',
                'publication_year' => 2018,
                'isbn'             => '978-602-038-1',
                'stock'            => 4,
            ],
            [
                'category_id'      => $catSains->id,
                'title'            => 'Kalkulus dan Geometri Analitis',
                'author'           => 'Edwin J. Purcell',
                'publisher'        => 'Erlangga',
                'publication_year' => 2019,
                'isbn'             => '978-979-033-2',
                'stock'            => 7,
            ],
            [
                'category_id'      => $catSains->id,
                'title'            => 'Astrofisika untuk Orang Sibuk',
                'author'           => 'Neil deGrasse Tyson',
                'publisher'        => 'KPG',
                'publication_year' => 2021,
                'isbn'             => '978-602-481-9',
                'stock'            => 2,
            ],

            // Sejarah & Budaya (4)
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
                'category_id'      => $catSejarah->id,
                'title'            => 'Sapiens: Riwayat Singkat Umat Manusia',
                'author'           => 'Yuval Noah Harari',
                'publisher'        => 'KPG',
                'publication_year' => 2020,
                'isbn'             => '978-602-424-8',
                'stock'            => 5,
            ],
            [
                'category_id'      => $catSejarah->id,
                'title'            => 'Nusantara: Sejarah Indonesia',
                'author'           => 'Bernard H.M. Vlekke',
                'publisher'        => 'KPG',
                'publication_year' => 2016,
                'isbn'             => '978-979-910-1',
                'stock'            => 2,
            ],
            [
                'category_id'      => $catSejarah->id,
                'title'            => 'Jalur Rempah dan Kejayaan Maritim',
                'author'           => 'A.B. Lapian',
                'publisher'        => 'Komunitas Bambu',
                'publication_year' => 2021,
                'isbn'             => '978-602-940-3',
                'stock'            => 4,
            ],

            // Ekonomi & Bisnis (4)
            [
                'category_id'      => $catBisnis->id,
                'title'            => 'Psikologi Uang',
                'author'           => 'Morgan Housel',
                'publisher'        => 'Baca',
                'publication_year' => 2021,
                'isbn'             => '978-602-648-6',
                'stock'            => 6,
            ],
            [
                'category_id'      => $catBisnis->id,
                'title'            => 'Zero to One: Membangun Startup',
                'author'           => 'Peter Thiel',
                'publisher'        => 'Gramedia Pustaka Utama',
                'publication_year' => 2019,
                'isbn'             => '978-602-032-5',
                'stock'            => 3,
            ],
            [
                'category_id'      => $catBisnis->id,
                'title'            => 'Prinsip Dasar Ekonomi Makro',
                'author'           => 'N. Gregory Mankiw',
                'publisher'        => 'Salemba Empat',
                'publication_year' => 2020,
                'isbn'             => '978-979-061-8',
                'stock'            => 5,
            ],
            [
                'category_id'      => $catBisnis->id,
                'title'            => 'The Lean Startup',
                'author'           => 'Eric Ries',
                'publisher'        => 'Bentang Pustaka',
                'publication_year' => 2018,
                'isbn'             => '978-602-291-9',
                'stock'            => 0,
            ],

            // Psikologi & Pengembangan Diri (4)
            [
                'category_id'      => $catPsikologi->id,
                'title'            => 'Atomic Habits: Perubahan Kecil',
                'author'           => 'James Clear',
                'publisher'        => 'Gramedia Pustaka Utama',
                'publication_year' => 2020,
                'isbn'             => '978-602-063-3',
                'stock'            => 8,
            ],
            [
                'category_id'      => $catPsikologi->id,
                'title'            => 'Filosofi Teras',
                'author'           => 'Henry Manampiring',
                'publisher'        => 'Kompas',
                'publication_year' => 2019,
                'isbn'             => '978-602-412-5',
                'stock'            => 5,
            ],
            [
                'category_id'      => $catPsikologi->id,
                'title'            => 'Deep Work: Fokus di Era Distraksi',
                'author'           => 'Cal Newport',
                'publisher'        => 'KPG',
                'publication_year' => 2021,
                'isbn'             => '978-602-481-2',
                'stock'            => 4,
            ],
            [
                'category_id'      => $catPsikologi->id,
                'title'            => 'Berani Tidak Disukai',
                'author'           => 'Ichiro Kishimi',
                'publisher'        => 'Gramedia Pustaka Utama',
                'publication_year' => 2020,
                'isbn'             => '978-602-063-7',
                'stock'            => 3,
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(['title' => $bookData['title']], $bookData);
        }
    }
}
