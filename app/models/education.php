<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class education extends Model
{
    protected $fillable=["partyId","school","degreeId","startDate","endDate"];
    protected $table="education";
    public function party(){
        return $this->belongsTo("App\models\party","partyId");
    }
}
