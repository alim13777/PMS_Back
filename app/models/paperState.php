<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class paperState extends Model
{
    protected $fillable=["id","paperId","state"];
    protected $table="paperState";
    public function paperState(){
        return $this->belongsTo("App\models\paper","paperId");
    }
}
