<?php

namespace App\Helpers\ApisHelper;

use Illuminate\Support\Facades\DB;

class ApisResponseHelper
{
    public static function handleValidationException($e)
    {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Validation error.',
            'errors' => $e->errors()
        ], 422);
    }

    public static function handleDatabaseException($e)
    {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Database error occurred. Please try again later.',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }

    public static function handleGenericException($e)
    {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again later.',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }
}