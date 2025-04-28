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
        Schema::create('justifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->foreignId('task_delivery_id')->constrained('task_deliveries')->onDelete('cascade');
			$table->foreignId('assignee_id')->constrained('assignees')->onDelete('cascade');
			$table->text('note')->nullable();
			$table->text('reply')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justifications');
    }
};
