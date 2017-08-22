<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LanguageController;
class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('applocale') and in_array(Session::get('applocale'), ['en', 'pl', 'ru'])) {

            App::setLocale(Session::get('applocale'));

        } else {

            $langCtrl = new LanguageController;
            $userIP = request()->ip();
            $userLang = $langCtrl->queryAPI($userIP);
            Session::set('applocale', $userLang);
            App::setLocale($userLang);

        }
        return $next($request);
    }
}