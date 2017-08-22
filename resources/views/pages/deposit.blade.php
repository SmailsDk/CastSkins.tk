@extends('welcome')
@section('page_title') Deposit @stop

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
                <button id="deposit_selected" class="button_red">Deposit selected skins</button>
                <button id="unselect" class="button_red">Unselect all</button>
                <hr>
                <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24 no_padding">
                    <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24 no_padding text-center" style="margin-bottom: 20px;">
                        <button style="float: none;display: inline-block;" class="button_red reloadInventory" userID="{{Auth::user()->steamId64}}">Get my inventory</button>
                    </div>
                    <div class="col-lg-24 col-md-24 col-xs-24 col-sm-24 no_padding text-center" style="margin-bottom: 20px;">
                        <h5 class="selected" style="text-align: center;width: 100%;">
                            <span style="float: none;display: inline-block;">Loaded inventory value:</span>
                            <div style="float: none;display: inline-block;" class="loaded_price">0</div>
                            <i style="float: none;display: inline-block;" class="fa fa-diamond"></i>
                            <span style="float: none;display: inline-block;"> which is equal to <b class="usd_val">0</b> USD</span>
                        </h5>
                    </div>
                    <div id="inventory-skins">
                    </div>
                </div>

            </div>
        @endif
    </div>
@stop

@section('scripts_dif')
    <script src="{{asset('js/deposit.js')}}"></script>
@stop