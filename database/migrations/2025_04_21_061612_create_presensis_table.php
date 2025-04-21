<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id(); // Primary key id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->date('tgl'); // Tanggal (Date)

            // Fields for 'Jam Masuk'
            $table->time('jam_masuk')->nullable(); // Time of entry (optional)
            $table->string('foto_masuk')->nullable(); // Photo for jam masuk (base64 string)
            $table->json('lokasi_masuk')->nullable(); // Location for jam masuk (latitude and longitude)
            $table->text('ket_masuk')->nullable(); // Notes for jam masuk

            // Fields for 'Jam Keluar'
            $table->time('jam_keluar')->nullable(); // Time of exit (optional)
            $table->string('foto_keluar')->nullable(); // Photo for jam keluar (base64 string)
            $table->json('lokasi_keluar')->nullable(); // Location for jam keluar (latitude and longitude)
            $table->text('ket_keluar')->nullable(); // Notes for jam keluar

            $table->timestamps(); // Auto-generated created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensis');
    }
}
