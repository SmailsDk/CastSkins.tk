<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
// Caching is useful if we actually use an API to request the locale to use for an IP
use Cache;
class LanguageController extends Controller
{
    // This function will be used in Routes.php
    // It changes the Locale in the session
    public function switchLang($lang)
    {
        // Check if valid language
        if (in_array($lang, ['en', 'pl', 'ru'])) {

            Session::set('applocale', $lang);

        }
        return redirect('/');
    }
    public function queryAPI($ip)
    {
        // Here It's possible to make a request to an IP Geo API to set the user's language.
        // When using an API I recommend caching the response for that IP.
        // I just return English by default.
        return 'en';
    }

}