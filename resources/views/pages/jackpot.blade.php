@extends('welcome')
@section('page_title') Jackpot @stop

@section('content')

    <div class="col-lg-24 text-center">
        <div class="col-lg-6 col-md-6 col-xs-24 col-sm-12 color_box">
            <div class="box_inside green">
                <div class="left"><i class="fa fa-puzzle-piece"></i></div>
                <div class="right">
                    <div class="title">ALL GAMES</div>
                    <div class="subtitle" id="allgames">0</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-24 col-sm-12 color_box">
            <div class="box_inside purple">
                <div class="left"><i class="fa fa-university"></i></div>
                <div class="right">
                    <div class="title">ITEMS IN POT</div>
                    <div class="subtitle"><span  id="potCount">0</span>/120</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-24 col-sm-12 color_box">
            <div class="box_inside gold">
                <div class="left"><i class="fa fa-money"></i></div>
                <div class="right">
                    <div class="title">POT VALUE</div>
                    <div class="subtitle" id="potPrice">$0.00</div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-xs-24 col-sm-12 color_box">
            <div class="box_inside">
                <div class="left"><i class="fa fa-clock-o"></i></div>
                <div class="right">
                    <div class="title">TIMELEFT</div>
                    <div class="subtitle" id="timer">125 SECONDS</div>
                </div>
            </div>
        </div>
        <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24 color_box ">
            <div class="box_inside drawAnnimation green">
                <i class="fa fa-caret-down roller_ticker"></i>
                <div class="inside_anim">

                </div>
            </div>

        </div>

        <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24">
            <a target="_blank" style="    width: 100%;
    float: left;
    height: 40px;
    font-size: 20px;
    color: #ed2700;
    line-height: 40px;
    background: #1e2129;
    text-decoration: none !Important;
    font-weight: bold;" href="https://steamcommunity.com/tradeoffer/new/?partner=63079740&token=pAyWy4v6">Join the Current Jackpot <span style="font-size:12px;">Minimum Deposit of $0.01 - Maximum 40 Items</span></a>
        </div>
        <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24 color_box jackpotPeople">
            <div id="noItems">
                <h4>Awaiting Players</h4>
            </div>
            <div class="box_inside user_scroller">
                <div class="usersInPot">

                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-7 col-xs-24 col-sm-24 last_winner centralize">
            <div class="col-lg-24 winner_row last_winnero">
                <img src="{{asset('img/crown.png')}}" width="20" alt="">
                <h4 class="winnerName">
                    {{str_limit($lastWinnerData[0]->nick,20)}}<br>
                    Won <b>${{$lastWinner[0]->potPrice/100}}</b> with <b>{{round($lastWinner[0]['userPutInPrice'] / $lastWinner[0]['potPrice'] * 100)}}%</b> chance.
                </h4>
            </div>
        </div>
        <div class="col-lg-5 col-md-5 col-xs-24 col-sm-24 last_winner centralize">
            <div class="col-lg-24 winner_row">
                <img src="{{asset('img/sticker2.png')}}" style="position:relative;    top: -10px;" width="20" alt="">
                <h4 class="winnerName">
                    Games today:<br>
                    <b>{{$todayPlayed}}</b>
                </h4>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-24 col-sm-24 last_winner text-left centralize">
            <div class="col-lg-24 winner_row ">
                BOT STATUS : <div id="status">Awaiting Offers</div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-24 col-sm-24 last_winner text-left centralize" >
            <div class="col-lg-24 winner_row ">
                TRADE STATUS :
                <div id="tstatus">Now you can send offers to the BOT</div>
            </div>
        </div>

    </div>


@stop
@if(Auth::check())
    <script>window.userID = '{{Auth::user()->steamId64}}'</script>
@endif
@section('scripts_dif')
    <script src="{{asset('js/jackpot.js')}}"></script>
@stop
@include('templates.itemsInPot')
@include('templates.live_round')