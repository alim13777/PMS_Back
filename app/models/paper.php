<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class paper extends Model
{
    protected $fillable=["paperId","title","type","comment","keyWords","topic","state","submitDate","acceptDate"];
    protected $table="paper";
    protected $primaryKey="paperId";
    public function paperParty(){
        return $this->hasMany("App\models\paperParty","paperId");
    }
    public function paperState(){
        return $this->hasMany("App\models\paperState","paperId");
    }
}
