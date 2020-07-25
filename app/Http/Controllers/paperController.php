<?php

namespace App\Http\Controllers;

use App\models\paper;
use App\models\party;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\models\paperState;
class paperController extends Controller
{
    public function index(){
        return paper::all();
    }
    public function findParty($partyId){
        $party=party::find($partyId)->paper()->get();
        return $party;
    }
    public function find($paperId){
        return paper::find($paperId);
    }

    public function createPaper(Request $request){
        $paper=paper::create($request->paper);
        $this->paperParty($request->relation,$paper);
        paperState::create(["paperId"=>$paper->paperId,"state"=>"submit"]);
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
