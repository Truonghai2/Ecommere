<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('poster');
            $table->string('file_id');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->text('description');
            $table->integer('price')->nullable();
            $table->integer('sale')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('brand');
            $table->integer('option_type');
            $table->string('material');
            $table->integer('guarantee');
            $table->string('country')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('quantity_saled')->default(0);
            $table->float('total_rate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
