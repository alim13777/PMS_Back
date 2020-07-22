<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class party extends Model
{
    protected $fillable=['partyId','type','owner'];
    protected $table="party";
    protected $primaryKey="partyId";

    public function education(){
        return $this->hasMany('App\models\education',"partyId");
    }
    public function person(){
        return $this->hasOne('App\models\person','partyId');
    }
    public function role(){
       return $this->belongsToMany(role::class,'party_role','partyId');
    }
    public function hasAccess(array $permissions)
    {
        foreach ($this->role()->get() as $role) {
            if ($role->hasAccess($permissions)) {
                return true;
            }
            return false;

        }
    }

    public function user(){
      return  $this->hasMany(User::class,'partyId');
    }
}
