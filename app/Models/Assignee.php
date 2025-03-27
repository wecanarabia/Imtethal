<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignee extends Model
{
    protected $table = 'assignees';

    protected $fillable = [
        'task_id',
        'assigneeable_id',
        'assigneeable_type',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function assigneeable()
    {
        return $this->morphTo();
    }
}
