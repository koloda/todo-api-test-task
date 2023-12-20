<?php

use App\Models\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parentId')->nullable()->constrained('tasks')->onDelete('cascade');
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', [TaskStatus::Todo->value, TaskStatus::Done->value])->default(TaskStatus::Done->value);
            $table->unsignedTinyInteger('priority')->default(1);
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
            $table->dateTime('completedAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
