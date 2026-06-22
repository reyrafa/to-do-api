<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\AiService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    public function show(Request $request)
    {
        return TaskResource::collection($request->user()->tasks);
    }

    public function store(StoreRequest $request, AiService $gemini)
    {
        $validatedRequest = $request->validated();

        $tasks = $gemini->generateTaskGroq(
            $validatedRequest['title']
        );

        foreach ($tasks as $task) {
            Task::create([
                ...$task,
                'user_id' => $request->user()->id
            ]);
        }
        return ApiResponse::success(
            message: 'Tasks created successfully',
            status: Response::HTTP_CREATED
        );
    }

    public function update(UpdateRequest $request, $uuid)
    {
        $validatedRequest = $request->validated();
        $task = Task::where('uuid', $uuid)->first();
        $this->authorize('update', $task);
        $updatedTask = $task->update($validatedRequest);
        return ApiResponse::success(
            status: Response::HTTP_OK,
            data: $updatedTask
        );
    }

    public function view($uuid)
    {
        $task = Task::where('uuid', $uuid)->first();
        $task->load('user');
        if (!$task) {
            return ApiResponse::error(
                message: 'Task cannot be found',
                status: Response::HTTP_NOT_FOUND,
            );
        }
        $this->authorize('view', $task);
        return ApiResponse::success(
            message: 'Task successfully fetched',
            status: Response::HTTP_OK,
            data: new TaskResource($task)
        );
    }

    public function delete()
    {

    }
}
