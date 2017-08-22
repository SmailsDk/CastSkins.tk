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
use Carbon\Carbon;

class ChatController extends Controller
{

    public function sendChatMessage(Request $request)
    {
        if (Auth::check()) {
            $getCount = \App\Chat::where('steamUserID', Auth::User()->steamId64)->orderBy('id','desc')->count();
            $getLastMessTime = \App\Chat::where('steamUserID', Auth::User()->steamId64)->orderBy('id','desc')->first();
            $nowIs = new Carbon();
            if($getCount > 0) {
                if($nowIs->diffInSeconds($getLastMessTime->created_at) < 10) {
                    return $this->responseErr('To fast dude, slow down with those messages.');
                }
            }

            $message = $request->get('message');
            $User = \App\User::where('steamId64', Auth::User()->steamId64)->first();

            $userBanTime = $User->chat_timeout;
            $userBanTime = Carbon::parse($userBanTime);
            $diff = $nowIs->diffInSeconds($userBanTime, false);
            $COUNTER = \App\placedBets::where('userID64', Auth::User()->steamId64)->groupBy('userID64')
                ->selectRaw('sum(ammount) as sum')
                ->count();
            if ($COUNTER < 1) {
                return $this->responseErr('You need to place bet to start chating 0/1000000!');
            }
            $placed = \App\placedBets::where('userID64', Auth::User()->steamId64)->groupBy('userID64')
                ->selectRaw('sum(ammount) as sum')
                ->get();
            $placed = $placed[0]->sum;
            if ($placed < 10000000) {
                $retArr['locked'] = $placed;
                return $this->responseErr('You need to place bet to start chat ' . $placed . '/10000000!');
            }

            if ($diff > 0) {
                return $this->responseErr('You are banned!');
            }
            if ($message == '') {
                return $this->responseErr('Empty?!');
            }
            if (Auth::User()->chat_blocked == 1) {
                return $this->responseErr('You are muted from the chat!');
            }
            $steamid = Auth::User()->steamId64;
            $insert = Chat::create([
                'steamUserID' => $steamid,
                'text' => $message,
                'room' => $request->get('type')
            ]);
            if ($insert) {
                return $this->responseSuccess(':)');
        }
        } else {
            // $request->get('message');
            return $this->responseErr('You are not logged in!');
        }
    }

    public function sendAdv()
    {
        $insert = Chat::create([
            'steamUserID' => '76561198023345468',
            'text' => 'Need to sell or buy skins? Go to www.bitskins.com !',
            'room' => 'general'
        ]);

    }

    public function sendAdv2()
    {
        $insert = Chat::create([
            'steamUserID' => '76561198023345468',
            'text' => 'Nedd to sell or buy skins? Go to www.bitskins.com !',
            'room' => 'general'
        ]);
    }

    public function getLastMessages()
    {
        $allmessages = Chat::limit(15)->orderBy('id', 'desc')->get();
        $messages = [];
        foreach ($allmessages as $message) {
            $messages[$message->id]['message'] = $message;
            $user_info = User::where('steamId64', $message->steamUserID)->first();
            $messages[$message->id]['user'] = $user_info;
        }
        return json_encode($messages);
    }

    public function getLastMessage()
    {
        $allmessages = Chat::limit(1)->orderBy('id', 'desc')->get();
        $messages = [];
        foreach ($allmessages as $message) {
            $messages[$message->id]['message'] = $message;
            $user_info = User::where('steamId64', $message->steamUserID)->first();
            $messages[$message->id]['user'] = $user_info;
        }
        return json_encode($messages);
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
