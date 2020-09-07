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
Route::get('email/verify', 'VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');


Route::get("/paper/party",function(Request $request){
    $partyId=$request->user()->partyId;
    return \App\Http\Controllers\paperController::findPartyPaper($partyId);
})->middleware('auth:sanctum');
Route::get("/paper/party/{partyId}",function($partyId){
    return \App\Http\Controllers\paperController::findPartyPaper($partyId);
})->middleware('auth:sanctum');
Route::get("/paper",function(){
    return \App\Http\Controllers\paperController::index();
})->middleware('auth:sanctum');
Route::get("/paper/{paperId}",function($paperId){
    return \App\Http\Controllers\paperController::find($paperId);
})->middleware('auth:sanctum');
