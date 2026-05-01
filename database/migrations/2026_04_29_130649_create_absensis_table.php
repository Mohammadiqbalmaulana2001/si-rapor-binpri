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
        Schema::create('absensis', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('warga_binaan_id');
            $table->foreign('warga_binaan_id')->references('id')->on('warga_binaans')->onDelete('cascade');

            $table->uuid('kegiatan_id');
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->onDelete('cascade');

            $table->date('tanggal');
            $table->enum('kehadiran', ['hadir', 'tidak_hadir', 'izin', 'sakit'])->default('hadir');
            $table->enum('keaktifan', ['aktif', 'pasif', 'perlu_pembinaan_lanjutan'])->nullable();
            $table->text('catatan')->nullable();

            $table->uuid('dicatat_oleh')->nullable();
            $table->foreign('dicatat_oleh')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();

            $table->unique(['warga_binaan_id', 'kegiatan_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
