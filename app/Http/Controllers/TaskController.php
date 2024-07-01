<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of Tasks.
     */
    public function index()
    {
        // Fetch the project-level tasks, which have no parent task
        $projects = Task::whereNull('task_id')->get();
        return view('tasks.index', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new Task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created Task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $task = Task::create($validated);

        return redirect()->route('home')
            ->with('success', "Task $task->id is successfully created");
    }

    /**
     * Display the specified Task.
     */
    public function show(Task $task)
    {
        return view('tasks.show', [
            'task' => $task
        ]);
    }

    /**
     * Show the form for editing the specified Task.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', [
            'task' => $task
        ]);
    }

    /**
     * Update the specified Task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'state' => 'required',
            'time_estimated' => 'required|numeric|min:0',
        ]);

        // Update the attributes
        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', "Task $task->id is successfully updated");
    }

    public function complete(Task $task)
    {
        $task->complete();

        // Redirect to the tasks.show page
        return redirect()->route('tasks.show', $task)
            ->with('success', "Task $task->id is successfully completed");
    }

    /**
     * Remove the specified Task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        // Redirect to the proper page
        if($task->parent) {
            $route = redirect()->route('tasks.show', $task->parent);
        } else {
            $route = redirect()->route('tasks.index');
        }

        return $route->with('success', "Task $task->id is successfully deleted");
    }
}
