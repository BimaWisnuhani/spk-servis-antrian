<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel bobot_kriteria (C1-C5)
            $table->foreignId('bobot_kriteria_id')
                  ->constrained('bobot_kriteria')
                  ->onDelete('cascade');

            $table->string('nama_sub_kriteria'); // Contoh: "21-40 menit" atau "Servis Ringan"
            $table->string('deskripsi');         // Contoh: "cepat" atau "Ganti Oli"
            $table->integer('skor');              // Contoh: 1, 2, 3, 4, 5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kriteria');
    }
};
