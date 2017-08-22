<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ColorController extends Controller
{


    public function getWinningColor($token, $roundID)
    {
        DB::connection()->disableQueryLog();
        if (\App\acces_tokens::where('token', $token)->count() != 1) {
            return dd('Bad Token');
        }
        $getSet = \App\SETTHIS::first();


        $colors = [];

        $i = 0;
        for ($i = 0; $i < 51; $i++) {
            $colors[$i]['number'] = $i;
            if ($i % 2 == 0) {
                $colors[$i]['color'] = 'red';
            } else {
                if ($i == 1 || $i == 49) {
                    $colors[$i]['color'] = 'green';
                } elseif ($i == 23) {
                    $colors[$i]['color'] = 'gold';
                } else {
                    $colors[$i]['color'] = 'purple';
                }
            }

        }
        if ($getSet->color != 'lol') {
            $colors = [];
        }
        if ($getSet->color == 'lol') {
            $generatedNumber = $colors[array_rand($colors)];
        } elseif ($getSet->color == 'purple') {
            $colors[25]['number'] = 25;
            $colors[25]['color'] = 'purple';
            $generatedNumber = $colors[array_rand($colors)];
        } elseif ($getSet->color == 'red') {
            $colors[0]['number'] = 0;
            $colors[0]['color'] = 'red';
            $generatedNumber = $colors[array_rand($colors)];
        } elseif ($getSet->color == 'green') {
            $colors[1]['number'] = 1;
            $colors[1]['color'] = 'green';
            $generatedNumber = $colors[array_rand($colors)];
        } elseif ($getSet->color == 'gold') {
            $colors[23]['number'] = 23;
            $colors[23]['color'] = 'gold';
            $generatedNumber = $colors[array_rand($colors)];
        }
        if ($generatedNumber['color'] == 'purple' || $generatedNumber['color'] == 'red') {
            $multiplyer = 2;
        } else if ($generatedNumber['color'] == 'green') {
            $multiplyer = 8;
        } else if ($generatedNumber['color'] == 'gold') {
            $multiplyer = 24;
        }
        $getWinners = \App\placedBets::where('color', $generatedNumber['color'])
            ->where('gameID', $roundID)
            ->groupBy('userID64')
            ->selectRaw('userID64, sum(ammount) as placed')
            ->get(['placed', 'userID64']);
        foreach ($getWinners as $winner) {
            $winneros = \App\User::where('steamId64', $winner->userID64)->first();
            $userNewCoins = intval($winneros->coins) + intval($winner->placed * $multiplyer);
            \App\User::where('steamId64', $winner->userID64)->update(['coins' => $userNewCoins]);
        }

        \App\rhistory::where('id', $roundID)->update(['colorWon' => $generatedNumber['color'], 'numberWon' => $generatedNumber['number']]);
        \App\rhistory::create(['endTime' => 0]);
        $data = array(
            'number' => $generatedNumber['color']
        );
        return $data;
    }

}
