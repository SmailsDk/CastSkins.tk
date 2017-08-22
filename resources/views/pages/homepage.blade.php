@extends('welcome')
@section('page_title') Roulette @stop

@section('content')

    <div class="col-lg-24 text-center roulette_top">
        <div id="last_bets">
        </div>
        <div id="timer"><span class="timerTime">32:000</span></div>
        <div id="diamond" class="fa fa-caret-down"></div>
        <img id="wheel" src="{{asset('img/circle.png')}}" alt="">
    </div>
    <div class="form-group">
        <div class="container">
            <div class="col-lg-24" style="    color: #fff000;">@if(Auth::check()) Your
                steamid64: {{Auth::user()->steamId64}} @endif</div>
            <div class="col-lg-24 roulette_beto">
                <button type="button" id="clearInput" class="btn btn-default betshort"
                        data-action="clear">Clear
                </button>
                <button type="button" onclick="plus(1)" class="btn btn-default betshort"
                        data-action="1">+1
                </button>
                <button type="button" onclick="plus(10)" class="btn btn-default betshort"
                        data-action="10">+10
                </button>
                <button type="button" onclick="plus(100)" class="btn btn-default betshort"
                        data-action="100">+100
                </button>
                <button type="button" onclick="plus(1000)" class="btn btn-default betshort"
                        data-action="1000">+1000
                </button>
                <button type="button" onclick="plus(10000)" class="btn btn-default betshort"
                        data-action="10000">+10000
                </button>
                <button type="button" onclick="razy(0.5)" class="btn btn-default betshort"
                        data-action="half"> 1/2
                </button>
                <button type="button" onclick="razy(2)" class="btn btn-default betshort"
                        data-action="double"> x2
                </button>
                <button type="button" onclick="razy('max')" class="btn btn-default betshort"
                        data-action="max">Max
                </button>
                <input class="form-control " value="0" id="betammount" type="text"
                       placeholder="Bet ammount">
            </div>
        </div>

    </div>
    <div class="col-lg-24 roulette_bets">
        <div class="container">

            <div class="col-md-6">

                <button class="place_bet red" onclick="placeBET($('#betammount').val(), 'red')">Red x2</button>
                <div class="user_bet">Your bet : <span class="user_red">0</span></div>
                <div class="user_bet">Total bet : <span id="redTotal" class="totalBet">0</span></div>

                <table class="table table-hover tableusers" style="border:0;">
                    <tbody id="redUsers" style="float: left;width: 100%;overflow:hidden;">

                    </tbody>
                </table>
            </div>
            <div class="col-md-6">

                <button class="place_bet purpleso" onclick="placeBET($('#betammount').val(), 'black')">Purple x2
                </button>
                <div class="user_bet">Your bet : <span class="user_black">0</span></div>
                <div class="user_bet">Total bet : <span id="blackTotal" class="totalBet">0</span></div>
                <table class="table table-hover tableusers" style="border:0;">
                    <tbody id="blackUsers" style="float: left;width: 100%;overflow:hidden;">

                    </tbody>
                </table>
            </div>
            <div class="col-md-6">

                <button class="place_bet green" onclick="placeBET($('#betammount').val(), 'green')">Green x8</button>
                <div class="user_bet">Your bet : <span class="user_green">0</span></div>
                <div class="user_bet">Total bet : <span id="greenTotal" class="totalBet">0</span></div>
                <table class="table table-hover tableusers" style="border:0;">
                    <tbody id="greenUsers" style="float: left;width: 100%;overflow:hidden;">

                    </tbody>
                </table>
            </div>
            <div class="col-md-6">

                <button class="place_bet gold" onclick="placeBET($('#betammount').val(), 'gold')">Gold x24</button>
                <div class="user_bet">Your bet : <span class="user_gold">0</span></div>
                <div class="user_bet">Total bet : <span id="goldTotal" class="totalBet">0</span></div>
                <table class="table table-hover tableusers" style="border:0;">
                    <tbody id="goldUsers" style="float: left;width: 100%;overflow:hidden;">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

@section('scripts_dif')
    <script src="{{asset('js/roulette.js')}}"></script>

@stop