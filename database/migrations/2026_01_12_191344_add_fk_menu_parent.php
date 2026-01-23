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
        Schema::table('menu', function (Blueprint $table) {

            // parent_id = NULL nếu là menu gốc
            $table->unsignedBigInteger('parent_id')->nullable()->change();

            // index để MySQL cho phép FK
            $table->index('parent_id');

            // FK self reference
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menu')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);
            $table->unsignedBigInteger('parent_id')->default(0)->change();
        });
    }
};
