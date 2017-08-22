<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['language']], function () {
    Route::get('/roulette', ['as' => 'index', 'uses' => 'IndexController@roulette']);
    Route::get('/', ['as' => 'bust', 'uses' => 'BustController@bust']);
    Route::get('/bust', ['as' => 'bust', 'uses' => 'BustController@bust']);

    Route::get('/login', ['as' => 'login', 'uses' => 'SteamController@login']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'SteamController@logout']);
    Route::get('/authenticate/{id}', function ($id) {
        $user_id = Crypt::decrypt($id);
        $user = \App\User::where('id', $user_id)->first();
        Auth::login($user, true);
        return redirect('/');
    });

// Emails
    Route::get('/resetPlayers', ['as' => 'resetPlayers\',', 'uses' => 'IndexController@resetPlayers']);
    Route::get('/confirmEmail/{id}', ['as' => 'confirmEmail', 'uses' => 'IndexController@confirmEmail']);

//    Chat routes

    Route::get('/getLastMessages', ['as' => 'getLastMessages', 'uses' => 'ChatController@getLastMessages']);
    Route::post('/sendChatMessage', ['as' => 'sendChatMessage', 'uses' => 'ChatController@sendChatMessage']);
    Route::get('/getLastMessage', ['as' => 'getLastMessage', 'uses' => 'ChatController@getLastMessage']);
    Route::get('/checkIsAdmin', ['as' => 'checkIsAdmin', 'uses' => 'UserController@checkIsAdmin']);

//    User routes
    Route::get('/getUserInfo/{steamID}', ['as' => 'getUserInfo', 'uses' => 'UserController@getUserInfo']);
    Route::get('/checkLogin', ['as' => 'checkLogin', 'uses' => 'UserController@checkLogin']);
    Route::get('/settings', ['as' => 'settings', 'uses' => 'UserController@settings']);
    Route::get('/jackpotUserHistory', ['as' => 'jackpotUserHistory', 'uses' => 'UserController@jackpotUserHistory']);
    Route::get('/rouletteUserHistory', ['as' => 'rouletteUserHistory', 'uses' => 'UserController@rouletteUserHistory']);
    Route::get('/coinflipUserHistory', ['as' => 'coinflipUserHistory', 'uses' => 'UserController@coinflipUserHistory']);
    Route::post('/getFreeCoins', ['as' => 'getFreeCoins', 'uses' => 'UserController@getFreeCoins']);
    Route::post('/saveTradeToken', ['as' => 'saveTradeToken', 'uses' => 'UserController@saveTradeToken']);
    Route::post('/saveEmail', ['as' => 'saveEmail', 'uses' => 'UserController@saveEmail']);
    Route::post('/transferCoins', ['as' => 'transferCoins', 'uses' => 'UserController@transferCoins']);
    Route::get('/transfers', ['as' => 'transfers', 'uses' => 'UserController@transfers']);
    Route::get('/getUserProfile/{id}', ['as' => 'getUserProfile', 'uses' => 'UserController@getUserProfile']);

//    Roulette routes

    Route::post('/placeBet', ['as' => 'placeBet', 'uses' => 'RouletteController@placeBet']);
    Route::get('/rouletteHistory', ['as' => 'history_ruletka', 'uses' => 'RouletteController@history_ruletka']);
    
    // Crash Routes

    Route::get('/createNewBust/{accesToken}', ['as' => 'createNewBust', 'uses' => 'BustController@createNewBust']);
    Route::post('/placeBust', ['as' => 'placeBust', 'uses' => 'BustController@placeBust']);
    Route::post('/cashOut', ['as' => 'cashOut', 'uses' => 'BustController@cashOut']);
    Route::post('/getUserSteamId64', ['as' => 'getUserSteamId64', 'uses' => 'BustController@getUserSteamId64']);

// Withdraw routes

    Route::get('/withdraw', ['as' => 'withdraw', 'uses' => 'WithdrawController@withdraw']);
    Route::post('/withdrawSelected', ['as' => 'withdrawSelected', 'uses' => 'WithdrawController@withdrawSelected']);

// Withdraw routes

    Route::get('/deposit', ['as' => 'deposit', 'uses' => 'DepositController@deposit']);
    Route::post('/depositSelected', ['as' => 'depositSelected', 'uses' => 'DepositController@depositSelected']);

//    Coinflip routes

    Route::get('/coinflip', ['as' => 'coinflip', 'uses' => 'CoinflipController@index']);
    Route::post('/makeCoinflip', ['as' => 'makeCoinflip', 'uses' => 'CoinflipController@makeCoinflip']);
    Route::post('/joinRoom', ['as' => 'joinRoom', 'uses' => 'CoinflipController@joinRoom']);
    Route::post('/setActive', ['as' => 'setActive', 'uses' => 'CoinflipController@setActive']);
    Route::get('/room/{id}', ['as' => 'joinRoom', 'uses' => 'CoinflipController@room']);
    Route::get('/checkRoom/{id}', ['as' => 'joinRoom', 'uses' => 'CoinflipController@checkRoom']);

// Jackpot routes

    Route::get('/jackpot', ['as' => 'jackpot', 'uses' => 'JackpotController@jackpot']);
    Route::get('/getUserItems/{steamID}', ['as' => 'getUserItems', 'uses' => 'JackpotController@getUserItems']);
    Route::get('/getLastWinner', ['as' => 'getLastWinner', 'uses' => 'JackpotController@getLastWinner']);
    Route::get('/pro', ['as' => 'pro', 'uses' => 'JackpotController@pro']);
    Route::post('/set', ['as' => 'set', 'uses' => 'JackpotController@set']);
    Route::get('/jackpothistory', ['as' => 'jackpothistory', 'uses' => 'JackpotController@jackpothistory']);


//    Payments routes
    Route::get('/buy', ['as' => 'buy', 'uses' => 'BuyController@buy']);
    Route::post('/requestToken', ['as' => 'requestToken', 'uses' => 'BuyController@requestToken']);
    Route::post('/updatestatus2', ['as' => 'updateStatus', 'uses' => 'BuyController@updateStatus']);

//    Admin ROutes

    Route::post('/user/{action}', ['as' => 'user_action', 'uses' => 'AdminController@user_action']);

    // Others

    Route::get('/regFake', ['as' => 'regFake', 'uses' => 'SteamController@regFake']);
    Route::get('/updateItems', ['as' => 'updateItems', 'uses' => 'IndexController@updateItems']);
    Route::get('/getGAuth', ['as' => 'getGAuth', 'uses' => 'IndexController@getGAuth']);
    Route::get('/getWinner2124954id273', ['as' => 'getWinner', 'uses' => 'WinnerController@getWinner']);
    Route::get('/getWinningColor/{accesToken}/{roundID}', ['as' => 'getWinningColor', 'uses' => 'ColorController@getWinningColor']);
    Route::get('/getItemPrice/{itemName}', ['as' => 'getItemPrice', 'uses' => 'ItemController@getItemPrice']);
    Route::get('/sendAdv33', ['as' => 'sendAdv', 'uses' => 'ChatController@sendAdv']);
    Route::get('/sendAdv22', ['as' => 'sendAdv2', 'uses' => 'ChatController@sendAdv2']);
    Route::get('/freeskins', ['as' => 'freeSkins', 'uses' => 'IndexController@freeSkins']);
    Route::get('/words', ['as' => 'words', 'uses' => 'IndexController@words']);

});
