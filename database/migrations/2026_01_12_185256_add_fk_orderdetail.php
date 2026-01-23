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
        Schema::table('orderdetail', function (Blueprint $table) {
            // PRODUCT
            $table->index('product_id');
            $table->foreign('product_id')
                  ->references('id')
                  ->on('product')
                  ->restrictOnDelete(); // không cho xóa product đã có trong đơn
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ndc_orderdetail', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex(['product_id']);
        });
    }
};
