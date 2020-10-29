<?php

namespace App\Http\Controllers;

use App\models\journal;
use App\models\organization;
use App\models\paper;
use App\models\party;
use App\models\person;
use App\User;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\models\paperState;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\DB;

class partyController extends Controller
{

    public function createParty($data,$identity){
        $partyId=party::create(["identity"=>"person","owner"=>""])->partyId;
        if($identity=="person"){
            $this->createPerson($data,$partyId);
        }
        return $partyId;
    }
    public function createPerson($data,$partyId){
        person::create(["partyId"=>$partyId,"firstName"=>$data["firstName"],"lastName"=>$data["lastName"],"prefix"=>"","degree"=>"","gender"=>"","birthDate"=>"2000-01-01"]);
    }
    public function createUser(array $data,$partyId){
       return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account'=>$data["account"],
            'partyId'=>$partyId,
            'locale'=>$data["language"],
            'active'=>true,
        ]);
    }
    public function searchPerson($request){
        $person=DB::table('person')
            ->join('users','person.partyId','=','users.partyId')
            ->select('person.*',"users.email")
            ->where('person.firstName','like',"%".$request['text']."%")
            ->ORwhere('person.lastName','like',"%".$request["text"]."%")
            ->ORwhere('users.email','like',"%".$request["text"]."%")
            ->get();
        foreach ($person as $per){
            $per->birthDate=Carbon::parse( $per->birthDate)->timestamp;
            $per->created_at=Carbon::parse( $per->created_at)->timestamp;
            $per->updated_at=Carbon::parse( $per->updated_at)->timestamp;
        }
        return response()->json($person);
    }
    public function indexJournal(){
        return DB::table('organization')
            ->join('journal','organization.partyId','=','journal.partyId')
            ->select('organization.partyId',"organization.name")
            ->get();
    }
    public function updatePerson($data){
        $birthDate=Carbon::parse( $data->person["birthDate"])->toDateTime();
        $partyId=person::where("partyId",$data->person["partyId"])->update(array("firstName"=>$data->person["firstName"],"lastName"=>$data->person["lastName"],"prefix"=>$data->person["prefix"],"gender"=>$data->person["gender"],"degree"=>$data->person["degree"],"birthDate"=>$birthDate));
        return response()->json(array("partyId"=>"$partyId"));
    }
    public function getPerson($partyId){
        $person = person::where("partyId",$partyId)->get();
        return response()->json($person);
    }
}
