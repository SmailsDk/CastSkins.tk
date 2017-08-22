<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class que extends Model
{
    protected $fillable = ['user_id', 'assetID', 'date', 'itemname', 'status', 'valued'];
    protected $table = 'withdraw_que';

}