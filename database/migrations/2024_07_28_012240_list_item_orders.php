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
        if (!Schema::hasTable('list_item_orders')) {
            Schema::create('list_item_orders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('order_id');
                $table->bigInteger('product_id');
                $table->bigInteger('option_id')->nullable();
                $table->string('name');
                $table->integer('price');
                $table->integer('sale');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_item_orders');
    }
};
