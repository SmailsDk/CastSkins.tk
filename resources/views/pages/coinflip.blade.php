@extends('welcome')
@section('page_title') Coinflip @stop

@section('content')

    <div class="container coinflip">
        <div class="form-group">
            <div class="col-lg-24 no_padding roulette_beto">
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
        <hr>
        <h4>Select side : </h4>
        <div class="col-lg-24 no_padding roulette_beto">
        <input type="hidden" id="choosen_fr">
        <div id="choose_tt" class="choose_fr"></div>
        <div id="choose_ct" class="choose_fr"></div>
        </div>
        @if(Auth::check())
            <hr>
            <button class="button_red" id="create_room">Create room</button>
        @endif
        <hr>
        <a class="button_red" href="{{route('coinflip')}}">REFRESH ROOMS</a>
        @if(Auth::check())
            <div class="col-lg-24 no_padding roulette_beto">
            <h4>Rooms you are in: </h4>
            </div>
            <table class="table">
                @if($roomsYours != '0')
                    @foreach($roomsYours as $rmd)
                        @if($rmd->won == '0')
                            <tr style="vertical-align: middle">
                                <td style="vertical-align: middle">#{{$rmd->id}}</td>
                                <td style="vertical-align: middle">{{$rmd->name}}</td>
                                <td style="vertical-align: middle"><img width="20"
                                                                        src="{{asset('images/'.$rmd->left_side.'.png')}}"
                                                                        alt=""></td>
                                <td style="vertical-align: middle">{{$rmd->ammount}} coins to join (Win x2)</td>
                                <td style="vertical-align: middle">{{$rmd->created_at}}</td>
                                <td style="vertical-align: middle;">
                                    <a href="/room/{{$rmd->id}}"
                                       style="margin-top: 5px;margin-bottom: 5px;"
                                       class="btn btn-success btn-sm joinroom">Join
                                        room
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif

            </table>
        @endif
        <hr>
        <div class="col-lg-24 no_padding roulette_beto">
        <h4>Active rooms : </h4>
        </div>
        <table class="table">
            @if($rooms != '0')
                @foreach($rooms as $room)
                    @if($room->right == 0)
                        <tr style="vertical-align: middle">
                            <td style="vertical-align: middle">#{{$room->id}}</td>
                            <td style="vertical-align: middle">{{$room->name}}</td>
                            <td style="vertical-align: middle"><img width="20"
                                                                    src="{{asset('images/'.$room->left_side.'.png')}}"
                                                                    alt=""></td>
                            <td style="vertical-align: middle">{{$room->ammount}} coins to join (Win x2)</td>
                            <td style="vertical-align: middle">{{$room->created_at}}</td>
                            <td style="vertical-align: middle;">
                                @if(Auth::check()) <div style="margin-top: 5px;margin-bottom: 5px;"
                                                        class="btn btn-success btn-sm joinroom" id="{{$room->id}}">Join
                                    room
                                </div>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </table>
    </div>
    </div>
@stop

@section('scripts_dif')
    <script src="{{ asset('js/coinflip.js') }}"
            type="text/javascript"></script>
@stop