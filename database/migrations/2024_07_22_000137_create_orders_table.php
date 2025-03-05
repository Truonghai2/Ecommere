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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->notNull();
                $table->integer('payment_id')->default(0);
                $table->integer('payment_status')->default(0);
                $table->string('to_ward_name');
                $table->string('to_district_name');
                $table->string('to_province_name');
                $table->string('to_user_name');
                $table->string('to_phone_number');
                $table->integer('price_old');
                $table->integer('price_save');
                $table->integer('price_ship');
                $table->integer('price_new');
                $table->integer('status_order')->default(0);
                $table->text('content');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
