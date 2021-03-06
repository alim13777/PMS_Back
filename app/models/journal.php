<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class journal extends Model
{
    protected $fillable=['partyId','issn','impactFactor'];
    protected $table="journal";
    protected $primaryKey="partyId";
    public function organization(){
        return $this->hasOne("App\models\organization","partyId");
    }
}
