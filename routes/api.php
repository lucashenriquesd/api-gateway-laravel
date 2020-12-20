<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function (Request $request) {
    return response()->json([
        'msg' => 'Hello World',
    ]);
});

Route::get('/ms-files-lumen', function (Request $request) {
    $response = Http::withHeaders([
        'app-token' => env('MS_FILES_LUMEN'),
    ])->get('http://ms-files-lumen8-nginx/');

    return response()->json([
        'msg' => $response->body(),
    ]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
