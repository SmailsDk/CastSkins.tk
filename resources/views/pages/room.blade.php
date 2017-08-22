@extends('welcome')
@section('page_title') Room #{{$room->id}} @stop

@section('content')

    <div class="container coinflip">
        @if(!Auth::check())
            <h4>YOU NEED TO LOG IN</h4>
        @else
            <div class="col-lg-24 col-xs-24 col-md-24 col-sm-24 no_padding">
                <a class="button_red" href="{{route('coinflip')}}">Back to room list</a>
            </div>
            <hr>
            <input type="hidden" id="roomID" value="{{$room->id}}">
            <div class="col-lg-24 col-xs-24 col-md-24 col-sm-24 no_padding">
            <h4>Round ID : #{{$room->id}}</h4>
            </div>
            <hr>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-center">
                <div class="user_box">
                    <img class="img-circle" width="100" src="{{$userLeft->avatar}}" alt="">
                    <img class="img-circle" width="30"
                         src="{{asset('images/'.$room->left_side.'.png')}}" alt="">
                    <h5><b>{{$userLeft->nick}}</b></h5>
                    @if($room->left_active == 1)
                        <h5 style="width: 100%;text-align: center;">
                            <div style="float: none;display: inline-block;" class="button_red">READY</div>
                        </h5>
                    @else
                        @if($room->left == Auth::user()->steamId64)
                            <h5 style="width: 100%;text-align: center;">
                                <div style="float: none;display: inline-block;" class="button_red left_active"
                                     id="setActive">NOT READY
                                </div>
                            </h5>
                        @else
                            <h5 style="width: 100%;text-align: center;">
                                <div style="float: none;display: inline-block;" class="button_red left_active">
                                    NOT READY
                                </div>
                            </h5>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-center">
                <img class="img-circle" id="rolledFlip" width="100"
                     src="https://steamdb.info/static/img/default.jpg"
                     alt="">
                <h5><b><i class="fa fa-clock-o"></i> <span id="timetoGo">0</span>/10 seconds</b></h5>
                @if($userRight == '0')
                    <h5><b>WAITING FOR PLAYER</b></h5>
                @else
                    <h5 id="room_status"><b>WAITING FOR ACCEPT</b></h5>
                @endif
                <h5><b>{{$room->ammount*2}} COINS</b></h5>
            </div>
            @if($userRight == '0')
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-center">
                    <div class="user_box">
                        <img id="rightSide" class="img-circle" width="30"
                             src="https://steamdb.info/static/img/default.jpg" alt="">
                        <img id="rightAvatar" class="img-circle" width="100"
                             src="https://steamdb.info/static/img/default.jpg" alt="">
                        <h5><b id="rightNick">WAITING FOR PLAYER</b></h5>
                        <h5 id="rightActivate">

                        </h5>
                    </div>
                </div>
            @else
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-center">
                    <div class="user_box">
                        <img id="rightSide" class="img-circle" width="30"
                             src="{{asset('images/'.$room->right_side.'.png')}}" alt="">
                        <img id="rightAvatar" class="img-circle" width="100" src="{{$userRight->avatar}}"
                             alt="">
                        <h5><b id="rightNick">{{$userRight->nick}}</b></h5>
                        @if($room->right_active == 1)
                            <h5 style="width: 100%;text-align: center;">
                                <div style="float: none;display: inline-block;" class="button_red">READY</div>
                            </h5>
                        @else
                            @if($room->right == Auth::user()->steamId64)
                                <h5 style="width: 100%;text-align: center;">
                                    <div style="float: none;display: inline-block;"
                                         class="button_red right_active" id="setActive">NOT READY
                                    </div>
                                </h5>
                            @else
                                <h5 style="width: 100%;text-align: center;">
                                    <div style="float: none;display: inline-block;"
                                         class="button_red right_active">NOT READY
                                    </div>
                                </h5>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
            <hr>
        @endif
    </div>
@stop

@section('scripts_dif')
    @if(!Auth::check())
        YOU NEED TO LOG IN
    @else
        <script src="{{ asset('js/coinflip.js') }}"
                type="text/javascript"></script>
        <script>
            setInterval(function () {
                $.ajax({
                    url: '/checkRoom/' + $('#roomID').val() + '',
                    method: 'get',
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        if (response['time'] < 11) {
                            $('#timetoGo').html(response['time']);
                            $('#room_status').hide();
                        } else {
                            $('#room_status').show();
                            if (response['won'] != 0) {
                                $('#room_status').html('<b>Round finished</b>');
                            }
                            $('#timetoGo').html('10');
                        }
                        if (response['won'] != 0) {
                            $('#rolledFlip').attr('src', '../images/' + response['won'] + '.png');
                            $('#rolledFlip').addClass('animated flip');
                        }
                        if (response['right_active'] == 1) {
                            $('.right_active').removeClass('btn-danger').addClass('btn-success').html('READY');
                        } else {
                            $('.right_active').addClass('btn-danger').removeClass('btn-success').html('NOT READY');
                        }
                        if (response['left_active'] == 1) {
                            $('.left_active').removeClass('btn-danger').addClass('btn-success').html('READY');
                        } else {
                            $('.left_active').removeClass('btn-success').addClass('btn-danger').html('NOT READY');
                        }
                        if (response['rightAvatar'] != '0') {
                            $('#rightAvatar').attr('src', response['rightAvatar']);
                            $('#rightNick').html(response['rightName']);
                        }
                        if (response['rightSide'] != '0') {
                            $('#rightSide').attr('src', '../images/' + response['rightSide'] + '.png');
                        } else {
                            $('#rightSide').attr('src', 'https://steamdb.info/static/img/default.jpg');
                        }

                        if (response['rightID'] == '<?php echo Auth::user()->steamId64 ?>') {
                            $('#rightActivate').html('<div class="button_red" id="setActive">NOT READY</div>');
                            if (response['right_active'] == 1) {
                                $('.right_active').removeClass('btn-danger').addClass('btn-success').html('READY');
                            } else {
                                $('.right_active').addClass('btn-danger').removeClass('btn-success').html('NOT READY');
                            }
                            $('#setActive').on('click', function () {
                                var roomID = $(this).attr('id');
                                $.ajax({
                                    url: '/setActive',
                                    method: 'post',
                                    dataType: 'json',
                                    data: {
                                        roomID: $('#roomID').val()
                                    },
                                    success: function (response) {

                                    }
                                });
                            })
                        }
                    }
                });
            }, 1000);
        </script>
    @endif
@stop