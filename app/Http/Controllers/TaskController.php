<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Task\StoreRequest;
use App\Models\Task;
use App\Services\AiService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    public function show(Request $request)
    {
        return $request->user()->tasks;
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

    public function update($uuid)
    {
       $task = Task::where('uuid', $uuid)->get();
    }

    public function delete()
    {

    }
}
