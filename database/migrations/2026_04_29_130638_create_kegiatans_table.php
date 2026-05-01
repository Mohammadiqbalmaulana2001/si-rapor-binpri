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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kegiatan'); // Sholat Dhuha, Khataman, Senam, dll
            $table->string('kategori'); // Islam, Kristen, Umum
            $table->text('deskripsi')->nullable();
            $table->enum('frekuensi', ['harian', 'mingguan', 'bulanan']);
            $table->string('hari')->nullable(); // Senin, Selasa, dll (untuk mingguan)
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('penanggung_jawab')->nullable(); // Ustad/Pendeta/Petugas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
