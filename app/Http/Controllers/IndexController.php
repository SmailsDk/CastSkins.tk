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
use Carbon\Carbon;

class IndexController extends Controller
{
    public function roulette()
    {
        if (Auth::check()) {
//            if (Auth::user()->tradeURL == '') {
//                return redirect()->route('settings');
//            }
//            if (Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }
        $users = User::count();
        return view('pages.homepage', compact('users', 'checkUserUsedYourCode'));
    }

    public function freeSkins()
    {
        if (Auth::check()) {
//            if (Auth::user()->tradeURL == '') {
//                return redirect()->route('settings');
//            }
//            if (Auth::user()->email == '') {
//                return redirect()->route('settings');
//            }
            $checkUserUsedYourCode = \App\User::where('reedem_code', Auth::user()->refcode)->count();
        } else {
            $checkUserUsedYourCode = 0;
        }

        $now = '2016-10-20 00:00:00';
        $two = '2016-11-15 00:00:00';
        $usersDeposited = \App\deposit_ofers::where('status', 'Accepted')
            ->whereBetween('date', array($now, $two))
            ->groupBy('userID')
            ->selectRaw('*, sum(value) as deposited')
            ->orderBy('deposited', 'desc')
            ->limit(10)
            ->get();

        $depositors = [];
        $i = 0;

        foreach ($usersDeposited as $ud) {
            $depositors[$i]['deposited'] = $ud->deposited;
            $depositors[$i]['depositorID'] = $ud->userID;
            $thisUD = \App\User::where('steamId64', $ud->userID)->first(['nick', 'avatar']);
            $depositors[$i]['nick'] = $thisUD->nick;
            $depositors[$i]['avatar'] = $thisUD->avatar;
            $i++;
        }
        $users = User::count();
        return view('pages.freeskins', compact('users', 'checkUserUsedYourCode', 'depositors'));
    }

    public function words()
    {
        $str = explode("\n", file_get_contents(public_path() . '/badwords.txt'));
        $order = array("\r\n", "\n", "\r");
        $replace = '';

// Processes \r\n's first so they aren't converted twice.
        $newstr = str_replace($order, $replace, $str);
        $json = json_encode($newstr);
        dd($json);

    }

    protected $_codeLength = 6;

    /**
     * Create new secret.
     * 16 characters, randomly chosen from the allowed base32 characters.
     *
     * @param int $secretLength
     * @return string
     */
    public function createSecret($secretLength = 16)
    {
        $validChars = $this->_getBase32LookupTable();
        unset($validChars[32]);

        $secret = '';
        for ($i = 0; $i < $secretLength; $i++) {
            $secret .= $validChars[array_rand($validChars)];
        }
        return $secret;
    }

    /**
     * Calculate the code, with given secret and point in time
     *
     * @param string $secret
     * @param int|null $timeSlice
     * @return string
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = $this->_base32Decode($secret);

        // Pack time into binary string
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);

        // Unpak binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, $this->_codeLength);
        return str_pad($value % $modulo, $this->_codeLength, '0', STR_PAD_LEFT);
    }

    /**
     * Get QR-Code URL for image, from google charts
     *
     * @param string $name
     * @param string $secret
     * @param string $title
     * @return string
     */
    public function getQRCodeGoogleUrl($name, $secret, $title = null)
    {
        $urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $secret . '');
        if (isset($title)) {
            $urlencoded .= urlencode('&issuer=' . urlencode($title));
        }
        return 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . $urlencoded . '';
    }

    /**
     * Check if the code is correct. This will accept codes starting from $discrepancy*30sec ago to $discrepancy*30sec from now
     *
     * @param string $secret
     * @param string $code
     * @param int $discrepancy This is the allowed time drift in 30 second units (8 means 4 minutes before or after)
     * @param int|null $currentTimeSlice time slice if we want use other that time()
     * @return bool
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
    {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($calculatedCode == $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the code length, should be >=6
     *
     * @param int $length
     * @return PHPGangsta_GoogleAuthenticator
     */
    public function setCodeLength($length)
    {
        $this->_codeLength = $length;
        return $this;
    }

    /**
     * Helper class to decode base32
     *
     * @param $secret
     * @return bool|string
     */
    protected function _base32Decode($secret)
    {
        if (empty($secret)) return '';

        $base32chars = $this->_getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);

        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) return false;
        for ($i = 0; $i < 4; $i++) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])
            ) return false;
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = "";
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = "";
            if (!in_array($secret[$i], $base32chars)) return false;
            for ($j = 0; $j < 8; $j++) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); $z++) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : "";
            }
        }
        return $binaryString;
    }

    /**
     * Helper class to encode base32
     *
     * @param string $secret
     * @param bool $padding
     * @return string
     */
    protected function _base32Encode($secret, $padding = true)
    {
        if (empty($secret)) return '';

        $base32chars = $this->_getBase32LookupTable();

        $secret = str_split($secret);
        $binaryString = "";
        for ($i = 0; $i < count($secret); $i++) {
            $binaryString .= str_pad(base_convert(ord($secret[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
        }
        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32 = "";
        $i = 0;
        while ($i < count($fiveBitBinaryArray)) {
            $base32 .= $base32chars[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
            $i++;
        }
        if ($padding && ($x = strlen($binaryString) % 40) != 0) {
            if ($x == 8) $base32 .= str_repeat($base32chars[32], 6);
            elseif ($x == 16) $base32 .= str_repeat($base32chars[32], 4);
            elseif ($x == 24) $base32 .= str_repeat($base32chars[32], 3);
            elseif ($x == 32) $base32 .= $base32chars[32];
        }
        return $base32;
    }

    /**
     * Get array with all 32 characters for decoding from/encoding to base32
     *
     * @return array
     */
    protected function _getBase32LookupTable()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
            '='  // padding char
        );
    }

    public function getGAuth()
    {
        $code = $this->getCode('ONBCPSCNAZV4B5RJ');
        dd($code);

    }

    public function updateItems()
    {

        $code = $this->getCode('ONBCPSCNAZV4B5RJ');
        $json = file_get_contents('https://bitskins.com/api/v1/get_all_item_prices/?api_key=4ca564b7-ff8d-44d9-908f-c002bd28fd3f&code=' . $code . '');
        $data = json_decode($json, true);
        $data = $data['prices'];

        foreach ($data as $item) {

            $check = \App\item::where('marketName', $item['market_hash_name'])->count();
            $itemPrice = $item['price'];
            $marketName = $item['market_hash_name'];
            $itemPrice = $itemPrice * 100;
            $itemPrice = intval($itemPrice);
            if ($check < 1) {
                if ($itemPrice != 0) {
                    $user = \App\item::create([
                        'marketName' => $marketName,
                        'avgPrice30Days' => $itemPrice,
                        'buyOrders' => '200000',
                        'sellOrders' => '200000',
                        'highestBuyOrder' => '200000',
                        'lowestSellOrder' => '200000'
                    ]);
                    //$dd = DB::insert('insert into items (marketName, avgPrice30Days) values (?, ?)', [$marketName, $itemPrice]);
                }
            } else {
                $update = \App\item::where('marketName', $marketName)->update([
                    'avgPrice30Days' => $itemPrice,
                    'buyOrders' => '200000',
                    'sellOrders' => '200000',
                    'highestBuyOrder' => '200000',
                    'lowestSellOrder' => '200000'
                ]);
            }

        }
        dd('done');
    }

    public function resetPlayers()
    {
        $users = \App\User::where('coins', '>', '500')->where('email_confirm', 0)->limit(500)->get();
        foreach ($users as $user) {
            \App\User::where('steamId64', $user->steamId64)->update(['coins' => 500]);
        }
    }

    public function confirmEmail($id)
    {
        $upate = \App\User::where('id', $id)->update(['email_confirm' => '1']);
        return Redirect::to('http://csgourban.com#confirm');
    }

}