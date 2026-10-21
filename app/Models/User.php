<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

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

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Appointment::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Case-insensitive, so "D.maria@…" typed on a phone finds "d.maria@…". */
    public static function findByEmail(string $email): ?self
    {
        return static::whereRaw('lower(email) = ?', [Str::lower(trim($email))])->first();
    }

    /**
     * One-time studio access link: logs her in and lets her choose a new
     * password. Expires after auth.passwords.users.expire minutes; asking
     * for a new one invalidates the previous link.
     */
    public function createStudioAccessLink(): string
    {
        $token = Password::broker()->getRepository()->create($this);

        return route('admin.password.reset', ['token' => $token, 'email' => $this->email]);
    }
}
