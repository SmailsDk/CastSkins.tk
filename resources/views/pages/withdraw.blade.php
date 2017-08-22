@extends('welcome')
@section('page_title') Withdraw @stop

@section('content')
    <div class="container">
        @if(!Auth::check())
            <div id="withdraw">
                <h5>You need to be logged in</h5>
            </div>
        @else
            <style>
                button[disabled], html input[disabled] {
                    cursor: no-drop;
                    opacity: 0.5;
                }
            </style>
            <div id="withdraw">
                <h5 class="selected"><span>Selected amount:</span>
                    <div class="selected_am">0</div>
                    <i class="fa fa-diamond"></i></h5>
                <hr>
                <h5 class="selected"><span>Your current balance:</span>
                    <div class="">{{Auth::user()->coins}}</div>
                    <i class="fa fa-diamond"></i></h5>
                <hr>
                {{--<h5>1. To start withdrawing you need to bet a minimum 20000 diamonds all up (ON ROULETTE).</h5>--}}
                {{--<h5>1. You are allowed one withdraw per 24 hours, however can purchase 10 items per withdraw. Prevents Link to BOT'S Inventory: : <a href="http://steamcommunity.com/profiles/76561198295495923/inventory/">CLICK--}}
                        {{--HERE</a> </h5>--}}
                {{--<hr>--}}
                {{--<h5>Random last withdraws :</h5>--}}
                {{--<hr>--}}
                <button id="deposit_selected" class="button_red">Withdraw selected skins</button>
                <button id="unselect" class="button_red">Unselect all</button>
                <hr>
                <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24 no_padding">
                    <div id="inventory-skins">

                    </div>
                </div>

            </div>
        @endif
    </div>
@stop

@section('scripts_dif')
    <script src="{{asset('js/withdraw.js')}}"></script>
@stop