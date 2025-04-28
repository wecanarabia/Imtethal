<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function employees(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'department_employee', 'department_id','employee_id');
    }

    public function assignees()
    {
        return $this->morphMany(Assignee::class, 'assigneeable');
    }
}
