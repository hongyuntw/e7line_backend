<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Passwords\CanResetPassword;

class Member extends Authenticatable implements JWTSubject
{
    //
    protected $table = 'members';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    protected $hidden = [
        'password', 'remember_token',
    ];

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
}
