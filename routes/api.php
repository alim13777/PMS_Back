<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\models\party;
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
Auth::routes();
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum');
Route::group(["prefix"=>"papers"],function(){
    Route::get("/",function (Request $request){
       return \App\models\paper::all();
    })->name('readPaper')->middleware('can:read-paper');
});
