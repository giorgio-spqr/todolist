<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends AbstractController
{
    private const DEFAULT_PER_PAGE = 5;
    public function index(Request $request): JsonResponse
    {
        $query = Task::query();

        $this->filterQueryByRequest($query, $request);

        $perPage = $this->resolvePerPage($request, self::DEFAULT_PER_PAGE);
        $paginator = $query->paginate($perPage);

        $resources = TaskResource::collection($paginator);

        return $resources->toResponse($request);
    }

    public function show(Task $task, Request $request): JsonResponse
    {
        $resource = TaskResource::make($task);

        return $resource->toResponse($request);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = new Task();

        $this->validateAndSave($task, $request);

        return $this->created();
    }

    public function update(Task $task, TaskRequest $request): JsonResponse
    {
        $this->validateAndSave($task, $request);

        return $this->noContent();
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return $this->noContent();
    }

    private function filterQueryByRequest(Builder $query, Request $request): void
    {
        $status = null;

        if ($request->exists('is_completed')) {
            $status = $request->boolean('is_completed');
        }

        if (is_bool($status)) {
            $query->where('is_completed', $status);
        }
    }

    private function validateAndSave(Task $task, TaskRequest $request): void
    {
        $toFill = $request->all();

        $task->fill($toFill);

        DB::transaction(function () use ($task) {
            $task->save();
        });
    }
}