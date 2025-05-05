<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Justification extends Model
{
    use HasUuids;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logExcept([]);
    }
    protected static $logOnlyDirty = true;
    protected $table = 'justifications';
    public $incrementing = false;
    protected $fillable = [
        'task_delivery_id',
        'assignee_id',
        'note',
        'reply',
    ];

    public function taskDelivery()
    {
        return $this->belongsTo(TaskDelivery::class);
    }

    public function assignee()
    {
        return $this->belongsTo(Assignee::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function team()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
