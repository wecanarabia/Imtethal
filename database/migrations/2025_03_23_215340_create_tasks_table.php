<?php

use App\Enums\PriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Enums\TaskTypeEnum;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
			$table->text('description')->nullable();
			$table->string('task_type')->default(TaskTypeEnum::RECURRING->value);
			$table->string('delivery_status')->nullable();
			$table->integer('task_repetition');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
			$table->string('note')->nullable();
			$table->datetime('delivery_time')->nullable();
			$table->datetime('grace_end_time')->nullable();
			$table->integer('task_evaluation')->default(0);
			$table->string('priority')->default(PriorityEnum::HIGH->value);
			$table->text('delay_puneshment')->nullable();
			$table->string('status')->default(TaskStatusEnum::PENDING->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
