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
        Schema::create('letter_ins', function (Blueprint $table) {
            $table->id();
            $table->string('judul_surat');
            $table->string('nomor_surat');
            $table->string('tanggal_surat');
            $table->string('tanggal_masuk');
            $table->string('asal_surat');
            $table->string('sifat_surat');
            $table->string('kategori_surat');
            $table->string('file');
            $table->enum('status_disposisi', ['sudah_disposisi', 'belum_disposisi'])->default('belum_disposisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_ins');
    }
};
