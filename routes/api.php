<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\models\party;
use App\Http\Controllers\paperController;
use App\Http\Controllers\partyController;
use App\models\paper;
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
Route::put("/changePassword",function(Request $request){
     $user=$request->user();
     $registerController=new \App\Http\Controllers\Auth\RegisterController();
     return $registerController->changePassword($request,$user);
})->middleware("auth:sanctum");
Route::get("/paper/party",function(Request $request){
    $partyId=$request->user()->partyId;
    $paperController=new paperController();
    $user=$request->user();
    return $paperController->findPartyPaper($partyId,$user,false);
})->middleware("auth:sanctum");
Route::get("/paper/party/{partyId}",function($partyId,Request $request){
    $paperController=new paperController();
    $user=$request->user();
    return $paperController->findPartyPaper($partyId,$user,false);
})->middleware("auth:sanctum");
Route::get("/paper",function(){
    $paperController=new paperController();
    return $paperController->index();
})->middleware("auth:sanctum");
Route::get("/paper/{paperId}",function($paperId,Request $request){
    $paperController=new paperController();
    $user=$request->user();
    $history=isset($request["history"])?$request["history"]:false;
    return $paperController->find($paperId,$user,$history);
})->middleware("auth:sanctum");
Route::post("/paper",function (Request $request){
    $paperController=new paperController();
    return $paperController->createPaper($request);
})->middleware("auth:sanctum");
Route::put("/paper",function (Request $request){
    $paperController=new paperController();
    return $paperController->editPaper($request);
})->middleware("auth:sanctum");
Route::post("/paper/party",function(Request $request){
    $paper=new paper();
    $paper->paperId= $request->paperId;
    $paperController=new paperController();
    $publisher=$request->publisher;
    $author=$request->author;
    if(!$author)$author=[];
    return $paperController->addPaperParty($author,$publisher,$paper);
})->middleware("auth:sanctum");
Route::delete("/paper/party",function(Request $request){
    $paper=new paper();
    $paper->paperId= $request->paperParty[0]["paperId"];
    $paperController=new paperController();
    return $paperController->deletePaperParty($request->paperParty,$paper);
})->middleware("auth:sanctum");
Route::put("/paper/party",function(Request $request){
    $paper=new paper();
    $paper->paperId= $request->paperParty[0]["paperId"];
    $paperController=new paperController();
    return $paperController->updatePaperParty($request->paperParty,$paper);
})->middleware("auth:sanctum");
Route::post("/paper/paperState",function (Request $request){
     $paperController=new paperController();
    $status=$request->publisher["status"];
    return $paperController->addPaperStatus($request->publisher,$status);
})->middleware("auth:sanctum");
Route::get("/party/person/search",function (Request $request){
    $partyController=new partyController();
    return $partyController->searchPerson($request);
})->middleware("auth:sanctum");
Route::get("/party/journal",function(){
    $partyController=new partyController();
    return $partyController->indexJournal();
})->middleware("auth:sanctum");
Route::get("/party/person/{partyId}",function($partyId){
    $partyController=new partyController();
    return $partyController->getPerson($partyId);
})->middleware("auth:sanctum");
Route::put("/party/person",function (Request $request){
    $partyController=new partyController();
    return $partyController->updatePerson($request);
})->middleware("auth:sanctum");
Route::post("/file",function (Request $request){
    $uploadFileContlorler=new \App\Http\Controllers\UploadFileController();
    return $uploadFileContlorler->uploadFile($request);
})->middleware("auth:sanctum");
Route::get("/file",function (Request $request){
    $uploadFileContlorler=new \App\Http\Controllers\UploadFileController();
    return $uploadFileContlorler->getFile($request);
})->middleware("auth:sanctum");
Route::delete("/file",function (Request $request){
    $uploadFileContlorler=new \App\Http\Controllers\UploadFileController();
    return $uploadFileContlorler->deleteFile($request);
})->middleware("auth:sanctum");
Route::get("/paper/party/{partyId}/statics",function ($partyId){
    $paperController=new paperController();
    return $paperController->paperStatics($partyId);
})->middleware('auth:sanctum');
