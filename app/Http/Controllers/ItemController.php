<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function getItemPrice($itemName)
    {

        $getItemPrice = DB::select("SELECT * FROM items WHERE marketName='$itemName' LIMIT 1");
        return $getItemPrice;
    }



}
