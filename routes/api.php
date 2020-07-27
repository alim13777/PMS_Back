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
Auth::routes(['verify' => true]);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(["prefix"=>"paper"],function(){
    Route::get("/",'paperController@index')->middleware('auth:sanctum');
    Route::get("/{paperId}","paperController@find")->middleware('auth:sanctum');
    Route::get("/party/{partyId}","paperController@findParty")->middleware('auth:sanctum');
    Route::post("/","paperController@createPaper")->middleware('auth:sanctum');
});

Route::get('email/verify', 'VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

