<?php

namespace App\models;

use App\models\party;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $fillable=['roleId','name','slug','permissions'];
    protected $table='role';
    protected $primaryKey='roleId';
    protected $casts = [
        'permissions' => 'array',
    ];
    public function party(){
        return $this->belongsToMany(party::class,'party_role','roleId',"partyId");
    }
    public function hasAccess(array $permissions){
        foreach ($permissions as $permission){
            if($this->hasPermission($permission)){return true;}
        }
        return false;
    }
    public function hasPermission(string $permission){
        return $this->permissions[$permission] ?? false;
    }
}
