<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrian_servis', function (Blueprint $table) {
            $table->string('kode_antrian', 20)->after('id')->nullable()->unique(); // Contoh: B-001, W-002
            $table->string('nama_pelanggan', 100)->after('kode_antrian')->nullable();
            $table->string('tipe_motor', 100)->after('no_polisi')->nullable();
            $table->string('no_hp')->nullable()->after('tipe_motor');
        });
    }

    public function down(): void
    {
        Schema::table('antrian_servis', function (Blueprint $table) {
            $table->dropColumn(['kode_antrian', 'nama_pelanggan', 'tipe_motor']);
        });
    }
};
