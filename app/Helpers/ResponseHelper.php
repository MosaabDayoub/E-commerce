<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponseHelper
{
    // success response
    public static function success($data = null, string $message = null): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? 'Data retrived successfully',
        ];

        if ($data instanceof LengthAwarePaginator) {
            $response['data'] = $data->items();
            $response['pagination'] = self::formatPagination($data);
        } else {
            $response['data'] = $data;
        }

        return response()->json($response);
    }

    // success response without data
    public static function successMessage(string $message = 'The process completed successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    // error response
    public static function error(string $message = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message ?? 'Faild',
        ], 400); 
    }

    // pagination format
    private static function formatPagination(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'total_pages' => $paginator->lastPage(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
        ];
    }
}