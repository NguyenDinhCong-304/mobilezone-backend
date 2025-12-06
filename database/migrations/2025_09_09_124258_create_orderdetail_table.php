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
        Schema::create('orderdetail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders', 'id')
                ->cascadeOnDelete()
                ->name('fk_orderdetail_order_id');

            $table->foreignId('product_id')
                ->constrained('product', 'id')
                ->cascadeOnDelete()
                ->name('fk_orderdetail_product_id');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderdetail');
    }
};
