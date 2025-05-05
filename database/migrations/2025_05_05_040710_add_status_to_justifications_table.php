<?php

use App\Enums\JustificationStatusEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('justifications', function (Blueprint $table) {
            $table->string('status')->default(JustificationStatusEnum::PENDING->value);
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('justificationss', function (Blueprint $table) {
            //
        });
    }
};
