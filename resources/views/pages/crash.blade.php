@extends('welcome')
@section('page_title') Crash @stop

@section('content')
    <style>
        #crashCounter.white {
            color: white;
        }

        #crashCounter {
            font-weight: bold;
            font-size: 106px;
        }

        #nextGamein {
            font-size: 20px;
            color: white;
        }

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: middle;
            border-top: 1px solid #dddddd;
            height: 30px;
            border-color: #ED2700;
        }

        .bust_table tr {
            height: 30px;
            border-color: #ED2700;
        }

        .bust_table {
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid #ED2700;
            color: white;
        }

        .table-hover > tbody > tr:hover {
            background-color: transparent;
        }

        .bust_buttons button:last-of-type, .bust_buttons button:first-of-type {
            background: rgba(0, 0, 0, 0.2);
        }

        .bust_buttons button {
            border-radius: 0;
            color: #fff;
            margin-right: 0;
            float: none;
            display: inline-block;
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid #ED2700;
            outline: none;
            text-transform: uppercase;
            height: 33px;
            margin-bottom: 10px;
        }

        .bust_buttons .place_bet {
            border-radius: 0;
            color: #fff;
            margin-right: 0;
            float: none;
            display: inline-block;
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid #ED2700;
            outline: none;
            text-transform: uppercase;
            height: 46px;
            margin-bottom: 10px;
            width: 390px;
            margin-top: 50px;
            max-width: 100%;
        }

        .bust_buttons.last_button {
            margin-bottom: 100px;
        }

        .bust_buttons label {
            width: 390px;
            color: white;
            float: none;
            display: inline-block;
            max-width: 100%;
            margin-bottom: 0;
        }
    </style>
    <div class="col-lg-12 text-center">
        <div class="col-lg-24 text-center">
            <div class="white" style="color:white;">Last rounds:

                <div style="    float: none;
    display: inline-block;" class="" id="lastBusts"></div>
            </div>
            <div id="crashCounter" class="white">x1.00</div>
            <div id="nextGamein" class="white" style="opacity: 0;">Next game in 10 seconds</div>
        </div>
        <div class="col-lg-24 text-center bust_buttons">
            <div class="col-lg-24 bust_buttons">
                @if(Auth::check())
                    <button type="button" class="place_bet placeBetNow" onclick="placeBet($('#bet_amount').val())">Place bet
                    </button>
                    <button type="button" class="cashout place_bet" onclick="cashOut()" style="display: none;">Cash out
                    </button>
                @else
                    <button type="button" class="place_bet" onclick="">You need to be logged in</button>
                @endif
            </div>
            <div class="col-lg-24 bust_buttons">
                <button type="button" onclick="plus('clear')">Clear</button>
                <button type="button" onclick="plus(1)">+1</button>
                <button type="button" onclick="plus(200)">+200</button>
                <button type="button" onclick="plus(1000)">+1000</button>
                <button type="button" onclick="plus(10000)">+10000</button>
                <button type="button" onclick="razy(0.5)">1/2</button>
                <button type="button" onclick="razy(2)">x2</button>
                <button type="button" onclick="razy('max')">Max</button>
            </div>
            <div class="col-lg-24 bust_buttons">
                <div class="col-lg-24 no_padding text-center">
                    <label for="bet_amount" class="text-left">Bet:</label>
                </div>
                <input type="text" class="place_bet" style="margin-top: 10px;" id="bet_amount" placeholder="Bet amount"
                       value="0">
            </div>
            <div class="col-lg-24 bust_buttons last_button">
                <div class="col-lg-24 no_padding text-center">
                    <label for="auto_cashout" class="text-left">Auto cashout:</label>
                </div>
                <input type="text" class="place_bet" style="margin-top: 10px;" id="auto_cashout" placeholder="None"
                       value="0">
            </div>
        </div>
    </div>
    <div class="col-lg-12 text-left">
        <div class="col-lg-24 crash_bets">
            <table class="table table-hover bust_table">
                <thead>
                <tr>
                    <th>USERNAME</th>
                    <th>@</th>
                    <th>BET</th>
                    <th>PROFIT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Waiting for players</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('scripts_dif')
    <script src="{{asset('js/crash.js')}}"></script>
@stop