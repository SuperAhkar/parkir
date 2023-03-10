<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterTempatParkir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_tempat_parkir', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('slot');
            $table->bigInteger('biaya');
            $table->string('lokasi');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('role');
            $table->string('image');
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
        Schema::dropIfExists('register_tempat_parkir');
    }
}
