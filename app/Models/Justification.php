<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    use HasUuids;

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
