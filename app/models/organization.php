<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class organization extends Model
{
    protected $fillable=["partyId","name","type","cityId","nameFa"];
    protected $table="organization";
    protected $primaryKey="partyId";
    public function party(){
        return $this->hasOne("App\models\party","partyId");
    }
    public function journal(){
        return $this->hasOne("App\models\journal","partyId");
    }
}
