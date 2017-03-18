<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    public static function getRoles()
    {
        $roles = [];
        foreach (Role::orderBy('id', 'asc')->get() as $role) 
        {
            $roles[$role->id] = $role->label;
        }
        return $roles;
    }


}
