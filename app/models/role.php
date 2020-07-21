<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $fillable=['name','slug','permissions'];
    protected $table='roles';
    protected $primaryKey='id';
    protected $casts = [
        'permissions' => 'array',
    ];
    public function party(){
        return  $this->belongsToMany('App\models\party','partyRoles');
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
