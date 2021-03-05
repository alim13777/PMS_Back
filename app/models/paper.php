<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class paper extends Model
{
    protected $fillable=["paperId","title","type","description","keywords"];
    protected $table="paper";
    protected $primaryKey="paperId";

    public function paperState(){
        return $this->hasMany("App\models\paperState","paperId");
    }
    public function party(){
        return $this->belongsToMany(party::class,"paper_party","paperId","partyId")->withPivot("role","partyId","paperId","startDate","endDate","localId");
    }
}
