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
        Schema::create('thumbnail_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("category_id")->constrained('categories');
            $table->string('thumbnail');
            $table->string('file_id');
            $table->string('title');
            $table->string('description');
            $table->integer('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thumbnail_categories');
    }
};
