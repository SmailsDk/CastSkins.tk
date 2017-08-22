<?php

namespace App\Http\Controllers;

use App\bust_history;
use Carbon\Carbon;
use DB;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Support\Str;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class BustController extends Controller
{
    public function bust()
    {
        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = User::count();
        return view('pages.crash', compact('users', 'checkUserUsedYourCode'));
    }

    public function getUserSteamId64()
    {
        if (Auth::check()) {
            $user['id'] = Auth::user()->steamId64;
            return json_encode($user);
        } else {

        }
    }

    public function cashOut(Request $request)
    {
        if (Auth::check()) {
            $cashout = $request->get('cashout');
            $getLastBust = \App\bust_history::orderBy('id', 'desc')->first();
            $getUser = \App\User::where('steamId64', Auth::user()->steamId64)->first();
            $lastBustID = $getLastBust->id;
            $check = \App\bust_bets::where('userID64', Auth::user()->steamId64)->where('cashed_out', 0)->where('gameID', $lastBustID)->count();
            if ($check > 1) {
                return $this->responseErr('Already cashouted.');
            }
            if ($getLastBust->started != 1) {
                return $this->responseErr('Game not started.');
            }
            $get = \App\bust_bets::where('userID64', Auth::user()->steamId64)->where('cashed_out', 0)->where('gameID', $lastBustID)->first();
            $profit = (($get->amount * $cashout) - $get->amount);
            $update = \App\bust_bets::where('userID64', Auth::user()->steamId64)->where('cashed_out', 0)->where('gameID', $lastBustID)->update(['cashed_out' => $cashout, 'profit' => $profit]);
            $update = \App\User::where('steamId64', Auth::user()->steamId64)->update(['coins' => $getUser->coins + ($get->amount * $cashout)]);


            return $this->responseSuccess('Cashouted ' . $get->amount . ', Cashout on: ' . $cashout . ', Profit : ' . $profit . '.');
        } else {
            return $this->responseErr('You are not logged in!');
        }
    }

    public function createNewBust($token)
    {
        DB::connection()->disableQueryLog();
        if (\App\acces_tokens::where('token', $token)->count() != 1) {
            return dd('Bad Token');
        }
// Weź ostatniego busta i go skończ
        $getLastBust = \App\bust_history::orderBy('id', 'desc')->first();
        $lastBustID = $getLastBust->id;
        $endLastBust = \App\bust_history::where('id', $lastBustID)->update(['id' => $lastBustID]);

        $insert = \App\bust_history::create();

    }

    public function placeBust(Request $request)
    {
        $amount = $request->get('amount');
        if (Auth::check()) {
            $getLastBust = \App\bust_history::orderBy('id', 'desc')->first();
            if (\App\bust_bets::where('gameID', $getLastBust->id)->where('userID64', Auth::user()->steamId64)->count() > 0) {
                return $this->responseErr('You already placed bet.');
            }
            $lastBustID = $getLastBust->id;

            $getUserInfo = \App\User::find(Auth::user()->id);
            if ($getUserInfo->coins < $amount) {
                return $this->responseErr('You dont have that much diamonds');
            }
            if ($amount < 1 || !is_numeric($amount)) {
                return $this->responseErr('Nope.');
            }
            if (Auth::user()->global_banned > 0) {
                return $this->responseErr('Nope.');
            }



            if ($getLastBust->started == 1) {
                return $this->responseErr('Game is running, you need to wait for new one.');
            }

            \App\User::where('steamId64', Auth::user()->steamId64)->update(['coins' => ($getUserInfo->coins) - $amount]);
            $insert = \App\bust_bets::create([
                'cashed_out' => 0,
                'userID64' => Auth::user()->steamId64,
                'amount' => $amount,
                'gameID' => $lastBustID,
                'avatar' => Auth::user()->avatar,
                'url' => Auth::user()->url,
                'nick' => Auth::user()->nick
            ]);

            return $this->responseSuccess('Placed ' . $amount . ' on ' . Carbon::now() . ' .');
        } else {
            return $this->responseErr('You are not logged in!');
        }

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