<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJamAbsenTable extends Migration
{
    public function up()
    {
        Schema::create('jam_absen', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk'); // Clock-in time
            $table->time('jam_keluar'); // Clock-out time
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jam_absen');
    }
}
