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
        // 1. Tambahkan role 'member' pada tabel users
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas', 'member') NOT NULL DEFAULT 'member'");

        // 2. Tambahkan relasi user_id pada tabel members
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas'");
    }
};
