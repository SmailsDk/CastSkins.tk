<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class WithdrawController extends Controller
{

    public function withdrawSelected(Request $request)
    {
        if (Auth::check()) {
            $getLastWithdraw = \App\que::where('user_id', Auth::User()->steamId64)->orderBy('id', 'desc')->first();

            if ($getLastWithdraw) {
                if (\Carbon\Carbon::now()->diffInHours($getLastWithdraw->created_at) < 24) {
                    return $this->responseErr('One withdraw per 24h, you are not alone on this site!');
                }
            }

            // return $this->responseErr('Please wait for next skins shipement if you dont want to get 1 cents skins!');
            $items = $request->All()['items'];
            $steamid = Auth::user()->steamId64;
            $isLocked = \App\que::get(['assetid']);
            $howMuchInQue = \App\que::where('user_id', $steamid)->where('status', 'awaiting')->count();
            $userCoins = Auth::user()->coins;


            $offerPrice = 0;
            foreach ($items as $item) {
                $price = \App\item::where('marketName', $item['name'])->first(['avgPrice30Days']);
                if ($price == null) {
                    return $this->responseErr('One of selected items is no longer exist in withdraw!');
                }
                $price = $price->avgPrice30Days;
                $price = ($price * 10) * 1.2;
                $offerPrice += $price;
            }
            if ($userCoins < $offerPrice) {
                return $this->responseErr('Not enouth coins!');
            }
            foreach ($items as $item) {
                if ($item['assetid'] == 0) {
                    return $this->responseErr('One of selected items is no longer exist in withdraw!');
                }
            }
            $deposited = \App\deposit_ofers::where('userID', $steamid)->where('status', 'Accepted')->sum('value');
            if ($deposited < 5000) {
                return $this->responseErr('To start withdrawing you need to deposit a minimum of 5000 coins ($5) onto our site (IN SKINS).');
            }
            if (Auth::user()->isPartner > 0) {
                return $this->responseErr('You are not able to withdraw!');
            }
            $placed = \App\placedBets::where('userID64', $steamid)->groupBy('userID64')
                ->selectRaw('sum(ammount) as sum')
                ->get();
            $placed = $placed[0]->sum;
            if ($howMuchInQue > 0) {
                return $this->responseErr('You have items in withdraw que, please wait until next request!');
            }
            if ($placed < 20000) {
                return $this->responseErr('To start withdrawing you need to bet a minimum 50000 coins all up (ON ROULETTE)!');
            }
            if (Auth::user()->email == '') {
                return $this->responseErr('You dont have email provided');
            }
//            if (Auth::user()->email_confirm < 1) {
//                return $this->responseErr('Please confirm your email first');
//            }
//            if(Auth::user()->isAdmin < 1) {
//                return $this->responseErr('Withdraw is not available till 08.07.2016 (FRIDAY) becouse of our security issues, sorry we are doing our best.!');
//            }
            $lockedItems = array();
            foreach ($isLocked as $value) {
                array_push($lockedItems, (int)$value->assetid);
            }
            $count = 0;
            foreach ($items as $item) {
                $count += 1;
                if ($count > 10) {
                    return $this->responseErr('You cannot withdraw more than 10 items at time!');
                }
                if (in_array($item['assetid'], $lockedItems)) {
                    return $this->responseErr('One of selected items is no longer exist in withdraw!');
                }
            }
            $userCoins -= $offerPrice;
            $updateCoins = \App\User::where('steamId64', Auth::user()->steamId64)->update(['coins' => $userCoins]);

            foreach ($items as $item) {
                $itemValue = \App\item::where('marketName', $item['name'])->first();
                $itemValue = (intval($itemValue->avgPrice30Days) * 10) * 1.2;
                \App\que::create([
                    'user_id' => $steamid,
                    'assetID' => $item['assetid'],
                    'itemname' => $item['name'],
                    'valued' => $itemValue
                ]);
            }

            return $this->responseSuccess('Your item is in withdraw que! Keep looking at your tradeoffers.');
        } else {
            return $this->responseErr('User is not logged in!');
        }
    }

    public function withdraw()
    {
        if (Auth::check()) {
            if (Auth::user()->tradeURL == '') {
                return redirect()->route('settings');
            }
//            if(Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email_confirm < 1) {
//                return Redirect::to('http://csgourban.com#please');
//            }
            $getLastRandom = \App\withdraw_que::where('status', 'sent')->orderBy('id', 'rand')->limit(5)->get();

            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();

        } else {
            $checkUserUsedYourCode = 0;

        }
        $users = \App\User::count();
        return view('pages.withdraw', compact('checkUserUsedYourCode', 'users'));
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
