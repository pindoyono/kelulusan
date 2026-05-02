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
        Schema::table('kelulusans', function (Blueprint $table) {
            $table->string('skl_path')->nullable()->after('nilai_rata_rata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelulusans', function (Blueprint $table) {
            $table->dropColumn('skl_path');
        });
    }
};
