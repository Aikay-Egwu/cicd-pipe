<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    /**
     * Display a listing of todos.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            Todo::query()->orderBy('id')->get()
        );
    }

    /**
     * Store a newly created todo.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'complete' => ['boolean'],
        ]);

        $validated['complete'] ??= false;

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    /**
     * Display the specified todo.
     */
    public function show(Todo $todo): JsonResponse
    {
        return response()->json($todo);
    }

    /**
     * Update the specified todo.
     */
    public function update(Request $request, Todo $todo): JsonResponse
    {
        $validated = $request->validate([
            'subject' => ['sometimes', 'nullable', 'string', 'max:255'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'complete' => ['sometimes', 'boolean'],
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    /**
     * Remove the specified todo.
     */
    public function destroy(Todo $todo): Response
    {
        $todo->delete();

        return response()->noContent();
    }
}
