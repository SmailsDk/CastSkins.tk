<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Redirect;

class DepositController extends Controller
{

    public function deposit()
    {
        if (Auth::check()) {
            if(Auth::user()->tradeURL == '') {
                return redirect()->route('settings');
            }
//            if(Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
//
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();

        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = \App\User::count();
        return view('pages.deposit', compact('checkUserUsedYourCode', 'users'));
    }

    public function depositSelected(Request $request)
    {
        if (Auth::check()) {

            $checkLastDepo = \App\deposit_que::where('user_id', Auth::User()->steamId64)->orderBy('id','desc')->first();

            if($checkLastDepo) {
                if(\Carbon\Carbon::now()->diffInMinutes($checkLastDepo->created_at) < 3) {
                    return $this->responseErr('You need to wait until next deposit. '.\Carbon\Carbon::now()->diffInMinutes($checkLastDepo->created_at).'/3 minutes');
                }
            }
            $checkDepo = \App\deposit_que::where('user_id', Auth::User()->steamId64)->where('status', '0')->count();

            if ($checkDepo > 1) {
                return $this->responseErr('Please accept existing offer first!');
            }

            $items = $request->get('items');
            $itemsToDeposit = [];
            $offerPrice = 0;
            $i = 0;

            foreach ($items as $item) {
                $itemPrice = \App\item::where('marketName', $item['name'])->get();
                $itemPrice = $itemPrice[0]->avgPrice30Days;
                if($itemPrice < 40) {
                    return $this->responseErr('An error occured, please try again.');
                }
                $offerPrice = intval($offerPrice) + intval($itemPrice);
                $i += 1;
                $itemsToDeposit[$i]['price'] = $itemPrice;
                $itemsToDeposit[$i]['info'] = $item;
            }
            $offerPrice = intval($offerPrice * 10);
            foreach ($itemsToDeposit as $depo) {
                $creator = \App\deposit_que::create([
                    'user_id' => Auth::User()->steamId64,
                    'assetID' => $depo['info']['assetid'],
                    'itemname' => $depo['info']['name'],
                    'valued' => $depo['price'] * 10
                ]);
            }
            return $this->responseSuccess('Please check your steam offers, in about 10 seconds! .');
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
