<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class paperState extends Model
{
    protected $fillable=["statusId","paperPartyId","status","date"];
    protected $table="paperState";
    protected $primaryKey="statusId";
    public function paperState(){
        return $this->belongsTo("App\models\paper","paperId");
    }
    public function get($statusId){
        return paperState::find($statusId);

    }
    public function findPaperState($paperPartyId){
       return  paperState::where("paperPartyId",$paperPartyId)->get();
    }

}
