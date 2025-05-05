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
        Schema::table('justifications', function (Blueprint $table) {
            $table->foreignId('punishment_id')->nullable()->constrained('punishments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('justifications', function (Blueprint $table) {
            $table->dropColumn('punishment_id');
        });
    }
};
