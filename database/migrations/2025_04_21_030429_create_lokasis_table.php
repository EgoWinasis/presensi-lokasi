<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokasisTable extends Migration
{
    public function up()
    {
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 7); // Menyimpan latitude dengan presisi tinggi
            $table->decimal('longitude', 10, 7); // Menyimpan longitude dengan presisi tinggi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lokasis');
    }
}
