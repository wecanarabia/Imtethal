<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logExcept([]);
    }
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'task_repetition',
        'task_type',
        'company_id',
        'note',
        'delay_puneshment',
        'priority',
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
        return $this->hasMany(Assignee::class, 'task_id');
    }

    public function deliveries()
    {
        return $this->hasMany(TaskDelivery::class, 'task_id', 'id');
    }

    public function deliveryStatus(string $status)
    {
        return $this->hasMany(TaskDelivery::class, 'task_id', 'id')
            ->when($status === 'incomplete', function ($query) {
                $query->whereIn('status', [
                    TaskStatusEnum::PENDING->value,
                    TaskStatusEnum::IN_PROGRESS->value,
                    TaskStatusEnum::OVERDUE->value,
                ]);
            })
            ->when($status === 'complete', function ($query) {
                $query->where('status', TaskStatusEnum::COMPLETED->value);
            })
            ->when($status === 'rejected/canceled', function ($query) {
                $query->whereIn('status', [
                    TaskStatusEnum::REJECTED->value,
                    TaskStatusEnum::CANCELED->value,
                ]);
            });
    }
    public function incompletedDeliveries()
    {
        return $this->deliveryStatus('incomplete');
    }

    public function completedDeliveries()
    {
        return $this->deliveryStatus('complete');
    }

    public function refusedDeliveries()
    {
        return $this->deliveryStatus('rejected/canceled');
    }


}
