<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class journal extends Model
{
    protected $fillable=['partyId','issn','impactFactor'];
    protected $table="journal";
    protected $primaryKey="partyId";
}
