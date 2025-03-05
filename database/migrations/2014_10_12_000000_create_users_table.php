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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('birth_day');
            $table->string('address')->nullable();
            $table->integer('type_payment')->default(0);
            $table->integer('verify_email')->default(0);
            $table->integer('verify_number')->default(0);
            $table->integer('coin')->default(0);
            $table->integer('role')->default(0);
            $table->integer('dark_mode')->default(0);
            $table->string('remember_token')->nullable();
            $table->string('device_token')->nullable();
            $table->integer('start_price')->default(0);
            $table->integer('end_price')->default(50000000);
            $table->integer('sort_price')->default(0);
            $table->integer('sort_favourite')->default(0);
            $table->integer('sort_sale')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
