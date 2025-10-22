<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class EmptyCartException extends Exception
{
    public function __construct(private ?int $userId = null)
    {
        parent::__construct("The cart is empty, cannot create order");
    }

    public function report(): void
    {
        Log::channel('orders')->warning('Empty cart order attempt', [
            'user_id' => $this->userId
        ]);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'error_code' => 'EMPTY_CART'
        ], 400);
    }
}