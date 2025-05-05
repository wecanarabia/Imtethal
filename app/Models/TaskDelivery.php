<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TaskDelivery extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logExcept([]);
    }
    protected static $logOnlyDirty = true;
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
