<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'user';

    protected $fillable = [
        'name','email','phone','username','password','roles','avatar',
        'created_by','updated_by','status','email_verified_at','verification_token'
    ];

    protected $hidden = ['password','verification_token'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
