<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function team()
    {
        return $this->belongsTo(Company::class, 'team_id', 'id');
    }
}
