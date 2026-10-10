<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian_servis_sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_servis_id')->constrained('antrian_servis')->onDelete('cascade');
            // Mengarahkan foreign key ke tabel sub_kriteria
            $table->foreignId('sub_kriteria_id')->constrained('sub_kriteria')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian_servis_sub_kriteria');
    }
};
