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
        Schema::create('preview_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->integer('rating_id')->nullable();
            $table->string('image');
            $table->string('file_id');
            $table->integer('width');
            $table->integer('height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preview_images');
    }
};
