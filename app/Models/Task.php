<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'priority', 'state',
        'time_estimated', 'time_spent'];

    /**
     * a Task might belong to a parent task
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        // To prevent confusion, added the PK column name
        // Apparently, Laravel has some trouble managing recursive relationships
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * a Task might have child Tasks
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the project's task count
     *
     * @return Attribute
     */
    public function hasChildren(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->children()->count() > 0
        );
    }

    /**
     * Get the project's task count
     *
     * @return Attribute
     */
    public function taskCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->children()->count()
        );
    }

    /**
     * Get the project's completed task count
     *
     * @return Attribute
     */
    public function completedTaskCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->countTasksInState('completed')
        );
    }

    private function countTasksInState(string $state)
    {
        return $this->children->reduce( fn (?int $carry, Task $item) =>
        $item->state == $state ? $carry + 1 : $carry
            , 0);
    }

    public function timeSpent(): Attribute
    {
        // Overriding this attribute
        return Attribute::make(
            get: fn(?int $value) => $this->has_children ? $this->computeTimeSpent() : $value
        );
    }

    public function timeEstimated(): Attribute
    {
        // Overriding this attribute
        return Attribute::make(
            get: fn(?int $value) => $this->has_children ? $this->computeTimeEstimated() : $value
        );
    }

    public function state(): Attribute
    {
        // Overriding this attribute
        return Attribute::make(
            get: fn(string $value) => $this->has_children ? $this->computeState() : $value
        );
    }

    /*
     * Helper function that returns the sum of all the time spent of each child
     */
    private function computeTimespent() : int
    {
        return $this->children->reduce(
            fn (?int $carry, Task $item) => $carry + $item->time_spent,
            0
        );
    }

    /*
     * Helper function that returns the sum of all the time estimated of each child
     */
    private function computeTimeEstimated()
    {
        return $this->children->reduce(
            fn (?int $carry, Task $item) => $carry + $item->time_estimated,
            0
        );
    }

    private function computeState()
    {
        // all child tasks are in state ‘new’ or there are no child tasks present:
        $new_tasks_count = $this->countTasksInState('new');
        if ($this->task_count == 0 || $this->task_count == $new_tasks_count) {
            return 'new';
        }

        // all the child tasks have state ‘cancelled’
        $cancelled_tasks_count = $this->countTasksInState('cancelled');
        if ($this->task_count == $cancelled_tasks_count) {
            return 'cancelled';
        }

        // all the child tasks have state ‘completed’, but one or more might be ‘cancelled’
        $completed_task_count = $this->countTasksInState('completed');
        if ($this->task_count - $cancelled_tasks_count == $completed_task_count) {
            return 'completed';
        }

        // all the child tasks have state ‘deferred’, but one or more might be either ‘completed’ or ‘cancelled’
        $deferred_task_count = $this->countTasksInState('deferred');
        if ($this->task_count - $completed_task_count - $cancelled_tasks_count == $deferred_task_count) {
            return 'deferred';
        }

        // all child tasks have state ‘on hold’, but one or more might have either ‘new’, ‘completed’ or ‘cancelled’
        $on_hold_task_count = $this->countTasksInState('on hold');
        if ($this->task_count - $new_tasks_count - $completed_task_count - $cancelled_tasks_count
            == $on_hold_task_count) {
            return 'on hold';
        }

        // all child tasks have state ‘planned’, but one or more might have either ‘new’, ‘completed’ or ‘cancelled’
        $planned_task_count = $this->countTasksInState('planned');
        if ($this->task_count - $new_tasks_count - $completed_task_count - $cancelled_tasks_count
            == $planned_task_count) {
            return 'planned';
        }

        // when it is not in any of the other states:
        return 'in progress';
    }

    /**
     * Returns the progress of this task, which is the ratio between the
     * estimated and spent time. This is a percentage.
     *
     * @return Attribute
     */
    public function progress(): Attribute
    {
        return Attribute::make(
            get: function() {
                // Prevent divide by zero errors
                if ($this->time_estimated == 0)
                    return 0;

                return number_format(100 * $this->time_spent / $this->time_estimated, 0);
            }
        );
    }

    /**
     * Returns the pace of the task in minutes per percent-point. When the
     * progress is 0, the pace can't be calculated and `null` is returned.
     *
     * @return Attribute
     */
    public function pace(): Attribute
    {
        return Attribute::make(
            get: function() {
                $progress = $this->progress;

                if ($progress == null || $progress == 0)
                    return null;

                $time = now()->diffInMinutes($this->created_at);

                return $time / $progress;
            }
        );
    }

    /**
     * Returns the expected completed at timestamp. When this task state is
     * such that it can't be calculated, `null` is returned.
     *
     * @return Attribute
     */
    public function expectCompletedAt(): Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->has_children) {
                    return $this->children->reduce(
                        fn (?Carbon $carry, Task $item) => max($item->expect_completed_at, $carry) ,
                        null
                    );
                }
                $pace = $this->pace;

                if ($pace == null)
                    return null;

                return now()->addMinutes($pace * (100 - $this->progress));
            }
        );
    }

    /**
     * Workflow for completing a task.
     *
     * @return void
     */
    public function complete()
    {
        $this->state = 'completed';
        $this->completed_at = Carbon::now();
        $this->save();
    }
}
