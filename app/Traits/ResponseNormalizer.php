<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseNormalizer{

    protected function success(mixed $data = null, mixed $message = 'Successfull'):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => true,
            'data' => $data
        ],200);
    }

    protected function noContent():JsonResponse{
        return response()->json([
            'message' => 'no content',
            'status' => true,
            'data' => null
        ],200);
    }

    protected function created(mixed $data = null, mixed $message = 'Record Created'):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => true,
            'data' => $data
        ],201);
    }

    protected function badResponse(mixed $data = null, mixed $message = "Bad request"):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => false,
            'data' => $data
        ],400);
    }
    
    protected function customResponse(mixed $data = null, bool $status = true, mixed $message = null, int $statusCode = 200):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ],$statusCode);
    }

    protected function forbidden(mixed $data = null, mixed $message = null):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => false,
            'data' => $data
        ],403);
    }
    protected function error(mixed $data = null, mixed $message = null):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => false,
            'data' => $data
        ],500);
    }

    protected function unauthenticated(mixed $message = null,mixed $data = null):JsonResponse{
        return response()->json([
            'message' => $message,
            'status' => false,
            'data' => $data
        ],401);
    }
}