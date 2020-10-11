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
    public function findPartyPaper($partyId){
    $partyObject = new party();
    $partyObject->partyId=$partyId;
    $papers = $partyObject->paper()->get();
    $response = array();
    foreach ($papers as $paper) {
        $res=$this->find($paper->paperId)->original;
        array_push($response,$res[0]);
    }
    return response()->json($response);
    }
    public function find($paperId){
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
                        $author=array("localId"=>$party->pivot->localId,"partyId"=>$party->partyId,"partyRole"=>$party->pivot->role,"firstName"=>$person[0]->firstName,"lastName"=>$person[0]->lastName,"email"=>$email);
                        array_push($authors, $author);
                    }
                    if($organization->count()>0) {
                        $publisher=array();
                        $paperStatus=$this->getPaperStatus($party->partyId,$paperId);
                        if($paperStatus!=null) {
                            $state = $paperStatus->count() > 0 ? $paperStatus->status : "";
                            $date = $paperStatus->count() > 0 ?Carbon::parse($paperStatus->date)->timestamp  : "";
                            $publisher = array("partyId" => $party->partyId, "name" => $organization[0]->name, "status" => $state, "date" => $date);
                        }
                        array_push($publishers, $publisher);
                    }
            }
            array_push($response,array("paper"=>$paper,"authors"=>$authors,"publisher"=>$publishers));
        }
        return response()->json($response);
    }

    public function createPaper($request){
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
        foreach ($author as $rel){
            $localId=$rel["localId"]?$rel["localId"]:"";
            $arr=array("localId"=>$localId,"role"=>$rel["role"],"partyId"=>$rel["partyId"],"paperId"=>$paperId,"startDate"=>now());
                array_push($relArray,$arr);
        }
        $arrPub=array("partyId"=>$publisher["partyId"],"paperId"=>$paperId,"role"=>"publisher","startDate"=>now());
        array_push($pubArray,$arrPub);
        $paper->party()->attach($relArray);
        $paper->party()->attach($pubArray);
        $status=$publisher["status"];
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
        if($id){
        $id=$id->pivot->id;
        paperState::create(["paperPartyId"=>$id,"status"=>$status,"date"=>now()]);
        }
        else{
            $paper=new paper();
            $paper->paperId=$paperId;
            $publisher=array("partyId"=>$data["partyId"],"status"=>$data["status"]);
            return $this->addPaperParty([],$publisher,$paper);
        }
    }
    public function getPaperStatus($partyId,$paperId)
    {

        $status = $this->getPaperParty($partyId, $paperId);
        $paperState=new paperState();
        if ($status) {
         $statusId = $status->pivot->id;
         $paperState = $paperState->get($statusId);
        }
        return $paperState;
    }

}
