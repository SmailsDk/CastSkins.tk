<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WinnerController extends Controller
{
    public function getuserinfo($allUsersInfoStr, $steamIDToFind)
    {
        $allUsersInfo = json_decode($allUsersInfoStr, true);
        $players = $allUsersInfo['response']['players'];

        foreach ($players as $player) {
            $steamID = $player['steamid'];
            $player['personaname'] = htmlentities($player['personaname']);

            if ($steamIDToFind === $steamID) {
                return $player;
            }
        }

        # If the user is not found, then return false
        return false;
    }

    public function generateHash($roundID)
    {
        $nope = 'Brak id rundy';
        if ($roundID) {
            $rand = $roundID / 56.5;
            return md5($rand);
        } else {
            return $nope;
        }
    }

    public function getWin()
    {
        $allPotItems = \App\pot::get();
        $potCount = \App\pot::count();
        if ($potCount < 1) {
            return;
        }
        $ticketsArr = array();
        $totalPotPrice = 0;

        $i = 0;
        foreach ($allPotItems as $item) {
            $itemOwnerId64 = $item['ownerSteamId64'];
            $itemPrice = $item['itemPrice'];
            $totalPotPrice += $itemPrice;

            for ($i1 = 0; $i1 < $itemPrice; $i1++) {
                array_push($ticketsArr, array('number' => $i, '64' => $itemOwnerId64));
                $i++;
            }

        }

        $winner = $ticketsArr[array_rand($ticketsArr)];


        // get winner ID
        $winnerSteamId64 = $winner['64'];

        $winnerItems = \App\pot::where('ownerSteamId64', $winnerSteamId64)->get();
        $userPrice = 0;
        foreach ($winnerItems as $item) {
            $itemPrice = $item['itemPrice'];
            $userPrice += $itemPrice;
        }

        $winnerProcent = ($userPrice / $totalPotPrice) * 100;
        $winnero = floor((count($ticketsArr) - 0.0000000001) * ($winnerProcent / 100));
        $winningTicket = $ticketsArr[$winnero]['number'];

        echo $winnero;
        echo '<br>';
        echo "Wygrał ticket: $winningTicket";
        echo '<br>';
        dd($ticketsArr);
        echo "Należy do : $winnerSteamId64";

    }

    public function getWinner()
    {
        $allPotItems = \App\pot::get();
        $ticketsArr = array();
        $totalPotPrice = 0;
        $i = 1;
        foreach ($allPotItems as $item) {
            $itemOwnerId64 = $item['ownerSteamId64'];
            $itemPrice = $item['itemPrice'];
            $totalPotPrice += $itemPrice;
            for ($i1 = 1; $i1 < $itemPrice; $i1++) {
                array_push($ticketsArr, array('number' => $i, '64' => $itemOwnerId64));
            }
            $i++;
        }
        $winner = $ticketsArr[array_rand($ticketsArr)];
        // get winner ID
        $winnerSteamId64 = $winner['64'];


        // Check the price of what the winner put in, to get their odds

        $winnerItems = \App\pot::where('ownerSteamId64', $winnerSteamId64)->get();
        $userPrice = 0;
        foreach ($winnerItems as $item) {
            $itemPrice = $item['itemPrice'];
            $userPrice += $itemPrice;
        }

        $allItems = DB::select("SELECT * FROM currentPot ORDER BY itemPrice DESC");

        $allUsersArr = array();
        foreach ($allItems as $item) {
            array_push($allUsersArr, $item->ownerSteamId64);
        }
        $allUserIDsStr = join(',', $allUsersArr);

        $key = '770628C2B6A2C0897C1515624BFAD39F';
        $usersInfoStr = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$key&steamids=$allUserIDsStr");

        $allPlayers = array();
        $allPlayersInPot = DB::select("SELECT ownerSteamId64 FROM currentPot GROUP BY ownerSteamId64");
        foreach ($allPlayersInPot as $player) {
            $arrr = array('user' => $player);
            array_push($allPlayers, $arrr);
        }

        $allItemsInPrevGame = array();
        foreach ($allItems as $item) {
            $id = $item->id;
            $itemName = $item->itemName;
            $itemPrice = $item->itemPrice;
            $itemIcon = $item->itemIcon;
            $assetid = $item->instanceId;
            $itemOwnerId64 = $item->ownerSteamId64;
            $steamUserInfo = WinnerController::getuserinfo($usersInfoStr, $itemOwnerId64);
            $arr = array('itemID' => $id, 'assetid' => $assetid, 'itemSteamOwnerInfo' => $steamUserInfo, 'itemName' => $itemName, 'itemPrice' => $itemPrice, 'itemIcon' => $itemIcon);
            array_push($allItemsInPrevGame, $arr);
        }

        $allItemsJsonForDB = json_encode($allItemsInPrevGame);
        $allPlayersJsonForDB = json_encode($allPlayers);
        // Get the id for the current round
        $mostRecentHistory = DB::select("SELECT * FROM history ORDER BY id DESC LIMIT 1");
        foreach ($mostRecentHistory as $history) {
            $history_id = $history->id;
            $roundHash = $history->roundHash;
        }
        $winnerProcent = ($userPrice / $totalPotPrice) * 100;
        $winnero = floor((count($ticketsArr) - 0.0000000001) * ($winnerProcent / 100));
        $winningTicket = $ticketsArr[$winnero]['number'];
        $winner64 = $ticketsArr[$winnero]['64'];


//Provide coorect winner

        $userData = \App\User::where('steamId64', $winner64)->get(['id', 'tradeToken', 'nick']);
        $winnerTradeToken = $userData[0]['tradeToken'];
        $userNick = $userData[0]['nick'];
        $haveCSBOX = strpos($userNick, "CSGOURBAN.COM");
        if ($haveCSBOX == false) {
            $haveCSBOX = strpos($userNick, "csgourban.com");
        }
        if ($haveCSBOX == false) {
            $haveCSBOX = strpos($userNick, "CSGOURBAN.COM");
        }

        $winnerItems = \App\pot::where('ownerSteamId64', $winner64)->get();
        $userPrice = 0;
        foreach ($winnerItems as $item) {
            $itemPrice = $item['itemPrice'];
            $userPrice += $itemPrice;
        }
        $allItems = DB::select("SELECT * FROM currentPot ORDER BY itemPrice DESC");

        $keepPercentage = 0;
        $itemsToKeep = array();
        $itemsToGive = array();
        $itemsToGives = array();
        $give = false;
        $provision = 0;


        if ($haveCSBOX == false) {
            $percentage = 0.10;
            $myNumber = $totalPotPrice;
            $percent = $percentage * $myNumber;
            $keep = ceil($percent);

            foreach ($allItems as $key => $value) {
                array_push($itemsToGive, $value);
                continue;
            }
            foreach ($itemsToGive as $key => $value) {
                array_push($itemsToGives, $value);
                continue;
            }

            foreach ($itemsToGive as $key => $value) {
                $thisPrice = intval($value->itemPrice);
                if ($thisPrice <= $keep) {
                    if ($keep != 0) {
                        array_push($itemsToKeep, $value);
                        unset($itemsToGives[$key]);
                        $keep -= $thisPrice;
                    }
                }
                continue;
            }
            $provision = 10;
        } else {
            $percentage = 0.05;
            $myNumber = $totalPotPrice;
            $percent = $percentage * $myNumber;
            $keep = ceil($percent);

            foreach ($allItems as $key => $value) {
                array_push($itemsToGive, $value);
                continue;
            }
            foreach ($itemsToGive as $key => $value) {
                array_push($itemsToGives, $value);
                continue;
            }

            foreach ($itemsToGive as $key => $value) {
                $thisPrice = intval($value->itemPrice);
                if ($thisPrice <= $keep) {
                    if ($keep != 0) {
                        array_push($itemsToKeep, $value);
                        unset($itemsToGives[$key]);
                        $keep -= $thisPrice;
                    }
                }
                continue;
            }
            $provision = 5;
        }

        DB::update('update history set ticketwon = ?, ticketwinner = ?, winnerSteamId64 = ?, userPutInPrice = ?, potPrice = ?, allItemsJson = ?, usersInPot = ?, date = NOW() where id = ?', [$winningTicket, $winner64, $winner64, $userPrice,
            $totalPotPrice, $allItemsJsonForDB, $allPlayersJsonForDB, $history_id]);


        $data = array(
            'winnerSteamId' => $winner64,
            'winnerTradeToken' => $winnerTradeToken,
            'tradeItems' => $itemsToGives,
            'profitItems' => $itemsToKeep,
            'allPlayers' => $allPlayers,
            'gameID' => $history_id,
            'roundHash' => $roundHash,
            'haveCSBOX' => $haveCSBOX,
            'nick' => $userNick,
            'prowizja' => $provision
        );
        //DB::insert('insert into chat (steamUserID, text) values (?, ?)', [$winnerSteamId64, "Wygrałem rundę #$history_id !"]);
        DB::update('TRUNCATE TABLE currentPot');
        return $data;
    }

}
