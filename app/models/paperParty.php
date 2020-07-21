<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class paperParty extends Model
{
    protected $fillable=["partyId","paperId","relation"];
    protected $table="paperParty";
    public function party(){
        return $this->belongsTo("App\models\party","partyId");
    }
    public function paper(){
        return $this->belongsTo("App\models\paper","partyId");
    }
}
