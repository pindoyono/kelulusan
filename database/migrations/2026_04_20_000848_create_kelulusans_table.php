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
        Schema::create('kelulusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('pengumuman_id')->constrained('pengumumen')->cascadeOnDelete();
            $table->enum('status', ['lulus', 'tidak_lulus'])->default('lulus');
            $table->string('keterangan')->nullable();
            $table->decimal('nilai_rata_rata', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelulusans');
    }
};
