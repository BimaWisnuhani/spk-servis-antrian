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
        Schema::create('bobot_kriteria', function (Blueprint $table) {
            $table->id(); // Primary key (id)
            $table->string('kode', 10); // Contoh: C1, C2, dst.
            $table->string('nama_kriteria');
            $table->enum('tipe', ['benefit', 'cost']); // Biasanya berisi 'benefit' atau 'cost'
            $table->double('bobot'); // Menggunakan double agar bisa menampung angka desimal (0.25, dsb)
            $table->timestamps(); // create_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_kriteria');
    }
};
