<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah enum status untuk menambahkan 'booked' dan 'cancelled'
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('booked', 'borrowed', 'returned', 'cancelled') NOT NULL DEFAULT 'booked'");

        // 2. Buat user_id menjadi nullable (karena saat booking online belum dilayani petugas)
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN user_id BIGINT UNSIGNED NULL");

        // 3. Tambahkan kolom pickup_deadline (batas waktu pengambilan 1 hari)
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dateTime('pickup_deadline')->nullable()->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('pickup_deadline');
        });

        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('borrowed', 'returned') NOT NULL DEFAULT 'borrowed'");
    }
};
