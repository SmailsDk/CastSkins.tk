<!doctype html>
<html lang="en">
<head>
    <title>Csgourban.com - CSGO Jackpot</title>
    <meta name="google-site-verification" content="sB91lwYO7Mr6TLkaIgdgH1GfJQjpPoKk80CFAr9URyE"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta name="google-site-verification" content="-Tzr4XhgI4tKIn8Za6wYVbBHIDLqCFB4So3QCAL_dLA"/>
    <meta name="keywords"
          content="jackpot,csgo,cs:go,csobx.pl,csgourban,urban,csbox,counter strike, counter strike globall ofensive, unarmed bandit, slots, coins, roulette, ruletka, ruletki, double, dabl, csgo slots, csgo bandit">
    <meta property="og:title" content="CSGOURBAN JACKPOT!"/>
    <meta property="og:image" content="{{ asset('images/fbshare2.jpg') }}"/>
    <meta property="og:url" content="http://csgourban.com"/>
    <meta property="fb:app_id" content="1124368127594463"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description"
          content="A website where Players deposit skins, once a pre-defined threshold is reached a depositer will be choosen, with odds based on the values of it's deposited skin, and awarded with ALL the skins in the pool.">
    <meta name="og:description"
          content="A website where Players deposit skins, once a pre-defined threshold is reached a depositer will be choosen, with odds based on the values of it's deposited skin, and awarded with ALL the skins in the pool.">

    <link rel="shortcut icon" href="{{ asset('img/urban.png') }}?1"/>
    @include('usable.links')
    @yield('links_dif')
</head>
<body>
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}"/>

@include('partials.mobile_navi')
@include('partials.navigation')
@include('partials.recents')
<div class="col-lg-20 no_padding maincon" style="position: relative;">
    @include('partials.bottom_navi')
    <div class="breadcrumbs">
        <a href="{{route('index')}}">Homepage</a><a href="javascript:;">@yield('page_title')</a>
    </div>

    <div id="content_content">

        <div class="contento">
            <div class="globalmessage" style="    width: 100%;
    padding: 10px;float: left;
    background: #49161c;
    margin-bottom: 20px;
    color: #fff;
    text-align: center;display: none;">
            </div>
            @yield('content')
            <canvas id="demo-canvas" style="
               left: 0;bottom: 190px;z-index: -1;
    position: absolute;"></canvas>
            @include('partials.footer')
        </div>
    </div>
</div>
<audio src="{{asset('sounds/start_r_1.wav')}}" id="start_roll"></audio>
<audio src="{{asset('sounds/stop_r.mp3')}}" id="stop_roll"></audio>

<div class="remodal" data-remodal-id="modal">
    <button data-remodal-action="close" class="remodal-close"></button>
    <h1>Choose days for ban</h1>
    <input type="hidden" value="" id="banthisplayer">
    <p>
        <input type="text" id="howmuchdays" placeholder="0">
    </p>
    <br>
    <button data-remodal-action="cancel" class="remodal-cancel">SAFE</button>
    <button data-remodal-action="confirm" onclick="BanPlayer();" class="remodal-confirm">BAN</button>
</div>
<div class="remodal" data-remodal-id="gift">
    <button data-remodal-action="close" class="remodal-close"></button>
    <h4>Send gift to your friend</h4>
    <p>
        <input type="text" class="form-control" style="color:#333;" id="howmuchcoins" placeholder="Coins ammout">
    </p>
    <p>
        <input type="text" class="form-control" style="color:#333;" id="tosteamid" placeholder="Friend steamid64">
    </p>
    <br>
    <button onclick="sendCoins();" class="remodal-confirm">SEND</button>
</div>

<div class="remodal text-left" data-remodal-id="freecoins">
    <button data-remodal-action="close" class="remodal-close"></button>
    @if(Auth::check())
        <h4>Hi {{Auth::user()->nick}}</h4>
        <hr>
        <h3>Your unique refferal code is : <b>{{Auth::user()->refcode}}</b></h3>
        <hr>
        <form action="" method="POST" id="freecoinsCode">
            <input required class="col-lg-10 no_padding" type="text" name="coinscode"
                   placeholder="Type recived code here">
            <button type="submit">Submit code</button>
        </form>
        <hr>
        People that used your ref code: {{$checkUserUsedYourCode}} <br>
        Coins that you earned: {{($checkUserUsedYourCode)*50}}
        <hr>


        <h5>How do I get free diamonds on csgourban.com?</h5>
        <ul>
            <li>Log onto the site.</li>
            <li>Copy your unique code and send it out to your friends or the world. By doing so you will be earning
                points.
            </li>
            <li>By typing in the code you will get 500 free points and the person that gave you the code will get 50
                points.
            </li>
        </ul>

    @else
        <h4>Please sign in</h4>
    @endif
</div>
<div class="remodal text-left" data-remodal-id="contact">
    @if(Auth::check())
        <h4>Contact</h4>
        <p>If you want to contact us just write us an email, <br> we will respond as soon as possible but up to 72
            working hours.</p>
        <hr>
        Partership/Youtubers/Streamers : <a href="mailto:partnership@csgourban.com">partnership@csgourban.com</a>
        <hr>
        Technical/Admin : <a href="mailto:admin@csgourban.com">admin@csgourban.com</a>
    @else
        <h4>Please sign in</h4>
    @endif
</div>
<div class="remodal text-left" data-remodal-id="faq">
    @if(Auth::check())
        <B>FAQ:</B>
        <br>
        <br>
        <B>1. Why should you choose CSGOUrban instead of other gambling sites?</B><br>
        - We do NOT take commission on withdrawing items from our site.<br>
        - We have highly qualified staff that can help you any time of day and night<br><br>

        <b>2. How do you login?</b><br>
        - You can login by clicking on the green "Sign in Through Steam" button in the top-right corner of the website.
        <br><br>

        <B>3. How do you contact staff members?</B><br>
        - You can contact support by emailing us at admin@csgourban.com<br>
        - If you are looking to be partners or you are a Youtuber/Streamer, contact us here:partnership@csgourban.com
        <br><br>

        <b> 4. I can't withdraw. Why?</b><br>
        - There may be four possible solutions to this problem:<br>
        · To start withdrawing you need to deposit min 5000 diamonds ($5) on our site.<br>
        · To start withdrawing you need to bet min 50000 diamonds all up.<br>
        · You can withdraw only once per 24 hours. However, you may withdraw 10 items at a time.<br>
        · You didn't set your trade URL or your account is suspended.<br><br>

        If you didn't find your solution here, please double check, then you may contact us.<br><br>

        <b> 5. Can I get my coins or skins refunded?</b><br>
        - Firstly, we DO NOT REFUND ANYTHING. Everything you're doing with your coins, skins or account is YOUR OWN
        RESPONSIBILITY.<br>
        - If our service had a bug please contact us through contact tab. Every email sent will be replied to in a
        maximum 72 hours.<br>
        - Every skin you deposited you confirmed with Steam Guard Mobile Authenticator therefore it is your fault if you
        lose them whilst gambling.<br><br>

        <b>6. How many diamonds do I receive for my skins?</b><br><br>
        - The conversion is $1 = 1000 diamond. Therefore $10 = 10000 diamonds and $100 = 100000.<br>
    @else
        <h4>Please sign in</h4>
    @endif
</div>
<div class="remodal text-left" data-remodal-id="confirm">
    Your email has been confirmed.
</div>
<div class="remodal text-left" data-remodal-id="please">
    Please confirm your email first.
</div>
<div class="remodal text-left" data-remodal-id="thanks">
    Thanks for supporting us! You can see your order status below.
</div>
<div class="remodal text-left" data-remodal-id="ups">
    Ups, something went wrong with your payment. Please try again.
</div>
<div class="remodal text-left" data-remodal-id="rules">
    @if(Auth::check())
        {{--<h4 style="color:Red;">We are closed, please do not send any items to bot!</h4>--}}
        <h4>Terms of Service</h4>
        <hr>
        <b>Terms of use</b>
        <br><br>
        Usage of our site, CSGOURBAN.COM and its services constitute your acceptance of this agreement. If you do not
        agree with these terms of service please leave the site immediately.
        <br><br>
        You must be 18 years of age to use this site and its services.
        <br><br>
        <b>Item prices</b>
        <br>
        Items on the site may not always be market value at the time of the transaction as we update price data every
        six hours. Usage of CSGOURBAN.COM implies that you fully accept our prices and understand there will be no
        refunds for any price issue.
        In the case of an item being overvalued we reserve the right to adjust the credits of your account to fix the
        error. Pricing data provided by SteamAPI.
        <br><br>
        {{--<B>Jackpot fees and winnings</B>--}}
        {{--<br>--}}
        {{--CSGOURBAN.COM takes approximately 10% of the total value of each jackpot round. If u do have CSGOURBAN.COM in--}}
        {{--your steam name the site takes approximately 5% of the total value of each jackpot round.<br>--}}
        {{--All winnings from jackpot will be sent to the trade URL provided by the user.<br>--}}
        {{--CSGOURBAN.COM reserves the right to keep any items if the following happens:<br><br>--}}
        {{--- You do not have space in your inventory to accept your winnings.<br>--}}
        {{--- You did not give your Trade Url or fed Trade Url was wrong at the time of sending the offer.<br>--}}
        {{--- You did not accept your winnings within 2 hours of sending the offer.<br>--}}
        {{--- You have declined/modified the offer of the winning.<br>--}}
        {{--- Any attempt to change the offer shall be deemed to be a scam and deprive the right to receive items with a--}}
        {{--winning round<br>--}}
        {{--- The user does not meet the basic criteria regarding the coming of age<br><br>--}}

        <b>Winnings from roulette or coin flip</b><br><br>

        All winnings will be transferred to your account on CSGOURBAN.COM.<br>
        All winnings in your CSGOURBAN.COM account can be stored without any activity indefinitely.<br>
        You can withdraw items to your steam account using the withdrawal feature if you have met the requirements to
        withdraw.<br><br>


        CSGOURBAN.COM reserves the right to keep any items if the following happens:<br>
        - You do not have space in your inventory to accept your winnings.<br>
        - You did not give your Trade Url or fed Trade Url was wrong at the time of sending the offer.<br>
        - You did not accept your winnings within 2 hours of sending the offer.<br>
        - You have declined/modified the offer of the winning.<br>
        - Any attempt to change the offer shall be deemed to be a scam and deprive the right to receive items with a
        winning round<br>
        - The user does not meet the basic criteria regarding the coming of age.<br><br>

        <b>Withdraw</b><br><br>

        To withdraw from CSGOURBAN.COM you must have bet at least 50000 diamonds and deposited at least items worth 5000
        diamonds. Users are forbidden from using CSGOURBAN.COM as a trading platform.
        <br>You can withdraw only CS:GO skins, we do not pay real money.
        <br><br>
        <b>Extra Coins</b><br>
        If you don't have a CS:GO skins you can buy an extra coins, all you need to do is go to ,,BUY DIAMONDS,,
        bookmark
        and folow the orders.
        <br><br>
        <b>Chat Rules</b>
        <br>
        <br>
        CHAT IS IN ENGLISH, YOU MIGHT BE BLOCKED IF YOU WRITE IN OTHER LANGUAGE <br>
        Chatting prevailing general principles of culture and spelling when an administrator or moderator has objections
        to any user has the possibility to block his chat function.<br><br>

        You can be banned/timed out for the following reasons:<br>
        - Deliberate misrepresentation of other players (malicious give false information).<br>
        - Insults, humiliation, slander, etc. other players.<br>
        - Bypassing censorship and block links (vulgarity spaces, dots, stars and everything that is used to bypass the
        lock).<br>
        - Advertising other portals, as well as for games.<br>
        - Abuse of punctuation ("?;!;."&^$(#@%).<br>
        - Spam, flooding, trolling.<br>
        - Begging for items other users.<br><br>

        <b>Service</b><br>

        CSGOURBAN.COM reserves the right, without prior notice, to permanently or temporarily terminate or suspend your
        access to the service for malicious or fraudulent activity. Each user is only allowed one account at
        CSGOURBAN.COM and must not abuse the affiliate system in any way.
        <br><br>
        CSGOURBAN.COM bots will never send you friend requests.
        <br><br>
        <b>Refunds</b>
        <br><br>
        CSGOURBAN.COM does not offer refunds. All bets are final and you play at your own risk. If you have a problem
        with a bet or item transaction, please open a support ticket.
        <br><br>
        <b>Limited Liability</b>
        <br><br>
        CSGOURBAN.COM is not responsible for trade/account bans that may occur as a resulting of accepting items from
        our bots.
        <br><br>
        CSGOURBAN.COM assumes no responsibility for missed bets as a result of network latency or disconnections. Always
        ensure a stable connection before placing bets. Avoid placing important bets at the last second.
        <br><br>
        <b>Variation</b>
        <br><br>
        We reserve the right to alter our terms of service at any time. Revised terms of service will apply from the
        date of publication on this site.
        <br><br>
        <b>Copyright</b>
        <br><br>
        It is prohibited to copy, redistribute, publish, distribute, share or otherwise use all or part of the data
        contained on this website CSGOURBAN.COM for commercial purposes and by integrating them into websites.<br><br>
        <b>Others</b>
        <br><br>
        Use of page faults is rewarded with a ban and total loss of points called diamonds.. <br><br>
        Ignorance of the rules does not exempt from the observance of the rules.
        <br><br><br>
        <b>Additional Terms and Conditions; EULAs</b> <br><br>
        When you use G2A Pay services provided by G2A.COM Limited (hereinafter referred to as the "G2A Pay services provider") to make a purchase on our website, responsibility over your purchase will first be transferred to G2A.COM Limited before it is delivered to you. G2A Pay services provider assumes primary responsibility, with our assistance, for payment and payment related customer support. The terms between G2A Pay services provider and customers who utilize services of G2A Pay are governed by separate agreements and are not subject to the Terms on this website.
        <br><br>
        With respect to customers making purchases through G2A Pay services provider checkout, (i) the Privacy Policy of G2A Pay services provider shall apply to all payments and should be reviewed before making any purchase, and (ii) the G2A Pay services provider Refund Policy shall apply to all payments unless notice is expressly provided by the relevant supplier to buyers in advance. In addition the purchase of certain products may also require shoppers to agree to one or more End-User License Agreements (or "EULAs") that may include additional terms set by the product supplier rather than by Us or G2A Pay services provider. You will be bound by any EULA that you agree to.
        <br><br>
        We and/or entities that sell products on our website by using G2A Pay services are primarily responsible for warranty, maintenance, technical or product support services for those Products. We and/or entities that sell products on our website are primarily responsible to users for any liabilities related to fulfillment of orders, and EULAs entered into by the End-User Customer. G2A Pay services provider is primarily responsible for facilitating your payment.
        <br><br>
        You are responsible for any fees, taxes or other costs associated with the purchase and delivery of your items resulting from charges imposed by your relationship with payment services providers or the duties and taxes imposed by your local customs officials or other regulatory body.
        <br><br>
        For customer service inquiries or disputes, You may contact us by email at support@XXXXX.com.
        <br><br>
        Questions related to payments made through G2A Pay services provider payment should be addressed to support@g2a.com.
        <br><br>
        Where possible, we will work with You and/or any user selling on our website, to resolve any disputes arising from your purchase.
        <br><br><br><br>
        <font color="red">SITE IS CLOSED, DO NOT DEPOSIT ANY SKINS!</font>
    @else
        <h4>Please sign in</h4>
    @endif
</div>

@include('usable.scripts')
@yield('scripts_dif')

</body>
</html>
