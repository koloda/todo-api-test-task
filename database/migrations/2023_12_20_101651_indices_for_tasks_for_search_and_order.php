<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('status', 'tasks_status_index');
            $table->index('priority', 'tasks_priority_index');
            $table->fullText(['title', 'description'], 'tasks_title_description_fulltext');
        });

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE tasks ADD KEY priority_createdAt_order (priority ASC, createdAt DESC)');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE tasks ADD KEY priority_completedAt_order (priority ASC, completedAt DESC)');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE tasks ADD KEY createdAt_completedAt_order (createdAt ASC, completedAt DESC)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_status_index');
            $table->dropIndex('tasks_priority_index');
            $table->dropIndex('tasks_title_description_fulltext');
            $table->dropIndex('priority_createdAt_order');
            $table->dropIndex('priority_completedAt_order');
            $table->dropIndex('createdAt_completedAt_order');
        });
    }
};
