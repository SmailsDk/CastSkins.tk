<?php namespace App\Http\Middleware;

use App\LangDetect;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Torann\GeoIP\GeoIPFacade;
use Closure;

class OnEnter
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
//            $lang = new LangDetect;
//            $langs = ['ru' => ['ru','pl','uk','ky','ab','mo','et','lv']];
//            if ((is_null(Session::get('lang'))) || empty(Session::get('lang'))) {
//                if(Auth::guest()) {
//                    Session::set('lang', $lang->getBestMatch('en', $langs));
//                } else {
//                    $user_lang = User::find(Auth::user()->id)->language;
//                    if(is_null($user_lang)) {
//                        Session::set('lang', $lang->getBestMatch('en', $langs));
//                    } else {
//                        Session::set('lang', $user_lang);
//                    }
//                }
//            }
//            $lang = Session::get('lang');
//            if(is_null($lang)) {
//                $lang = config('app.locale');
//            }
//            App::setLocale($lang);
        }
        return $next($request);
    }

}
