<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\party;
class person extends Model
{
    protected $fillable=['partyId','firstName','lastName','suffix','gender','birthDate'];
    public function party(){
        return $this->hasOne('App\models\party','partyId');
      }
      protected $table="person";
      protected $primaryKey="partyId";
}
