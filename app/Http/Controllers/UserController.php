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

class UserController extends Controller
{

    public function checkLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return json_encode($user);
        }
    }

    public function transfers()
    {
        $retArr = [];
        $getTransferHistory = \App\transferHistory::where('from_id', Auth::user()->steamId64)->orWhere('to_id', Auth::user()->steamId64)->orderBy('id', 'desc')->get();

        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = \App\User::count();
        return view('pages.transfers', compact('getTransferHistory', 'checkUserUsedYourCode', 'users'));
    }

    public function transferCoins(Request $request)
    {
        $toSteamID = $request->All()['tosteamID'];
        $much = $request->All()['much'];
        $info = [];
        if (!Auth::check()) {
            return $this->responseErr('You are not logged in!');
        }
        if (Auth::user()->isPartner > 0) {
            return $this->responseErr('You are not able to withdraw!');
        }
        if ($much < 1) {
            return $this->responseErr('You want to transfer nothing?');
        }
        if ($toSteamID == '') {
            return $this->responseErr('This user is not exist.');
        }
        if ($toSteamID == Auth::user()->steamId64) {
            return $this->responseErr('You cannot send coins to you.');
        }
        $user_info = \App\User::where('steamId64', Auth::user()->steamId64)->first();
        $getUserBets = \App\placedBets::where('userID64', Auth::user()->steamId64)->count();
        if ($getUserBets < 50) {
            return $this->responseErr('You need to place minimum 50 bets on roulette to send coins.');
        }
        $user_coins = Auth::user()->coins;
        $userID = Auth::user()->steamId64;
        if ($user_coins < $much) {
            return $this->responseErr('Not enouth coins.');
        }
        $exist = \App\User::where('steamId64', $toSteamID)->count();
        if ($exist < 1) {
            return $this->responseErr('This user is not exist.');
        }
        $exist = \App\User::where('steamId64', $toSteamID)->first();

        $toInfo = \App\User::where('steamId64', $toSteamID)->first();
        $toCoins = $toInfo->coins;
        $toCoins = $toCoins + $much;
        $myCoins = $user_coins - $much;
        $updateTO = DB::update("UPDATE users SET coins='$toCoins' WHERE steamID64='$toSteamID'");
        $updateME = DB::update("UPDATE users SET coins='$myCoins' WHERE steamID64='$userID'");

        $insert = \App\transferHistory::create([
            'from' => Auth::user()->nick,
            'to' => $exist->nick,
            'from_id' => Auth::user()->steamId64,
            'to_id' => $exist->steamId64,
            'coins' => $much,
        ]);
        return $this->responseSuccess('You transfered ' . $much . ' to your friend ' . $exist->nick . '.');
    }

    public function jackpotUserHistory()
    {
        $retArr = [];
        $getJackpotHistory = \App\Jackpot_history::where('winnerSteamId64', Auth::user()->steamId64)->where('userPutInPrice', '!=', 0)->orderBy('id', 'desc')->get();
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
        return view('pages.jackpotUserHistory', compact('getJackpotHistory', 'checkUserUsedYourCode', 'users'));
    }

    public function rouletteUserHistory()
    {
        $getWinner = \App\placedBets::leftJoin('roulette_history', function ($join) {
            $join->on('placed_bets.gameID', '=', 'roulette_history.id');
        })->where('placed_bets.userID64', Auth::user()->steamId64)->orderBy('roulette_history.id', 'desc')->paginate(20);
        $getWithdraw = \App\withdraw_que::where('user_id', Auth::user()->steamId64)->sum('valued');
        $getDeposited = \App\wholeItems::where('ownerSteamId64', Auth::user()->steamId64)->where('roulette', 1)->sum('itemPrice');

        $rouletteLos = 0;
        $rouletteWon = 0;
        foreach ($getWinner as $round) {
            if ($round->colorWon == 'green') {
                $rouletteWon += $round->ammount * 8;
            } else if ($round->colorWon == 'gold') {
                $rouletteWon += $round->ammount * 24;
            } else if ($round->colorWon == 'black' || $round->colorWon == 'purple' || $round->colorWon == 'gold' ) {
                $rouletteWon += $round->ammount * 2;
            } else {
                $rouletteLos += $round->ammount;
            }
        }
        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = \App\User::count();
        $rouletteBilance = $rouletteWon - $rouletteLos - $getWithdraw + $getDeposited;
        return view('pages.RouletteUserHistory', compact('getWinner', 'getDeposited', 'getWithdraw', 'rouletteLos', 'rouletteWon', 'rouletteBilance', 'checkUserUsedYourCode', 'users'));
    }

    public function coinflipUserHistory()
    {
        $getWinner = \App\coinflip_rooms::where('left', Auth::user()->steamId64)->orWhere('right', Auth::user()->steamId64)->orderBy('coinflip_rooms.id', 'desc')->paginate(20);

        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = \App\User::count();
        return view('pages.CoinflipUserHistory', compact('getWinner', 'rouletteLos', 'rouletteWon', 'checkUserUsedYourCode', 'users'));
    }

    public function getUserInfo($steamID)
    {
        $user_info = \App\User::where('steamId64', $steamID)->first();
        return $user_info;
    }

    public function settings()
    {

        if (Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = \App\User::count();
        return view('pages.settings', compact('checkUserUsedYourCode', 'users'));
    }

    public function saveTradeToken(Request $request)
    {
        $retArr = [];
        $retArr['error'] = 'null';


        $tradeUrl = $request->All()['tradeURL'];

        if (is_null($tradeUrl) || strlen($tradeUrl) === 0) {
            $retArr['error'] = 'No tradelink?';
            return $retArr;
        }
        if (!filter_var($tradeUrl, FILTER_VALIDATE_URL)) {
            $retArr['error'] = 'Bad tradelink';
            return $retArr;
        }
        $query = parse_url($tradeUrl, PHP_URL_QUERY);
        parse_str($query, $queryArr);
        $tradeToken = isset($queryArr['token']) ? $queryArr['token'] : null;
        if (is_null($tradeToken) || strlen($tradeToken) === 0) {
            $retArr['error'] = 'Bad tradelink';
            return $retArr;
        }

        $steamID = Auth::user()->steamId64;

        DB::update('update users set tradeToken = ?, tradeURL = ? where steamId64 = ?', [$tradeToken, $tradeUrl, $steamID]);
        return $retArr;
    }

    public function saveEmail(Request $request)
    {
        $retArr = [];
        $retArr['error'] = 'null';


        $email = $request->All()['email'];

        if (is_null($email) || strlen($email) === 0) {
            $retArr['error'] = 'No email?';
            return $retArr;
        }
        $steamID = Auth::user()->steamId64;

        DB::update('update users set email = ?,email_confirm = ? where steamId64 = ?', [$email, '0', $steamID]);
        $userID = Auth::user()->id;
        $nick = Auth::user()->nick;
        \Mail::send('emails.confirm', ['nick' => $nick, 'id' => $userID], function ($m) use ($email, $userID, $nick) {
            $m->setFrom('noreply@csgourban.com', 'csgourban.com');
            $m->to($email, $nick)->subject('Email confirmation from csgourban.com');
        });
        return $retArr;
    }

    public function getFreeCoins(Request $request)
    {
        if (Auth::check()) {
            $userThis = \App\User::where('steamId64', Auth::user()->steamId64)->first();
            $thisCode = $request->get('coinscode');
            $userCode = $userThis->refcode;
            if ($thisCode == $userCode) {
                return $this->responseErr('You cannot use your own code!');
            }
            $reddemed = $userThis->reedem_code;
            $searchForCode = \App\User::where('refcode', $request->get('coinscode'))->count();
            if ($searchForCode < 1) {
                return $this->responseErr('You provided bad code!');
            }
            if ($userThis->havecsgo == 0) {
                return $this->responseErr('You need to have CS:GO on your account!');
            }
            $searchForOwner = \App\User::where('refcode', $request->get('coinscode'))->first();
            if ($reddemed == null) {
                $update = \App\User::where('steamId64', $userThis->steamId64)->update(['coins' => $userThis->coins + 500]);
                $update = \App\User::where('steamId64', $userThis->steamId64)->update(['reedem_code' => $request->get('coinscode')]);
                $updateSec = \App\User::where('steamId64', $searchForOwner->steamId64)->update(['coins' => $searchForOwner->coins + 50]);
                return $this->responseSuccess('You recived your 500 free coins with success!');
            } else {
                return $this->responseErr('You had your chanse to get free coins!');
            }
        } else {
            return $this->responseErr('You are not logged in!');
        }
    }

    public function getUserProfile($steamid)
    {
        $user = \App\User::where('steamId64', $steamid)->get(['avatar', 'coins', 'created_at', 'nick', 'url', 'refcode']);
        $getReferals = \App\User::where('reedem_code', $user[0]->refcode)->count();
        $getTotalBet = \App\placedBets::where('userID64', $steamid)->sum('ammount');
        $getTotalWithdraw = \App\ofers::where('userID', $steamid)->where('roundID', NULL)->sum('value');
        $getTotalDeposit = \App\deposit_ofers::where('userID', $steamid)->where('status', 'Accepted')->sum('value');
        $returnInfo['avatar'] = $user[0]->avatar;
        $returnInfo['coins'] = $user[0]->coins;
        if ($user[0]->created_at) {
            $returnInfo['created_at'] = $user[0]->created_at->toFormattedDateString();
        } else {
            $returnInfo['created_at'] = 'start';
        }
        $returnInfo['nick'] = $user[0]->nick;
        $returnInfo['url'] = $user[0]->url;
        $returnInfo['refs'] = $getReferals;
        $returnInfo['totalBet'] = $getTotalBet;
        $returnInfo['totalWith'] = $getTotalWithdraw;
        $returnInfo['totalDepo'] = $getTotalDeposit;
        return json_encode($returnInfo);
    }


    public function checkIsAdmin()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin == 1 || Auth::user()->isMod == 1) {
                $isAdmin = 1;
            } else {
                $isAdmin = 0;
            }
        } else {
            $isAdmin = 0;
        }
        $isAdmin = json_encode($isAdmin);
        return $isAdmin;
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
