<?php

namespace App\Http\Controllers;

use App\models\paper;
use App\models\party;
use App\models\person;
use App\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\models\paperState;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Null_;

class partyController extends Controller
{
    public function createParty($data,$type){
        $partyId=party::create(["type"=>"person","owner"=>""])->partyId;
        if($type=="person"){
            $this->createPerson($data,$partyId);
        }
        return $partyId;
    }

    public function createPerson($data,$partyId){
        person::create(["partyId"=>$partyId,"firstName"=>$data["firstName"],"lastName"=>$data["lastName"],"suffix"=>"","degreeId"=>"","gender"=>"","birthDate"=>"2000-01-01"]);
    }
    public function createUser(array $data,$partyId){
       return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account'=>$data["account"],
            'partyId'=>$partyId,
            'language'=>$data["language"],
            'active'=>true,
        ]);
    }



}
