<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Dtos\CreateTaskDto;
use App\Dtos\UpdateTaskDto;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Traits\ResponseNormalizer;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    use ResponseNormalizer;

    public function __construct(public readonly TaskService $taskService) {}

    /* Instance to Create a User's Task
    */
    public function store(CreateTaskRequest $request): Response
    {
        $user = auth()->user();

        $validatedData = CreateTaskDto::fromArray($request->validated());

        $task = $this->taskService->createTask($validatedData, $user);

        if ($task) {
            return $this->created(message: "Task Successfully Created");
        }

        return $this->error(message: "Error Occured");
    }

    /* Instance to Fetch a All User's Tasks
    */
    public function index(): Response
    {
        $user = auth()->user();

        $task = $this->taskService->viewTasks($user);

        if ($task) {
            return $this->success(data: TaskResource::collection($task));
        }

        return $this->error(message: "Error Occured");
    }

    /* Instance to Fetch a User's Single Task
    */
    public function show(Task $task): Response
    {
        $user = auth()->user();

        if (!$user->tasks->contains($task)) {
            return $this->badResponse(message: "Invalid Task");
        }

        $task = $this->taskService->viewTask($user, $task);

        if ($task) {
            return $this->success(data: new TaskResource($task));
        }

        return $this->error(message: "Error Occured");
    }

    /* Instance to Update a User's Single Task
    */
    public function update(UpdateTaskRequest $request, Task $task): Response
    {
        $user = auth()->user();

        if (!$user->tasks->contains($task)) {
            return $this->badResponse(message: "Invalid Task");
        }

        $validatedData = UpdateTaskDto::fromArray($request->validated());

        $task = $this->taskService->updateTask($validatedData, $task);

        if ($task) {
            return $this->success(message: "Task Updated Successfully");
        }

        return $this->error(message: "Error Occured");
    }


    /* Instance to Delete a User's Single Task
    */
    public function destroy(Task $task): Response
    {
        $user = auth()->user();

        if (!$user->tasks->contains($task)) {
            return $this->badResponse(message: "Invalid Task");
        }
        
        $task = $this->taskService->deleteTask($task);

        if ($task) {
            return $this->success(message: "Task Deleted Successfully");
        }

        return $this->error(message: "Error Occured");
    }
}