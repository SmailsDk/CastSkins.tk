<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\coinflip_rooms;
use Auth;
use Illuminate\Http\Request;
use App\que;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class CoinflipController extends Controller
{
    public function index()
    {
        $rooms = coinflip_rooms::where('right', 0)->count();
        if($rooms > 0) {
            $rooms = coinflip_rooms::where('right', 0)->get();
        } else {
            $rooms = coinflip_rooms::where('right', 0)->count();
        }
        if(Auth::check()) {
//            if(Auth::user()->tradeURL == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
//            if(Auth::user()->email_confirm < 1) {
//                return Redirect::to('http://csgourban.com#please');
//            }
            $roomsYours = coinflip_rooms::where('owner', Auth::user()->steamId64)->count();
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
            if($roomsYours > 0) {
                $roomsYours = coinflip_rooms::where('owner', Auth::user()->steamId64)->orWhere('right', Auth::user()->steamId64)->get();
            } else {
                $roomsYours = 0;
            }
        } else {
            $checkUserUsedYourCode = 0;
            $roomsYours = 0;

        }
        $users = User::count();
        return view('pages.coinflip', compact('users', 'checkUserUsedYourCode','rooms', 'roomsYours'));

    }

    public function setActive(Request $request)
    {
        $rooms = coinflip_rooms::where('id', $request->get('roomID'))->first();
        if ($rooms->right == Auth::user()->steamId64) {
            coinflip_rooms::where('id', $request->get('roomID'))->update(['right_active' => 1]);
        }
        if ($rooms->left == Auth::user()->steamId64) {
            coinflip_rooms::where('id', $request->get('roomID'))->update(['left_active' => 1]);
        }
    }

    public function room($id)
    {
        $room = coinflip_rooms::where('id', $id)->first();

        $userLeft = User::where('steamId64', $room->left)->first();
        if ($room->right == '0') {
            $userRight = '0';
        } else {
            $userRight = User::where('steamId64', $room->right)->first();
        }
        if(Auth::check()) {
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = User::count();
        return view('pages.room', compact('users', 'checkUserUsedYourCode','room', 'userLeft', 'userRight'));
    }

    public function checkRoom($id)
    {
        $retArr = [];
        $room = coinflip_rooms::where('id', $id)->first();
        $now = Carbon::now();
        $roomTime = new Carbon($room->created_at);
        $endtime = new Carbon($room->ended);
        $diff = $now->diffInSeconds($endtime);
        $winnerSIDE = 0;
        $retArr['time'] = $diff;
        $retArr['won'] = $room->won;
        $retArr['right_active'] = $room->right_active;
        $retArr['left_active'] = $room->left_active;
        $retArr['rightSide'] = $room->right_side;
        if ($room->right == '0') {
            $retArr['rightAvatar'] = '0';
        } else {
            $retArr['rightAvatar'] = User::where('steamId64', $room->right)->get(['avatar'])[0]->avatar;
            $retArr['rightName'] = User::where('steamId64', $room->right)->get(['nick'])[0]->nick;
            $retArr['rightID'] = User::where('steamId64', $room->right)->get(['steamId64'])[0]->steamId64;
        }
        if ($diff > 10) {
            if ($room->left_active == 1 && $room->right_active == 1) {
                if ($room->won == '0') {
                    coinflip_rooms::where('id', $id)->update(['ended' => $now]);
                }
            }
        }
        if ($diff == 10) {
            $sides = array(
                'tt' => 'tt',
                'ct' => 'ct'
            );
            $room = coinflip_rooms::where('id', $id)->first();
            if ($room->won == '0') {
                $winnerSIDE = $sides[array_rand($sides)];
                coinflip_rooms::where('id', $id)->update(['won' => $winnerSIDE]);
                if ($room->left_side == $winnerSIDE) {
                    $userToUpdate = User::where('steamId64', $room->left)->first();
                    User::where('steamId64', $room->left)->update(['coins' => ($userToUpdate->coins + (intval($room->ammount) * 2))]);
                } else {
                    $userToUpdate = User::where('steamId64', $room->right)->first();
                    User::where('steamId64', $room->right)->update(['coins' => ($userToUpdate->coins + (intval($room->ammount) * 2))]);
                }
                $retArr['won'] = $winnerSIDE;
                $retArr['0'] = $diff;
            }
        }
        return json_encode($retArr);
    }

    public function makeCoinflip(Request $request)
    {
        if (Auth::check()) {
            $side = $request->get('side');
            $ammount = $request->get('ammount');
            $steamid = Auth::user()->steamId64;

            $checkUser = User::where('steamId64', $steamid)->first();
            if ($checkUser->coins < intval($ammount)) {
                return $this->responseErr('Not enouth coins!');
            }
            if (intval($ammount) < 1) {
                return $this->responseErr('Niu Niu!');
            }
            $check = coinflip_rooms::where('owner', $steamid)->where('right', 0)->count();
            if ($check > 1) {
                return $this->responseErr('You have 2 active games already!');
            }
            $createRoom = coinflip_rooms::create([
                'owner' => $steamid,
                'name' => Auth::user()->nick,
                'left_side' => $side,
                'left_active' => 1,
                'left' => $steamid,
                'right' => 0,
                'ammount' => intval($ammount),
                'won' => 0,
                'ended' => 0
            ]);
            if ($createRoom) {
                User::where('steamId64', $steamid)->update(['coins' => $checkUser->coins - intval($ammount)]);
            }
            $retArr['id'] = $createRoom->id;
            return json_encode($retArr);
        } else {
            return $this->responseErr('You need to be logged in!');
        }


        return view('pages.coinflip');
    }

    public function joinRoom(Request $request)
    {
        if (Auth::check()) {
            $roomID = $request->get('roomID');
            $getRoomInfo = coinflip_rooms::where('id', $roomID)->first();
            $steamid = Auth::user()->steamId64;
            $checkUser = User::where('steamId64', $steamid)->first();
            if ($getRoomInfo->left == $steamid) {
                return $this->responseErr('You cannot join your own room!');
            }
            if ($getRoomInfo->right != 0) {
                return $this->responseErr('This sit is already taken!');
            }
            if ($getRoomInfo->ammount > $checkUser->coins) {
                return $this->responseErr('Not enouth coins!');
            }
            if ($getRoomInfo->ended != 0) {
                return $this->responseErr('This games is over!');
            }
            if ($getRoomInfo->left_side == 'tt') {
                $side = 'ct';
            } else {
                $side = 'tt';
            }

            coinflip_rooms::where('id', $roomID)->update(['right' => Auth::user()->steamId64, 'right_side' => $side, 'right_active' => 1]);
            User::where('steamId64', $steamid)->update(['coins' => Auth::user()->coins - intval($getRoomInfo->ammount)]);
            return $this->responseSuccess('Success, you will be redirected now!');
        } else {
            return $this->responseErr('You need to be logged in!');
        }
        return view('pages.coinflip');
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