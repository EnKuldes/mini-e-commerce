<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'role', 'desc', 'active'
    ];

    // relationship
    public function access_lists()
    {
        return $this->belongsToMany(AccessList::class, 'access_list_roles')->using(AccessListRole::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users')->using(RoleUser::class);
    }
}
