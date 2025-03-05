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
        if (Schema::hasTable('list_item_orders')) {
            Schema::table('list_item_orders', function (Blueprint $table) {
                $table->integer('quantity');
                $table->string('name_option')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('list_item_orders')) {
            Schema::table('list_item_orders', function (Blueprint $table) {
                $table->integer('quantity');
                $table->string('name_option')->nullable();
            });
        }
    }
};
