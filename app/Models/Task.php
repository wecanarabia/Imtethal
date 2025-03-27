<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'priority',
        'delivery_status',
        'task_repetition',
        'task_type',
        'company_id',
        'note',
        'delivery_time',
        'grace_end_time',
        'task_evaluation',
        'delay_puneshment',
        'priority',
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function assignees()
    {
        return $this->hasMany(Assignee::class);
    }
}
