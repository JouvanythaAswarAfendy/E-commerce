<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('offline_transaction_items', function (Blueprint $table) {
            // Tambahkan kolom product_name jika belum ada
            if (!Schema::hasColumn('offline_transaction_items', 'product_name')) {
                $table->string('product_name')->after('product_id')->nullable();
            }
            
            // Rename quantity ke qty jika ada
            if (Schema::hasColumn('offline_transaction_items', 'quantity') && !Schema::hasColumn('offline_transaction_items', 'qty')) {
                $table->renameColumn('quantity', 'qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offline_transaction_items', function (Blueprint $table) {
            if (Schema::hasColumn('offline_transaction_items', 'product_name')) {
                $table->dropColumn('product_name');
            }
            if (Schema::hasColumn('offline_transaction_items', 'qty')) {
                $table->renameColumn('qty', 'quantity');
            }
        });
    }
};
