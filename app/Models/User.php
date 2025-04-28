<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Helpers\Helper;
use App\Enums\DeliveryStatusEnum;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements HasTenants, FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type',
        'performance_evaluation',
        'job_title',
        'company_id',
        'profile_photo_path',
    ];

    protected $appends = ['performance_evaluation'];

    public function getPerformanceEvaluationAttribute()
    {
        $deliveries = TaskDelivery::whereHas('task', function ($query) {
            $query->where('user_id', $this->id);
        });

        $deliveriesCount = $deliveries->count();
        $completedTasks = $this->team->completed_task_points;
        $totalPoints = $deliveriesCount * $completedTasks;

        $totalCompletedDeliveries = $deliveries->where('delivery_status', DeliveryStatusEnum::ON_TIME->value)->count();
        $totalDelayedDeliveries = $deliveries->where('delivery_status', DeliveryStatusEnum::DELAYED->value)->count();

        $percentage = $totalPoints > 0?(($totalCompletedDeliveries+$totalDelayedDeliveries) / $totalPoints) * 100:0;

        return __('views.EIMTITHAL') . ' ' .Helper::getRating($percentage).' '. round($percentage, 2) . '%';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_path;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

     public function getTenants(Panel $panel): Collection
    {
        return collect([$this->team]);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->team()->whereKey($tenant)->exists();
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_employee', 'employee_id', 'department_id')
            ->withPivot('department_role')
            ->withTimestamps();
    }

    public function assignees()
    {
        return $this->morphMany(Assignee::class, 'assigneeable');
    }
}
