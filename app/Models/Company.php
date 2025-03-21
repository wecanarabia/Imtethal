<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'notification_type',
        'on_time_schedule_points',
        'grace_period_points',
        'delay_delivery_points',
    ];

    public function employees()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(\App\Models\Department::class);
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }


    public static function boot()
    {
        parent::boot();
        static::saving(function ($company) {
            $company->slug = Str::slug($company->name);
        });
    }
}






























use Illuminate\Database\Eloquent\Relations\HasMany;
