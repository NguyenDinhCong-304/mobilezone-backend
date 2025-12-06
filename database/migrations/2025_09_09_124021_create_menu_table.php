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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->string('type');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('table_id')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();

            // Nếu muốn, bạn có thể thêm foreign key cho parent_id
            // $table->foreign('parent_id')->references('id')->on('menu')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
