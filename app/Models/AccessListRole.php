<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccessListRole extends Pivot
{
    protected $table = 'access_list_role';
    protected $fillable = ['role_id', 'access_list_id', 'updated_at'];
}
