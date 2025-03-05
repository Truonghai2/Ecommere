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
        if (!Schema::hasTable('product_variations')) {
            Schema::create('product_variations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('product_id')->notNull();
                $table->string('poster')->notNull();
                $table->string('file_id')->notNull();
                $table->decimal('price', 10, 0)->notNull();
                $table->integer('sale')->notNull();
                $table->integer('quantity')->notNull();
                $table->integer('width')->notNull();
                $table->integer('height')->notNull();
                $table->integer('length')->notNull();
                $table->integer('weight')->notNull();
                $table->string('material')->notNull();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variation_attributes')) {
            Schema::create('product_variation_attributes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('product_variation_id')->notNull();
                $table->integer('attribute_id')->notNull();
                $table->integer('attribute_value_id')->notNull();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation_attributes');
        Schema::dropIfExists('product_variations');
    }
};
