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
use Ramsey\Collection\Collection;


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
        $papers = paper::where("paperId",$paperId)->get();
        $response = array();
        foreach ($papers as $paper) {
            $authors = array();
            $publishers = array();
            $parties=$paper->party()->get();
            foreach ($parties as $party){
                    $person = $party->person()->get();
                    $organization=$party->organization()->get();
                    $user=$party->user()->get();
                    if($user->count()>0){$email=$user[0]->email;}
                    else{$email="";}
                    if($person->count()>0){
                        $author=array("partyId"=>$party->partyId,"partyRole"=>$party->pivot->role,"firstName"=>$person[0]->firstName,"lastName"=>$person[0]->lastName,"email"=>$email);
                        array_push($authors, $author);
                    }
                    if($organization->count()>0) {
                        $status=paperState::all()->where("paperId","=",$paperId)->where("partyId","=",$party->partyId);
                        $state=$status->count()>0?$status[0]->status:"";
                        $date=$status->count()>0?$status[0]->date:"";
                        $publisher = array("partyId" => $party->partyId,"name"=>$organization[0]->name,"status"=>$state,"date"=>$date);
                        array_push($publishers, $publisher);
                    }
            }
            array_push($response,array("paper"=>$paper,"authors"=>$authors,"publisher"=>$publishers));
        }
        return response()->json($response);
    }

    public  function createPaper(Request $request){
        $paper=paper::create($request->paper);
        $author=$request->authors;
        $publisher=$request->publisher;
        $this->addPaperParty($author,$publisher,$paper);
        return response()->json(["paperId"=>$paper->paperId],200);
    }
    public function editPaper(Request $request){
        $paperId=$request->paper["paperId"];
        return paper::where("paperId",$paperId)->update($request->paper);
    }
    public function addPaperParty($author,$publisher,$paper){
        $paperId=$paper->paperId;
        $relArray=array();
        $pubArray=array();
        foreach ($author as $rel){
            $arr=array("role"=>$rel["role"],"partyId"=>$rel["partyId"],"paperId"=>$paperId,"startDate"=>now());
                array_push($relArray,$arr);
        }
        $arrPub=array("partyId"=>$publisher["partyId"],"paperId"=>$paperId,"role"=>"publisher","startDate"=>now());
        array_push($pubArray,$arrPub);
        $paper->party()->attach($relArray);
        $paper->party()->attach($pubArray);
        $status=$publisher["status"];
        $this->addpaperStaus($arrPub,$status);

    }
    public function deletePaperParty($data,$paper){
        $paperId=$paper->paperId;
        $relArray=array();
        foreach ($data as $rel){
            $arr=array("partyId"=>$rel["partyId"],"paperId"=>$paperId);
            array_push($relArray,$rel);
            return $paper->party()->updateExistingPivot($arr, array('endDate' => now()), false);
        }

    }
    public function updatePaperParty($data,$paper){
        $paperId=$paper->paperId;
        $relArray=array();
        foreach ($data as $rel){
            $arr=array("partyId"=>$rel["partyId"],"paperId"=>$paperId);
            array_push($relArray,$rel);
            return $paper->party()->updateExistingPivot($arr, array('role' => $rel["role"]), false);
        }
    }
    public function addPaperStatus($data,$status){
        $party=new party();
        $party->partyId=$data["partyId"];
        $paperId=$data["paperId"];
        $id=$party->paper()->find($paperId)->pivot->id;
        paperState::create(["statusId"=>$id,"status"=>$status,"date"=>now()]);
    }

}
