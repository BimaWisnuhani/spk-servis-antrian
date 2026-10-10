<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        if (!Schema::hasTable('antrian_servis')) {
            Schema::create('antrian_servis', function (Blueprint $table) {
                $table->id(); // nomor antrian

                $table->string('no_polisi');

                $table->enum('jenis_servis', [
                    'Servis sangat ringan',
                    'Servis Ringan',
                    'Servis Sedang',
                    'Servis Berat',
                    'Servis sangat berat'
                ]);

                $table->integer('estimasi_waktu'); // menit (cost)

                $table->string('antrian_pelanggan');
                $table->string('ketersediaan_mekanik');

                $table->enum('status_pesan', ['walk-in', 'booking'])
                      ->default('walk-in'); // benefit

                $table->date('tanggal_servis');
                $table->time('waktu_datang'); // jam datang

                $table->enum('status', ['pending', 'menunggu', 'proses', 'selesai', 'batal'])->default('pending');

                // $table->enum('status', ['menunggu', 'diproses', 'selesai'])
                //       ->default('menunggu');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian_servis');
    }
};
