<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CONTRIBUTOR = 'contributor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function roles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_CONTRIBUTOR,
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isContributor(): bool
    {
        return $this->role === self::ROLE_CONTRIBUTOR;
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
        ], true);
    }

    public function profilePicture(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'profile_picture_id');
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaLibrary::class, 'user_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id');
    }
}