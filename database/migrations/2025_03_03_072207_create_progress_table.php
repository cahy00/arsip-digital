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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispotition_id')->constrained('dispotitions')->onDelete('cascade');
            $table->enum('status_progress', ['belum_selesai', 'on_progress', 'selesai'])->default('belum_selesai');
            $table->text('ket')->nullable();
            $table->string('document-progress')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
