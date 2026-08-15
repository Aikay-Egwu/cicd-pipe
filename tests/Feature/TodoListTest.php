<?php

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it lists all todos', function () {
    $first = Todo::factory()->create();
    $second = Todo::factory()->create();

    $this->getJson('/api/todos')
        ->assertOk()
        ->assertJsonCount(2)
        ->assertJsonPath('0.id', $first->id)
        ->assertJsonPath('0.title', $first->title)
        ->assertJsonPath('1.id', $second->id);
});

test('it creates a todo', function () {
    $payload = [
        'subject' => 'Groceries',
        'title' => 'Buy milk',
        'description' => 'Pick up two cartons',
        'due_date' => '2026-08-20',
        'complete' => false,
    ];

    $this->postJson('/api/todos', $payload)
        ->assertCreated()
        ->assertJsonPath('title', 'Buy milk')
        ->assertJsonPath('complete', false);

    $this->assertDatabaseHas('todos', [
        'subject' => 'Groceries',
        'title' => 'Buy milk',
        'description' => 'Pick up two cartons',
        'due_date' => '2026-08-20',
        'complete' => false,
    ]);
});

test('it defaults a new todo to incomplete', function () {
    $this->postJson('/api/todos', [
        'title' => 'Water the plants',
    ])->assertCreated()
        ->assertJsonPath('complete', false);

    $this->assertDatabaseHas('todos', [
        'title' => 'Water the plants',
        'complete' => false,
    ]);
});

test('it requires a title when creating a todo', function () {
    $this->postJson('/api/todos', [
        'subject' => 'Missing title',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('title');
});

test('it shows a todo', function () {
    $todo = Todo::factory()->create();

    $this->getJson("/api/todos/{$todo->id}")
        ->assertOk()
        ->assertJsonPath('id', $todo->id)
        ->assertJsonPath('title', $todo->title)
        ->assertJsonPath('complete', $todo->complete);
});

test('it returns 404 for a missing todo', function () {
    $this->getJson('/api/todos/999999')
        ->assertNotFound();
});

test('it updates a todo', function () {
    $todo = Todo::factory()->create();

    $this->putJson("/api/todos/{$todo->id}", [
        'subject' => 'Updated subject',
        'title' => 'Updated title',
        'description' => 'Updated description',
        'due_date' => '2026-09-01',
        'complete' => true,
    ])->assertOk()
        ->assertJsonPath('title', 'Updated title')
        ->assertJsonPath('complete', true);

    $this->assertDatabaseHas('todos', [
        'id' => $todo->id,
        'subject' => 'Updated subject',
        'title' => 'Updated title',
        'complete' => true,
    ]);
});

test('it supports partial updates', function () {
    $todo = Todo::factory()->incomplete()->create();

    $this->patchJson("/api/todos/{$todo->id}", [
        'complete' => true,
    ])->assertOk()
        ->assertJsonPath('complete', true)
        ->assertJsonPath('title', $todo->title);

    $this->assertDatabaseHas('todos', [
        'id' => $todo->id,
        'title' => $todo->title,
        'complete' => true,
    ]);
});

test('it deletes a todo', function () {
    $todo = Todo::factory()->create();

    $this->deleteJson("/api/todos/{$todo->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('todos', [
        'id' => $todo->id,
    ]);
});
