<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'contact_email',
        'is_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function isSuperAdmin(): bool
    {
        $email = strtolower(trim((string) $this->email));
        $superAdminEmail = strtolower(trim((string) config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il')));

        return $email !== '' && $email === $superAdminEmail;
    }
}
