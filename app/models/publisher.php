<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class publisher extends Model
{
    protected $fillable = ['partyId'];
    protected $table = "publisher";
    protected $primaryKey = "partyId";

    public function organization()
    {
        return $this->hasOne("App/models/organization", 'partyId');
}
}
