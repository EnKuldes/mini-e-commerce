<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessList extends Model
{
    use HasFactory;
    // fillable 
    protected $fillable = [
        'type', 'parent', 'order', 'link', 'child', 'active', 'icon', 'name'
    ];

    // relationship
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'access_list_roles')->using(AccessListRole::class);
    }
}
