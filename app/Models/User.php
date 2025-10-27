<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    /**
     * untuk mendapatkan identifier yang akan disimpan di dalam claim "sub" JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * untuk mendapatkan custom claim tambahan untuk JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function hobbies()
    {
        return $this->hasMany(Hobby::class);
    }
}
