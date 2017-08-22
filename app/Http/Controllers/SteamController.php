<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Support\Str;
use Redirect;

class SteamController extends Controller
{
    private $steamAuth;

    public function __construct(SteamAuth $auth)
    {
        parent::__construct();
        $this->steamAuth = $auth;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function redirect_post($url, array $data, array $headers = null)
    {
        $params = array(
            'http' => array(
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        if (!is_null($headers)) {
            $params['http']['header'] = '';
            foreach ($headers as $k => $v) {
                $params['http']['header'] .= "$k: $v\n";
            }
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if ($fp) {
            echo @stream_get_contents($fp);
            die();
        } else {
            // Error
            throw new Exception("Error loading '$url', $php_errormsg");
        }
    }

    public function authenticate($id)
    {

        $user_id = Crypt::decrypt($id);
        $user = \App\User::where('id', $user_id)->first();
        Auth::login($user, true);
        return redirect('/');
    }

    public function regFake(Request $request)
    {
        $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=39071F5931CC0088A82162A83824123B&steamids=' . $request->get('steamid') . '');
        $obj = json_decode($json);
//        $obj = $obj->toArray();
        $user = \App\User::where('steamId64', $request->get('steamid'))->first();
        if (is_null($user)) {
            \App\User::create([
                'steamId64' => $obj->response->players[0]->steamid,
                'refcode' => Str::random(8),
                'nick' =>  $obj->response->players[0]->personaname,
                'avatar' => $obj->response->players[0]->avatar,
                'url' => $obj->response->players[0]->profileurl,
                'havecsgo' => '1'
            ]);
        } else {
            dd($user);
        }
    }

    public function login()
    {


        if ($this->steamAuth->validate()) {
            $steamID = $this->steamAuth->getSteamId();
            $user = \App\User::where('steamId64', $steamID)->first();
            if (!is_null($user)) {

                $steamInfo = $this->steamAuth->getUserInfo();

                $nick = $steamInfo->getNick();
                if (preg_match("/Administrator|Admin|admins|script|marquee|script|null|admin/i", $nick)) {

                    $nick = 'Nickname not correct';
                }
                $avatar = $steamInfo->getProfilePictureFull();
                $avatar = str_replace('https', 'http', $avatar);


                $filename = Str::random(20);
                $steamoo = $steamInfo->getSteamID64();


                DB::update('update users set nick = ?, avatar = ? where steamId64 = ?', [$nick, $avatar, $steamID]);
                $code = DB::select("SELECT refcode FROM users WHERE steamId64='$steamoo'");
                $refcodee = Str::random(8);


                foreach ($code as $cd) {
                    if ($cd->refcode == null) {
                        DB::update('update users set refcode = ? where steamId64 = ?', [$refcodee, $steamInfo->getSteamID64()]);
                    }
                }

                $json = file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=39071F5931CC0088A82162A83824123B&steamid=' . $steamID . '&format=json');
                $obj = json_decode($json);

                DB::update('update users set havecsgo = ? where steamId64 = ?', [0, $steamID]);
                foreach ($obj->response->games as $game) {
                    if ($game->appid == 730) {
                        DB::update('update users set havecsgo = ? where steamId64 = ?', [1, $steamID]);
                    }
                }


                $user = \App\User::where('steamId64', $steamoo)->first();

                if ($user->global_banned > 0) {
                    return $user->global_banned_reason;
                }
                return Redirect::to('http://csgourban.com/authenticate/' . Crypt::encrypt($user->id) . '');
                //return redirect()->route('authenticate', Crypt::encrypt($user->id));
                // Auth::login($user, true);
                // dd($obj->playerstats->achievements[0]->achieved);
            } else {

                $steamInfo = $this->steamAuth->getUserInfo();

                $nick = $steamInfo->getNick();
                if (preg_match("/Administrator|Admin|admins|script|marquee|script|null|admin/i", $nick)) {

                    $nick = 'Nickname not correct';
                }
                $characters = "23456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz";

                $string = '';
                for ($p = 0; $p < 15; $p++) {
                    $string .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
                $avatar = $steamInfo->getProfilePictureFull();
                $avatar = str_replace('https', 'http', $avatar);
                $steamoo = $steamInfo->getSteamID64();
                $user = \App\User::create([
                    'steamId64' => $steamInfo->getSteamID64(),
                    'refcode' => Str::random(8),
                    'nick' => $nick,
                    'avatar' => $avatar,
                    'url' => $steamInfo->getprofileURL()
                ]);
                $json = file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=39071F5931CC0088A82162A83824123B&steamid=' . $steamID . '&format=json');
                $obj = json_decode($json);

                DB::update('update users set havecsgo = ? where steamId64 = ?', [0, $steamID]);
                foreach ($obj->response->games as $game) {
                    if ($game->appid == 730) {
                        DB::update('update users set havecsgo = ? where steamId64 = ?', [1, $steamID]);
                    }
                }
                //$user = \App\User::where('steamId64', $steamoo)->get();
                if ($user->global_banned > 0) {
                    return $user->global_banned_reason;
                }
                return Redirect::to('http://csgourban.com/authenticate/' . Crypt::encrypt($user->id) . '');
            }

            //\Auth::login($user, true, 120);
            return redirect('/');
        } else {
            return $this->steamAuth->redirect();
        }
    }


}