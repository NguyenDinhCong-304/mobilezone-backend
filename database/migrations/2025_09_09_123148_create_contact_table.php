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
        Schema::create('contact', function (Blueprint $table) {
            $table  ->id();
            $table  ->foreignId('user_id')
                    ->nullable() // Cho phép null khi không có user
                    ->constrained('user', 'id') // Liên kết đúng bảng
                    ->nullOnDelete() // Nếu user bị xoá, tự động set null
                    ->name('fk_contact_user_id');
            $table  ->string('name');
            $table  ->string('email');
            $table  ->string('phone');
            $table  ->text('content');
            $table  ->integer('reply_id')->default(0);
            $table  ->integer('created_by');
            $table  ->integer('updated_by')->nullable();
            $table  ->integer('status');
            $table  ->timestamps();
            $table  ->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
