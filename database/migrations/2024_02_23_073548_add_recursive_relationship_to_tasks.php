<?php

use App\Models\Project;
use App\Models\Task;
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
        Schema::table('tasks', function (Blueprint $table) {
            // Create the new FK
            $table->foreignId('task_id')
                ->after('id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
        // NICE TO HAVE: migrating the old projects into tasks
        foreach (Project::all() as $project) {
            $task = Task::create([
                'title' => $project->title,
                'description' => $project->description
            ]);
            foreach ($project->tasks as $child) {
                $task->children()->save($child);
            }
        }
        // Then, drop the projects FK index and the table (ALSO NICE TO HAVE)
        Schema::table('tasks', function (Blueprint $table) {
            // It is nice to also drop the foreign key index on rollback
            $table->dropForeign('tasks_project_id_foreign');
            $table->dropColumn('project_id');
        });
        Schema::dropIfExists('projects');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_task_id_foreign');
        });
    }
};
