<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class rhistory extends Model
{
    protected $fillable = ['endTime'];
    protected $table = 'roulette_history';

}