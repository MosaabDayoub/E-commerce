<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DeleteAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DeleteAccountRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = $request->user();
            
            $user->tokens()->delete();
            
            $user->delete();

            DB::commit();

            return ResponseHelper::successMessage(
                'Account deleted successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return ResponseHelper::error('Account deletion failed');
        }
    }
}