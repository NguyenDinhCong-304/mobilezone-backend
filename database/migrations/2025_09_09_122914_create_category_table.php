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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->foreignId('parent_id')
            ->nullable()
            ->constrained('category')
            ->nullOnDelete(); // Khi xóa category cha -> con sẽ về null
            $table->integer('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
