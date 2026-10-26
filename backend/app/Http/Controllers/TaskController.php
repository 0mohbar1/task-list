<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tasks = Task::orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return TaskResource::make($task)
            ->response()
            ->setStatusCode(201);
    }

    public function complete(Task $task): TaskResource
    {
        $task->completed = true;
        $task->save();

        return TaskResource::make($task);
    }
}
