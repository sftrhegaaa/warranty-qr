<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        $path = resource_path('data/countries.json');

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'countries.json tidak ditemukan',
                'path' => $path,
            ], 404);
        }

        $json = file_get_contents($path);
        $countries = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'countries.json tidak valid',
                'error' => json_last_error_msg(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }
}