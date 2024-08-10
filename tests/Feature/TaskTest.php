<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('create task success', function () {
    $user = User::factory()->create();
    $taskData = [
        'title' => 'New Task',
        'description' => 'Task description',
        'due_by' => now()->addDays(5)->format('Y/m/d H:i'), // Ensure the format matches the validation rules
    ];

    $response = $this->actingAs($user)->postJson(route('create.task'), $taskData);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'message' => 'Task Successfully Created',
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => $taskData['title'],
        'description' => $taskData['description'],
        'due_by' => $taskData['due_by'],
        'user_id' => $user->id,
    ]);
});

test('fetch all tasks success', function () {
    $user = User::factory()->create();
    $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson(route('view.task'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'due_by',
                ],
            ],
        ]);
});

test('fetch single task success', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'due_by' => now()->addDays(5)->format('Y-m-d H:i:s'),
    ]);

    $response = $this->actingAs($user)->getJson(route('show.task', $task->id));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_by' => $task->due_by,
            ],
        ]);
});


test('update task success', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $updatedData = [
        'title' => 'Updated Task Title',
        'description' => 'Updated description',
        'due_by' => now()->addDays(5)->format('Y/m/d H:i'),
    ];

    $response = $this->actingAs($user)->putJson(route('update.task', $task->id), $updatedData);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Task Updated Successfully',
        ]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => $updatedData['title'],
        'description' => $updatedData['description'],
        'due_by' => $updatedData['due_by'],
    ]);
});

test('delete task success', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $task = Task::factory()->create(['user_id' => $user->id]);

    $response = $this->deleteJson(route('delete.task', $task->id));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Task Deleted Successfully',
        ]);
});

test('create task failure without authentication', function () {
    $taskData = [
        'title' => 'New Task',
        'description' => 'Task description',
        'due_by' => now()->format('Y/m/d H:i'),
    ];

    $response = $this->postJson(route('create.task'), $taskData);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});