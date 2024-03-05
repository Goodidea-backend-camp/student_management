<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
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
    // append custom attributes for serialization
    protected $appends = ['passed_days', 'progress'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function registration_token(): HasOne
    {
        return $this->hasOne(RegistrationToken::class);
    }

    protected function passedDays(): Attribute
    {
        // passed_days is null if start_date is not set
        if (is_null($this->start_date)) {
            return new Attribute(
                get: fn() => null,
            );
        }
        return new Attribute(
            get: fn() => (new DateTime())->diff(new DateTime($this->start_date))->format('%a'),
        );
    }

    protected function progress(): Attribute
    {
        // if the student had left (leave_date is set), progress is 100%
        if (!is_null($this->leave_date)) {
            return new Attribute(
                get: fn() => 100,
            );
        }

        // if start_date is not set, progress is null
        if (is_null($this->start_date)) {
            return new Attribute(
                get: fn() => null,
            );
        }

        // otherwise, compute the progress
        $totalDaysInSixMonth = (int)(new DateTime($this->proposed_leave_date))->diff(new DateTime($this->start_date))->format('%a');
        if (is_null($this->leave_date)) {
            return new Attribute(
                get: fn() => round(100 * $this->passed_days / $totalDaysInSixMonth),
            );
        }
    }
}
