<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rdkks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('petanis');
            $table->foreignId('pupuk_id')->constrained('pupuks');
            $table->foreignId('komoditas_id')->constrained('komoditas');
            $table->integer('stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rdkks');
    }
};
