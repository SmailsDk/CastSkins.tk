<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Auth;
use \App\User;
use Illuminate\Support\Str;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class BuyController extends Controller
{

    public function buy()
    {
        if (Auth::check()) {
//            if(Auth::user()->tradeURL == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email_confirm < 1) {
//                return Redirect::to('http://csgourban.com#please');
//            }
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
            $getUserPayments = \App\g2a::where('status', '!=', 0)->where('buyer', Auth::user()->steamId64)->count();
            $getsms = \App\SIMPAY::where('buyer', Auth::user()->steamId64)->count();
            if($getsms > 0) {
                $getsms = \App\SIMPAY::where('buyer', Auth::user()->steamId64)->get();
            } else {
                $getsms = 0;
            }
            if($getUserPayments > 0) {
                $getUserPay = \App\g2a::where('status', '!=', 0)->where('buyer', Auth::user()->steamId64)->orderBy('id','desc')->get();
            } else {
                $getUserPay = 0;
            }
        } else {
            $getUserPay = 0;
            $checkUserUsedYourCode = 0;
            $getsms = 0;
        }
        $codes = \App\sms::get();
        $today = Carbon::now();
        $users = User::count();
        return view('pages.buy', compact('users','getsms','checkUserUsedYourCode', 'today','getUserPay','codes'));
    }

    public function updateStatus()
    {
        $file = public_path() . '/people.txt';
        $current = file_get_contents($file);
        $current .= "John Smith\n";
        file_put_contents($file, $current);

        //$update = \App\g2a::where('orderID', '==', $request->get('userOrderId'))->update(['status' => 3]);
    }

    public function requestToken(Request $request)
    {
        $retArr = [];
        if (!Auth::check()) {
            return $this->responseErr('You need to be logged in!');
        }
        if ($request->get('amount') == null || $request->get('amount') < 10) {
            return $this->responseErr('Minimum 10 coins');
        }
        if ($request->get('amount') > 1000000) {
            return $this->responseErr('Maximum 1000000 coins');
        }
        if ($request->get('email') == null) {
            return $this->responseErr('You need to provide your email!');
        }
        $orderID = str_random(20);
        $retArr['data-id'] = "pay-g2a-script";
        $retArr['api_hash'] = '4df8ece7-b436-456d-b78d-f0772397ff2f';
//        $retArr['api_hash'] = '9cdd90f2-25d6-4928-97a7-cb4c084a85bf';
        $retArr['status'] = "OK";
        $retArr['currency'] = 'EUR';
        $retArr['order_id'] = $orderID;
        $retArr['items'][0]['sku'] = '' . $request->get('amount') . ' COINS ON CSGOURBAN.COM FOR USER ' . Auth::user()->steamId64 . '';
        $retArr['items'][0]['qty'] = $request->get('amount');
        $retArr['items'][0]['name'] = '' . $request->get('amount') . ' COINS ON CSGOURBAN.COM FOR USER ' . Auth::user()->steamId64 . '';
        $retArr['items'][0]['type'] = 'COINS';
        $retArr['items'][0]['id'] = 'TEST';
        $retArr['items'][0]['price'] = 0.0013;
        $retArr['items'][0]['amount'] = $request->get('amount') * $retArr['items'][0]['price'];
//        $retArr['items'][0]['price'] = ($request->get('amount') / 1000) * 1.2;
        $retArr['items'][0]['url'] = 'http://csgourban.com';
        $retArr['amount'] = $request->get('amount') * $retArr['items'][0]['price'];
        $retArr['description'] = '' . $request->get('amount') . ' COINS ON CSGOURBAN.COM FOR USER ' . Auth::user()->steamId64 . '';
        $retArr['email'] = $request->get('email');
        $retArr['url_failure'] = 'http://csgourban.com/buy#ups';
        $retArr['url_ok'] = 'http://csgourban.com/buy#thanks';

        $update = \App\User::where('steamId64', Auth::user()->steamId64)->update(['email' => $request->get('email')]);
        $insert = \App\g2a::create([
            'orderID' => $orderID,
            'buyer' => Auth::user()->steamId64,
            'coins' => $request->get('amount'),
            'cost' => $request->get('amount') * $retArr['items'][0]['price']
        ]);

        if ($insert) {
            return $this->responseSuccess(json_encode($retArr));
        } else {
            return $this->responseErr('Something went wrong :(');
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
