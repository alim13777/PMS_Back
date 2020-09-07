<?php

namespace App\Http\Controllers;

use App\models\paper;
use App\models\party;
use App\models\person;
use App\models\organization;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\models\paperState;
use Illuminate\Support\Facades\DB;
class paperController extends Controller
{
    public static function index()
    {
        return paper::all();
    }
    public static function findPartyPaper($partyId){
    $partyObject = new party();
    $partyObject->partyId=$partyId;
    $papers = $partyObject->paper()->get();
    $response = array();
    foreach ($papers as $paper) {
        $res=paperController::find($paper->paperId)->original;
        array_push($response,$res);
    }
    return response()->json($response);
    }
    public static function find($paperId){
        $papers = paper::find($paperId)->get();
        $response = array();
        foreach ($papers as $paper) {
            $authors = array();
            $publishers = array();
            $parties=$paper->party()->get();
            foreach ($parties as $party){
                if ($party->pivot->role == "author") {
                    $author = $party->person()->get();
                    array_push($authors, $author);
                }
                if($party->pivot->role=="publisher"){
                    $publisher=$party->organization()->get();
                    array_push($publishers,$publisher);
                }
            }
            array_push($response,array("paper"=>$paper,"authors"=>$authors,"publisher"=>$publishers));
        }
        return response()->json($response);
    }

    public function createPaper(Request $request){
        $paper=paper::create($request->paper);
        $publisher=array("partyId"=>$request->publisher["publisher"],"relation"=>"publisher");
        $paperParty=$request->authors;
        array_push($paperParty ,$publisher);

        $this->paperParty($paperParty,$paper);
        paperState::create(["paperId"=>$paper->paperId,"state"=>"readySubmit"]);
        return response()->json(["paperId"=>$paper->paperId],200);
    }
    public function paperParty($data,$paper){
        $paperId=$paper->paperId;
        $relArray=array();
        foreach ($data as $rel){
            $arr=array("paperId"=>$paperId);
            $rel+=$arr;
            array_push($relArray,$rel);
        }
        return $paper->party()->attach($relArray);
    }



}
