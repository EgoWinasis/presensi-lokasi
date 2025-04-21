<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Name of the holiday
            $table->date('date');    // Date of the holiday
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }

};
