<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id(); // Kolom id otomatis dari Laravel (primary key)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key untuk user
            $table->date('tgl_cuti'); // Kolom tanggal cuti
            $table->text('keterangan')->nullable(); // Kolom keterangan
            $table->enum('status_admin', ['belum divalidasi', 'disetujui', 'ditolak'])->default('belum divalidasi'); // Status admin
            $table->enum('status_superadmin', ['belum divalidasi', 'disetujui', 'ditolak'])->default('belum divalidasi'); // Status superadmin
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
