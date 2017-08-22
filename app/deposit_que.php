<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class deposit_que extends Model
{
    protected $fillable = ['user_id', 'assetID', 'date', 'itemname', 'status', 'valued'];
    protected $table = 'deposit_que';

}