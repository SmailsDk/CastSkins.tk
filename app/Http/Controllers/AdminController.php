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

class AdminController extends Controller
{
    public function user_action($action, Request $request)
    {
        if ($action == 'timeout') {
            if (Auth::check()) {
                if (Auth::user()->isAdmin == 1 || Auth::user()->isMod == 1) {
                    $timeoutTime = $request->get('timeoutTime');

                    $User = \App\User::where('steamId64', $request->get('userid'))->first();
                    $nowIs = new Carbon();

                    $userBanTime = $User->chat_timeout;
                    $userBanTime = Carbon::parse($userBanTime);
                    $diff = $nowIs->diffInSeconds($userBanTime, false);
                    $newTime = $nowIs->addDays($timeoutTime);
                    if($diff > 0) {
                            return $this->responseErr('This user is in the hell already! Seconds to unban : '.$diff.'');
                    }
                    if ($timeoutTime < 1) {
                        return $this->responseErr('You need to set day to ban!');
                    }
                    $update = \App\User::where('steamId64', $request->get('userid'))->update(['chat_timeout' => $newTime]);
                    $delete = \App\Chat::where('steamUserID', $request->get('userid'))->delete();
                    if($update) {return $this->responseSuccess('Devil will take care of this user now ;] '); }
                } else {
                    return $this->responseErr('You are not god to decide user destiny!');
                }
            } else {
                return $this->responseErr('You are not logged in!');
            }



        } else {
            return $this->responseErr('We dont know this action, are you crazy?!');
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
