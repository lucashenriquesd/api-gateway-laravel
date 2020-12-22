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

Route::get('/', function () {
    $laravel = app();
    $version = $laravel::VERSION;

    return response()->json([
        'msg' => "Laravel ($version) (Laravel Components ^8.0)",
    ]);
});

Route::get('/ms-files-lumen', function () {
    $response = Http::withHeaders([
        'app-token' => env('MS_FILES_LUMEN'),
    ])->get('http://ms-files-lumen8-nginx/');

    $response_body_object = json_decode($response->body());

    return response()->json([
        'msg' => $response_body_object->msg,
    ]);
});

Route::get('encryptRandom40', function () {
    return response()->json(['msg' => Crypt::encrypt(Str::random(40))]);
});

Route::get('uuid', function () {
    return response()->json(['msg' => Str::uuid()]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});
