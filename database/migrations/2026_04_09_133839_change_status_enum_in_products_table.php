<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('tersedia', 'stok_menipis', 'habis') DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
    }
};