<?php
namespace App\Http\Controllers;
use App\models\paper;
use App\models\party;
use App\models\person;
use App\models\organization;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\models\paperState;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use Ramsey\Collection\Collection;
class paperController extends Controller
{

    public function index()
    {
        return paper::all();
    }
    public function findPartyPaper($partyId,$currentUser){
    $partyObject = new party();
    $partyObject->partyId=$partyId;
    $papers = $partyObject->paper()->get();
    $response = array();

    foreach ($papers as $paper) {
        $res=$this->find($paper->paperId,$currentUser)->original;
        array_push($response,$res[0]);
    }
    return response()->json($response);
    }
    public function find($paperId,$currentUser){
        $papers = paper::where("paperId",$paperId)->get();
        $response = array();
        foreach ($papers as $paper) {
            $authors = array();
            $publishers = array();
            $parties=$paper->party()->get();
            foreach ($parties as $partyLoop){
                    $party=new party();
                    $party->partyId=$partyLoop->partyId;
                    $person = $party->person()->get();
                    $organization=$party->organization()->get();
                    $user=$party->user()->get();
                    $email="";
                    if($user->count()>0){$email=$user[0]->email;}
                    if($partyLoop->pivot->partyId==$currentUser->partyId)$paper->localId=$partyLoop->pivot->localId;
                    if($person->count()>0){
                        $author=array("localId"=>$partyLoop->pivot->localId,"partyId"=>$party->partyId,"partyRole"=>$partyLoop->pivot->role,"firstName"=>$person[0]->firstName,"lastName"=>$person[0]->lastName,"email"=>$email);
                        array_push($authors, $author);
                    }
                    if($organization->count()>0) {
                        $paperStatus=$this->getPaperStatus($organization[0]["partyId"],$paperId);
                        $status=last($paperStatus);
                        if(!$status)continue;
                        $status=$status[0];
                        $state = $status["status"];
                        $date = Carbon::parse($status["date"])->getPreciseTimestamp(3);;
                        $publisher = array("partyId" => $party->partyId, "name" => $organization[0]->name, "status" => $state, "date" => $date);
                        array_push($publishers, $publisher);
                    }
            }
                    array_push($response,array("paper"=>$paper,"authors"=>$authors,"publisher"=>$publishers));
        }
        return response()->json($response);
    }
    public function createPaper($request){

        $paperRequest=$request->paper;
        $paperArr=array("title"=>$paperRequest["title"],"type"=>$paperRequest["type"],"description"=>$paperRequest["description"],"keywords"=>$paperRequest["keywords"]);
        $paper=paper::create($paperArr);
        $paper->localId=$request->paper["localId"]?$request->paper["localId"]:"";
        $author=$request->authors;
        $publisher=$request->publisher;
        $this->addPaperParty($author,$publisher,$paper);
        return response()->json(["paperId"=>$paper->paperId],200);

    }
    public function editPaper(Request $request){
        $paperId=$request->paper["paperId"];
        $paperRequest=$request->paper;
        $localId=$request->paper["localId"];
        $partyId=$request->user()->partyId;
        $paper=new paper();
        $paper->paperId=$paperId;
        $arr=array("partyId"=>$partyId,"paperId"=>$paperId);
        $paper->party()->updateExistingPivot($arr,array("localId"=>$localId), false);
        $paperArr=array("title"=>$paperRequest["title"],"type"=>$paperRequest["type"],"description"=>$paperRequest["description"],"keywords"=>$paperRequest["keywords"]);
        return paper::where("paperId",$paperId)->update($paperArr);
    }
    public function getPaperParty($partyId,$paperId){
        $party=new party();
        $party->partyId=$partyId;
        $paperId=$paperId;
        return $party->paper()->find($paperId);
    }
    public function addPaperParty($author,$publisher,$paper){
        $paperId=$paper->paperId;
        $relArray=array();
        $pubArray=array();
        $status=$publisher["status"];

        foreach ($author as $rel){
            $localId=$paper["localId"]?$paper["localId"]:"";
            $arr=array("localId"=>$localId,"role"=>$rel["role"],"partyId"=>$rel["partyId"],"paperId"=>$paperId,"startDate"=>now());
            array_push($relArray,$arr);
        }
        $arrPub=array("partyId"=>$publisher["partyId"],"paperId"=>$paperId,"role"=>"publisher","startDate"=>substr($publisher["date"],0,10));
        array_push($pubArray,$arrPub);
        $paper->party()->attach($relArray);
        $paper->party()->attach($pubArray);
        return $this->addPaperStatus($arrPub,$status);
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
        $id=$this->getPaperParty($party->partyId,$paperId);
        if(!$id){
            $paper=new paper();
            $paper->paperId=$paperId;
            $publisher=array("partyId"=>$data["partyId"],"paperId"=>$paperId,"role"=>$data["role"],"localId"=>"","startDate"=>now());
            $paper->party()->attach($publisher);
            $ids=$this->getPaperParty($party->partyId,$paperId);
            paperState::create(["paperPartyId"=>$ids,"status"=>$status,"date"=>$data["startDate"]]);
        }
        if($id){
        $id=$id->pivot->id;
        paperState::create(["paperPartyId"=>$id,"status"=>$status,"date"=>$data["startDate"]]);
        }

    }
    public function getPaperStatus($partyId,$paperId)
    {
        $status = $this->getPaperParty($partyId, $paperId);
        $paperState=new paperState();
        if ($status) {
             $paperPartyId = $status->pivot->id;
             $paperState = $paperState->findPaperState($paperPartyId);
        }
        return $paperState;
    }
    public function paperStatics($partyId){
        $papers=$this->findPartyPaper($partyId);
        $papers=json_decode($papers->content(), true);
        $statusList=array();
        foreach ($papers as $paper){
            $paperId=$paper["paper"]["paperId"];
            foreach ($paper["publisher"] as $publisher){
            $publisherId=$publisher["partyId"];
            $status=$this->getPaperStatus($publisherId,$paperId);
            array_push($statusList,$status);
            }
        }
        $researching=0;$writing=0;$underEdit=0;$underReview=0;$readySubmit=0;
        $submitting=0;$submitted=0;$underReview=0;$underEdit=0;$rejected=0;
        $accepted=0;$canceled=0;
        foreach ($statusList as $status){
            foreach ($status as $oneStatus){
            $state=$oneStatus["status"];
            switch ($state){
                case "researching":
                    $researching++;
                    break;
                case "writing":
                    $writing++;
                    break;
                case "readySubmit":
                    $readySubmit++;
                    break;
                case "submitting":
                    $submitting++;
                    break;
                case "submitted":
                    $submitted++;
                    break;
                case "underReview":
                    $underReview++;
                    break;
                case "underEdit":
                    $underEdit++;
                    break;
                case "rejected":
                    $rejected++;
                    break;
                case "accepted":
                    $accepted++;
                    break;
                case "canceled":
                    $canceled++;
                    break;
                default:
                    break;
            }
        }
        }
        $static=array(["researching"=>$researching,"writing"=>$writing,"underEdit"=>$underEdit
        ,"underReview"=>$underReview,"readySubmit"=>$readySubmit,"submitting"=>$submitting,"submitted"=>$submitted,
        "rejected"=>$rejected,"accepted"=>$accepted,"canceled"=>$canceled]);
        return $static ;
    }

}
