<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

abstract class AbstractController extends Controller
{
    protected function resolvePerPage(Request $request, ?int $defaultPerPage = null): ?int
    {
        return $request->query('per_page', $defaultPerPage);
    }

    protected function created($data = null): JsonResponse
    {
        return response()->json($data, Response::HTTP_CREATED);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
