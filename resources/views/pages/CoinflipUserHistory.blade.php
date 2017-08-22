@extends('welcome')
@section('page_title') Coinflip History @stop

@section('content')
    <div class="container coinflip">
        <h5>Your coinflip history:</h5>
        <br>
        @if($getWinner->total() > 0)
            <h5>You played <b>{{$getWinner->total()}}</b> games. Thank you!</h5>
            <table class="table user_history_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Left Side</th>
                    <th>Right Side</th>
                    <th>Your Side</th>
                    <th>Room value</th>
                    <th>Winning Side</th>
                </tr>
                </thead>
                <tbody>
                @foreach($getWinner as $rou)
                    <tr>
                        <td>{{$rou->id}}</td>
                        <td>@if($rou->left_side == 'tt') <img src="{{asset('images/tt.png')}}" width="30" alt=""> @elseif($rou->left_side == 'ct') <img src="{{asset('images/ct.png')}}" width="30" alt=""> @else Game not ended @endif</td>
                        <td>@if($rou->right_side == 'tt') <img src="{{asset('images/tt.png')}}" width="30" alt=""> @elseif($rou->right_side == 'ct') <img src="{{asset('images/ct.png')}}" width="30" alt=""> @else Game not ended @endif</td>
                        <td>
                            @if($rou->left == Auth::user()->steamId64)
                                @if($rou->left_side == 'tt') <img src="{{asset('images/tt.png')}}" width="30" alt=""> @else <img src="{{asset('images/ct.png')}}" width="30" alt=""> @endif
                            @elseif($rou->right == Auth::user()->steamId64)
                                @if($rou->right_side == 'tt') <img src="{{asset('images/tt.png')}}" width="30" alt=""> @else <img src="{{asset('images/ct.png')}}" width="30" alt=""> @endif
                            @endif
                        </td>
                        <td>2x{{$rou->ammount}}</td>
                        <td>@if($rou->won == 'tt') <img src="{{asset('images/tt.png')}}" width="30" alt=""> @elseif($rou->right_side == 'ct') <img src="{{asset('images/ct.png')}}" width="30" alt=""> @else Game not ended @endif</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            {{$getWinner->links()}}
        @else
            You not placed any bet yet? Go to coinflip!
        @endif
    </div>
@stop

@section('scripts_dif')

@stop