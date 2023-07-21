<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleEnum;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function groupMember(): HasOne
    {
        return $this->hasOne(GroupMember::class, 'user_id');
    }

    public function group(): HasOne
    {
        return $this->hasOne(Group::class, 'owner_id');
    }

    public function voucher(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function scopeIsGroupAdmin(Builder $query): void
    {
        $query->whereHas('roles', fn ($q) => $q->where('name', UserRoleEnum::GROUP_ADMIN));
    }

    public function scopeIsUser(Builder $query): void
    {
        $query->whereHas('roles', fn ($q) => $q->where('name', UserRoleEnum::USER));
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}
