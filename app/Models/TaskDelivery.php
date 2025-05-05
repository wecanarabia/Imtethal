<?php

namespace App\Models;

use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Database\Eloquent\Model;

class TaskDelivery extends Model
{
    protected $table = 'task_deliveries';

    protected $fillable = [
        'task_id',
        'delivery_status',
        'delivery_time',
        'grace_end_time',
        'task_evaluation',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function justifications()
    {
        return $this->hasMany(Justification::class);
    }

    public function punishments()
    {
        return $this->hasMany(Punishment::class);
    }
}
