<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrian_servis', function (Blueprint $table) {
            // Mengubah tipe kolom jenis_servis menjadi TEXT
            $table->text('jenis_servis')->change();
        });
    }

    public function down(): void
    {
        Schema::table('antrian_servis', function (Blueprint $table) {
            $table->string('jenis_servis', 255)->change();
        });
    }
};
