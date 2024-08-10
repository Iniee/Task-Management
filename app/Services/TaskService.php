<?php


namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Dtos\CreateTaskDto;
use App\Dtos\UpdateTaskDto;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{


    public function createTask(CreateTaskDto $createTaskDto, User $user): Task
    {
        $taskData = $createTaskDto->toArray();

        $taskData['user_id'] = $user->id;

        $task = Task::create($taskData);

        return $task;
    }

    public function viewTasks(User $user): Collection
    {
        return $user->tasks;
    }

    public function viewTask(User $user, Task $task): Task
    {
        return $user->tasks()->where('id', $task->id)->first();
    }

    public function updateTask(UpdateTaskDto $updateTaskDto, Task $task): bool
    {
        $updateData = $updateTaskDto->toArray();

        $filteredData = array_filter($updateData, function ($value) {
            return !is_null($value);
        });

        return $task->update($filteredData);
    }

    public function deleteTask(Task $task): bool
    {
        return $task->delete();;
    }
}