@extends('welcome')
@section('page_title') User Settings @stop

@section('content')


    <div class="container user_settings coinflip">
        @if(Auth::check())
            <h4>User Settings</h4>
            <hr>
            <h5>Trade URL (<font color="red">Required</font>) (<a target="_blank" href="http://steamcommunity.com/id/me/tradeoffers/privacy">What is my
                    tradelink?</a>):</h5>
            <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
                <input id="tradelink" type="text" class="form-control col-lg-20 col-md-20 col-sm-20 col-xs-20"
                       placeholder="Your tradelink" value="{{Auth::user()->tradeURL}}">
                <button class="submit button_red saveTrade">Save</button>
            </div>
            <hr>
            <hr>
            <h5>Email adress (<font color="red">Required</font>):</h5>
            <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
                <input id="email" type="email" class="form-control col-lg-20 col-md-20 col-sm-20 col-xs-20"
                       placeholder="Your email" value="@if(Auth::user()->email) {{Auth::user()->email}} @endif">
                <button class="submit button_red saveEmail">Save</button>
            </div>
            <hr>
            <h5>
                <h4>Sound settings :</h4>
                <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
                    <input type="checkbox" id="win_sound" name="win_sound" class="make-switch" checked>
                </div>
            </h5>
        @else
            <h4>You need to be logged in</h4>
        @endif
    </div>

@stop
@if(Auth::check())
    <script>window.userID = '{{Auth::user()->steamId64}}'</script>
@endif
@section('scripts_dif')
    <script src="{{asset('js/settings.js')}}"></script>
@stop