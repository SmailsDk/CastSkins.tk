<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class acces_tokens extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acces_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = ['nick', 'avatar', 'steamId64','reflink','url'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

}
