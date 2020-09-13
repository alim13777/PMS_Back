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

Route::get("/paper/party",function(Request $request){
    $partyId=$request->user()->partyId;
    $paperController=new paperController();
    return $paperController->findPartyPaper($partyId);
});
Route::get("/paper/party/{partyId}",function($partyId){
    $paperController=new paperController();
    return $paperController->findPartyPaper($partyId);
});
Route::get("/paper",function(){
    $paperController=new paperController();
    return $paperController->index();
});
Route::get("/paper/{paperId}",function($paperId){
    $paperController=new paperController();
    return $paperController->find($paperId);
});
Route::post("/paper",function (Request $request){
    $paperController=new paperController();
    return $paperController->createPaper($request);
});
Route::put("/paper",function (Request $request){
    $paperController=new paperController();
    return $paperController->editPaper($request);
});
Route::post("/paper/party",function(Request $request){
    $paper=new paper();
    $paper->paperId= $request->paperId;
    $paperController=new paperController();
    $publisher=$request->publisher;
    $author=$request->author;
    return $paperController->addPaperParty($author,$publisher,$paper);
});
Route::delete("/paper/party",function(Request $request){
    $paper=new paper();
    $paper->paperId= $request->paperParty[0]["paperId"];
    $paperController=new paperController();
    return $paperController->deletePaperParty($request->paperParty,$paper);
});
Route::put("/paper/party",function(Request $request){
    $paper=new paper();
    $paper->paperId= $request->paperParty[0]["paperId"];
    $paperController=new paperController();
    return $paperController->updatePaperParty($request->paperParty,$paper);
});
Route::post("/paper/paperState",function (Request $request){
     $paperController=new paperController();
    $status=$request->status;
    return $paperController->addPaperStatus($request->publisher,$status);
});
Route::get("/party/person",function (Request $request){
    $partyController=new partyController();
    return $partyController->searchPerson($request);
});
Route::get("/party/journal",function(){
    $partyController=new partyController();
    return $partyController->indexJournal();
});
Route::put("/party/person",function (Request $request){
    $partyController=new partyController();
    return $partyController->updatePerson($request);
});
