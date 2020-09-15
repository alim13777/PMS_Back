<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    protected $fillable=['partyId','type','value'];
    protected $table="contact";
    protected $primaryKey="id";
    public function party(){
        return $this->hasOne("App\models\party","partyId","partyId");
    }
}
