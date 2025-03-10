<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('searchables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('searchable_type');
            $table->unsignedBigInteger('searchable_id');
            $table->text('content');
            $table->timestamps();

            $table->index(['searchable_type', 'searchable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('searchables');
    }
};
