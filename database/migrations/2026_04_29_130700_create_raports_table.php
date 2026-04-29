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
        Schema::create('raports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warga_binaan_id')
                ->constrained('warga_binaans')
                ->cascadeOnDelete();

            $table->year('tahun');
            $table->tinyInteger('bulan');
            $table->integer('total_kegiatan');
            $table->integer('total_hadir');
            $table->integer('total_aktif');
            $table->integer('total_pasif');
            $table->integer('total_perlu_pembinaan');
            $table->decimal('persentase_kehadiran', 5, 2);
            $table->enum('rekomendasi', ['sangat_baik', 'baik', 'cukup', 'kurang'])->nullable();
            $table->text('catatan_petugas')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();

            $table->unique(['warga_binaan_id', 'tahun', 'bulan']);
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raports');
    }
};
