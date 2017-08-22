<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class bust_bets extends Model
{

    protected $table = 'bust_bets';
    protected $fillable = ['cashed_out', 'userID64','amount','gameID','avatar','url','nick'];

}