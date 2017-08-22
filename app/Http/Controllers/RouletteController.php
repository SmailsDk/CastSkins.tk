<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Support\Str;

class RouletteController extends Controller
{
    public function ruletka()
    {
        if (Auth::check()) {
//            if(Auth::user()->tradeURL == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
            $coins = Auth::user()->coins;
        } else {
            $coins = 0;
        }

        return view('pages.ruletka', compact('coins'));
    }

    public function history_ruletka()
    {
        $history = \App\rhistory::where('numberWon', '!=', '99')->orderBy('id', 'desc')->limit(500)->get(['colorWon']);

        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = \App\User::count();
        return view('pages.rouletteHistory', compact('history','checkUserUsedYourCode','users'));
    }

    public function sendcoins()
    {
        return view('pages.sendcoins');
    }

    public function currentwithdraw()
    {
        $withdraws = \App\withdraws::orderBy('id', 'desc')->limit(5)->get();

        return view('pages.currentwithdraw', compact('withdraws'));
    }

    public function mywithdraws()
    {
        if (Auth::check()) {
            $userID = Auth::user()->steamId64;
            $withdraws = \App\ofers::where('userID', $userID)->get();
        }
        return view('pages.mywithdraws', compact('withdraws'));
    }

    public function transferCoins(Request $request)
    {
        $toSteamID = $request->All()['tosteamID'];
        $much = $request->All()['much'];
        $info = [];
        if ($much < 1) {
            $info['zero'] = true;
        } else {
            if (Auth::check()) {
                $info['logged'] = true;
                $user_info = \App\User::where('steamId64', Auth::user()->steamId64)->first();
                $getUserBets = \App\placedBets::where('userID64', Auth::user()->steamId64)->count();
                if($getUserBets < 50) {
                    $info['notPlaced'] = true;
                    $info = json_encode($info);
                    return $info;
                }
                $user_coins = Auth::user()->coins;
                $userID = Auth::user()->steamId64;
                if ($user_coins < $much) {
                    $info['empty'] = true;
                } else {
                    $exist = \App\User::where('steamId64', $toSteamID)->count();
                    if ($exist > 0) {
                        $toInfo = \App\User::where('steamId64', $toSteamID)->first();
                        $toCoins = $toInfo->coins;
                        $toCoins = $toCoins + $much;
                        $myCoins = $user_coins - $much;
                        $updateTO = DB::update("UPDATE users SET coins='$toCoins' WHERE steamID64='$toSteamID'");
                        $updateME = DB::update("UPDATE users SET coins='$myCoins' WHERE steamID64='$userID'");
                        $info['rest'] = $myCoins;
                    } else {
                        $info['exist'] = false;
                    }
                }
            } else {
                $info['logged'] = false;
            }

        }
        $info = json_encode($info);
        return $info;
    }

    public function withdraw()
    {
        $withdraws = \App\withdraws::orderBy('id', 'desc')->limit(5)->get();
        $itemo = DB::select("SELECT name,date,icon,nick,url,avgPrice30Days FROM withdraws LEFT JOIN users ON users.steamId64=withdraws.userID LEFT JOIN items ON withdraws.name=items.marketName ORDER BY date DESC LIMIT 5");
        $withdra = DB::select("SELECT * FROM possible_withdraws LEFT JOIN items ON possible_withdraws.name=items.marketName ORDER BY avgPrice30Days DESC");
        return view('pages.withdraw', compact('itemo', 'withdra'));
    }

//    public function transfer(Request $request)
//    {
//        $steamID = $request->All()['steamID'];
//        $user_info = \App\User::where('steamId64', $steamID)->first();
//
//        $reflinkPoints = $user_info->reflink_points;
//        $coins = $user_info->coins;
//        $updateCoins = $reflinkPoints * 10;
//        $newCoins = $coins + $updateCoins;
//        if ($newCoins > $coins) {
//            $user_update = DB::update("UPDATE users SET reflink_points='0', coins='$newCoins' WHERE steamID64='$steamID'");
//            DB::insert('insert into rf (coins, userid) values (?, ?)', [$updateCoins, $steamID]);
//            $user = [];
//            $user['coins'] = 0;
//            $user = json_encode($user);
//            return $user;
//        } else {
//
//        }
//    }

    public function que(Request $request)
    {
        $assetID = $request->All()['assetID'];
        $lookInQue = \App\que::where('assetID', $assetID)->count();
        $que = [];
        if ($lookInQue > 0) {
            $que['inque'] = true;
        }
        $que['look'] = $lookInQue;
        $que = json_encode($que);
        return $que;
    }

    public function placeBet(Request $request)
    {

        $info = [];
        
        if (Auth::check()) {

            $lastID = DB::select("SELECT * FROM roulette_history ORDER BY id DESC LIMIT 1");
            $roundID = $lastID[0]->id;
            $steamID = Auth::user()->steamId64;
            $ammount = $request->All()['ammount'];
            $getCountPlaced = \App\placedBets::where('gameID', $roundID)->where('userID64', Auth::user()->steamId64)->count();
            if($getCountPlaced > 3) {
                $info['placedMuch'] = true;
                $info = json_encode($info);
                return $info;
            }
            if($ammount < 1 || !is_numeric($ammount)) {
                $info['baaad'] = true;
                $info = json_encode($info);
                return $info;
            }
            if ($ammount < 200) {
                $info['toLow'] = true;
                $info = json_encode($info);
                return $info;
            }
            if(Auth::user()->global_banned > 0) {
                $info['baaad'] = true;
                $info = json_encode($info);
                return $info;
            }
            $color = $request->All()['color'];
            if($color == 'black') {
                $color = 'purple';
            }

            $user_info = \App\User::where('steamId64', Auth::user()->steamId64)->first();
            $active_ofer = \App\ofers::where('userID', Auth::user()->steamId64)->count();
            $info['active_ofer'] = $active_ofer;

            $user_coins = $user_info->coins;
            if ($user_coins < $ammount) {
                $info['coins'] = false;
                $info['placed'] = false;

            } else {
                $user_coins = $user_coins - $ammount;
                $siteProfit = \App\profit::first();
                $siteProfitTotal = $siteProfit->siteProfit;
                $siteProfitTotal = intval($siteProfitTotal) + intval($ammount);
                $updateProfit = DB::update("UPDATE siteProfit SET siteProfit='$siteProfitTotal'");
                $user_update = \App\User::where('steamId64', Auth::user()->steamId64)->update(['coins' => $user_coins]);

                $info['coins'] = $user_coins;
                $active_bet = DB::select("SELECT * FROM roulette_history ORDER BY id DESC LIMIT 1");
                $game_id = $active_bet[0]->id;
                $placed = DB::insert('insert into placed_bets (color, userID64, ammount, gameID, avatar,url,nick,isStreamer,streamLink) values (?, ?, ?, ?,?,?,?,?,?)', [$color, $steamID, $ammount, $game_id, $user_info->avatar, $user_info->url, $user_info->nick,$user_info->isStreamer,$user_info->steamLink]);
                $info['placed'] = true;
                $info['ammount'] = $ammount;
                $info['color'] = $color;
                $info['count'] = $getCountPlaced;
            }
            $info['logged'] = true;
        } else {
            $info['coins'] = 0;
            $info['logged'] = false;
            $info['placed'] = false;
        }
        $info = json_encode($info);
        return $info;
    }


}