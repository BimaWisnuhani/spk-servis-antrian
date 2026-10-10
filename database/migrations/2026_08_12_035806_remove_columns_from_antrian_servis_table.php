<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrian_servis', function (Blueprint $table) {
            $table->dropColumn(['antrian_pelanggan', 'ketersediaan_mekanik']);
        });
    }

    public function down(): void
    {
        Schema::table('antrian_servis', function (Blueprint $table) {
            $table->string('antrian_pelanggan')->nullable();
            $table->string('ketersediaan_mekanik')->nullable();
        });
    }
};
