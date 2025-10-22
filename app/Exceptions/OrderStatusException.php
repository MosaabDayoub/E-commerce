<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderStatusException extends Exception
{
    public function __construct(
        private string $action,
        private string $currentStatus, 
        private ?int $orderId = null,
        private ?int $userId = null
    ) {
        parent::__construct("Cannot {$action} order. Current status: {$currentStatus}");
    }

    public function report(): void
    {
        Log::channel('orders')->warning('Invalid order status operation', [
            'action' => $this->action,
            'order_id' => $this->orderId,
            'user_id' => $this->userId,
            'current_status' => $this->currentStatus,
            'message' => $this->getMessage()
        ]);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'error_code' => 'INVALID_ORDER_STATUS'
        ], 400);
    }
}