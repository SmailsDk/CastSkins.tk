<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\User;
use App\Chat;
use Auth;
use Illuminate\Support\Str;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class JackpotController extends Controller
{
    public function jackpot()
    {
        return redirect()->route('index');
//        $users = User::count();
//        $dateNow = date("Y-m-d");
//        $todayPlayed = \App\history::where('date', $dateNow)->count();
//
//        $lastWinner = \App\history::where('winnerSteamId64', '!=', '')->orderBy('id', 'desc')->limit(1)->get(['winnerSteamId64', 'userPutInPrice', 'potPrice']);
//        $lWID = $lastWinner[0]->winnerSteamId64;
//        $lastWinnerData = \App\User::where('steamId64', $lWID)->get(['nick', 'avatar']);
//
//        $biggestWinner = \App\history::where('winnerSteamId64', '!=', '')->orderBy('potPrice', 'desc')->limit(1)->get(['winnerSteamId64', 'userPutInPrice', 'potPrice']);
//        $bWID = $biggestWinner[0]->winnerSteamId64;
//        $biggestWinnerData = \App\User::where('steamId64', $bWID)->get(['nick', 'avatar']);
//        if (Auth::check()) {
//            if(Auth::user()->tradeURL == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
//
//            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
//        } else {
//            $checkUserUsedYourCode = 0;
//        }
//
//        return view('pages.jackpot', compact('users', 'todayPlayed', 'lastWinner', 'lastWinnerData', 'checkUserUsedYourCode'));
    }

    public function jackpothistory()
    {
        $retArr = [];
        $getJackpotHistory = \App\Jackpot_history::where('userPutInPrice', '!=', 0)->orderBy('id', 'desc')->limit(30)->get();
        $i = 0;
        foreach ($getJackpotHistory as $history) {
            $retArr[$i]['data'] = $history;
            $getUser = \App\User::where('steamId64', '=', $history->winnerSteamId64)->first();
            $retArr[$i]['user'] = $getUser;
            $i++;
        }
        $getJackpotHistory = $retArr;
        $users = \App\User::count();
        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        return view('pages.jackpotHistory', compact('getJackpotHistory', 'checkUserUsedYourCode', 'users'));
    }

    public function set(Request $request)
    {
        \App\SETTHIS::where('id', 1)->update(['color' => $request->get('color')]);
        return json_encode('git');
    }

    public function getUserItems($id)
    {
        $userItems = \App\pot::where('ownerSteamId64', $id)->orderBy('itemIcon', 'desc')->get();
        return json_encode($userItems);
    }

    public function getLastWinner()
    {
        $lastWinner = \App\history::where('userPutInPrice', '>', 0)->orderBy('id', 'desc')->limit(1)->get(['winnerSteamId64', 'userPutInPrice', 'potPrice']);
        $lWID = $lastWinner[0]->winnerSteamId64;
        $lastWinnerData = \App\User::where('steamId64', $lWID)->get(['nick', 'avatar']);
        $retArr = [];
        $retArr['lastWinner'] = $lastWinner;
        $retArr['lastWinnerData'] = $lastWinnerData;

        return json_encode($retArr);
    }

    protected
    function responseErr($message = '')
    {
        return json_encode(array('success' => 0, 'response' => $message));
    }

    protected
    function responseSuccess($message = '')
    {
        return json_encode(array('success' => 1, 'response' => $message));
    }
}
