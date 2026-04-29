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
            $table->id();

            $table->foreignId('warga_binaan_id')
                ->constrained('warga_binaans')
                ->cascadeOnDelete();

            $table->foreignId('kegiatan_id')
                ->constrained('kegiatans')
                ->cascadeOnDelete();

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
