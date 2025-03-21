<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'description',
        'company_id',
        'performance_evaluation',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
