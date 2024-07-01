<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskTaskController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Task $parent)
    {
        return view('tasks.tasks.create', [
            'parent' => $parent
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $parent)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $task = $parent->children()->create($validated);

        return redirect()->route('tasks.show', $parent)
            ->with('success', "Task $task->id is successfully created");
    }
}
