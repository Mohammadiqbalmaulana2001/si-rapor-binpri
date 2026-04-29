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
        Schema::create('warga_binaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('no_register')->unique(); // Nomor Register Lapas
            $table->string('nomor_induk')->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('blok_kamar')->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_bebas')->nullable();
            $table->string('jenis_kejahatan')->nullable();
            $table->enum('status', ['aktif', 'bebas', 'pindah'])->default('aktif');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga_binaans');
    }
};
