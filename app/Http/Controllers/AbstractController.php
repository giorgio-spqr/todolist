<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\DuplicateDetectedException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\LegallyRejectedException;
use App\Exceptions\NotFoundException;
use App\Exceptions\PartnerTokenExpiredException;
use App\Exceptions\RejectedException;
use App\Http\Responses\Shared\NoContentResponse;
use App\Integration\Responses\AbstractConsumerResponse;
use App\OpenApiDefinitions\Shared\Parameters\OrderByParameter;
use App\OpenApiDefinitions\Shared\Parameters\OrderDirectionParameter;
use App\OpenApiDefinitions\Shared\Parameters\PerPageParameter;
use App\OpenApiDefinitions\Shared\Parameters\SearchParameter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;

abstract class AbstractController extends Controller
{
    protected function resolvePerPage(Request $request, ?int $defaultPerPage = null): ?int
    {
        return $request->query('per_page', $defaultPerPage);
    }

    protected function success($data = []): JsonResponse
    {
        return response()->json($data);
    }

    protected function created($data = null): JsonResponse
    {
        return response()->json($data, Response::HTTP_CREATED);
    }

    protected function accepted(): JsonResponse
    {
        return response()->json([], Response::HTTP_ACCEPTED);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @deprecated
     */
    protected function duplicates()
    {
        throw new DuplicateDetectedException();
    }

    /**
     * @deprecated
     */
    protected function rejected()
    {
        throw new RejectedException();
    }

    /**
     * @deprecated
     */
    protected function partnerTokenExpired()
    {
        throw new PartnerTokenExpiredException();
    }

    /**
     * @deprecated
     */
    protected function legallyRejected()
    {
        throw new LegallyRejectedException();
    }

    /**
     * @deprecated
     */
    protected function badRequest(?string $message = null)
    {
        if (empty($message)) {
            $message = 'Bad request';
        }

        throw new BadRequestException(message: $message);
    }

    /**
     * @deprecated
     */
    protected function notFound()
    {
        throw new NotFoundException();
    }

    /**
     * @deprecated
     */
    protected function forbidden()
    {
        throw new ForbiddenException();
    }

    protected function addSearchQuery(Builder $query, Request $request, array $columns): void
    {
        $search = $request->input(SearchParameter::NAME, '');
        if (empty($search)) {
            return;
        }
        if (!is_string($search)) {
            return;
        }

        $search = explode(' ', $search);
        foreach ($search as $part) {
            $query->where(function (Builder $builder) use ($part, $columns) {
                foreach ($columns as $column) {
                    $builder->orWhere($column, 'like', '%' . $part . '%');
                }
            });
        }
    }

    protected function addOrderingQuery(Builder $query, Request $request, ?string $default = null): void
    {
        $orderBy = $request->input(OrderByParameter::NAME, []);
        $orderDirection = $request->input(OrderDirectionParameter::NAME, []);

        if (!is_array($orderBy)) {
            $orderBy = [];
        }

        if (empty($orderBy) and $default) {
            $orderBy[$default] = null;
        }

        $table = $query->getModel()->getTable();

        foreach ($orderBy as $column => $isEnabled) {
            if (!$isEnabled) {
                continue;
            }
            if (!Schema::hasColumn($table, $column)) {
                continue;
            }

            $direction = 'asc';
            if (data_get($orderDirection, $column) === 'true') {
                $direction = 'desc';
            }

            $query->orderBy($column, $direction);
        }
    }

    protected function getOrderingDirection(array $orderDirection): string
    {
        if (empty($orderDirection)) {
            return 'asc';
        }

        return array_values($orderDirection)[0] == 1 ? 'desc' : 'asc';
    }

    /**
     * @deprecated
     */
    protected function toJsonResponse(AbstractConsumerResponse $response): JsonResponse
    {
        return $response->toJson();
    }
}
