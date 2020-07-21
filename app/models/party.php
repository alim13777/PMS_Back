<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class party extends Model
{
    protected $fillable=['partyId','type','owner'];
    protected $table="party";
    protected $primaryKey="partyId";
    public function paperParty(){
        return $this->hasMany("App\models\paperParty","partyId");
    }
    public function education(){
        return $this->hasMany("App\models\education","partyId");
    }
    public function person(){
        return $this->hasOne('App\models\person','partyId');
    }
}
