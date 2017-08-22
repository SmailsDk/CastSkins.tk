@extends('welcome')
@section('page_title') Roulette History @stop

@section('content')
    <div class="container coinflip">
        <h5>Your roulette history:</h5>
        @if($getWinner->total() > 0)
            <h5>You played <b>{{$getWinner->total()}}</b> games. Thank you!</h5>
            <table class="table user_history_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Placed</th>
                    <th>Round Color</th>
                    <th>Profit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($getWinner as $rou)
                    <tr>
                        <td>{{$rou->gameID}}</td>
                        <td>@if($rou->color == 'purple')
                                <div class="pull-left img-circle" style="width: 20px;height: 20px;margin-right: 5px;margin-bottom: 5px;background:
                                #A200FF
            "></div> @elseif($rou->color == 'green')
                                <div class="pull-left img-circle" style="width: 20px;height: 20px;margin-right: 5px;margin-bottom: 5px;background:
                                #00FF18
            "></div> @elseif($rou->color == 'red')
                                <div class="pull-left img-circle" style="width: 20px;height: 20px;margin-right: 5px;margin-bottom: 5px;background:
                                #ed2700
            "></div> @elseif($rou->color == 'gold')
                                <div class="pull-left img-circle" style="width: 20px;height: 20px;margin-right: 5px;margin-bottom: 5px;background:
                                #FFF000
            "></div>

                            @endif {{$rou->ammount}}
                        </td>
                        <td>@if($rou->numberWon == 99)
                                Round not ended
                            @else
                                <div class="pull-left img-circle"
                                     style="width: 20px;height: 20px;margin-right: 5px;margin-bottom: 5px;background:
                                     @if($rou->colorWon == 'purple')
                                             #A200FF
                                     @elseif($rou->colorWon == 'red')
                                             #ed2700
                                     @elseif($rou->colorWon == 'gold')
                                             #FFF000
                                     @elseif($rou->colorWon == 'green')
                                             #00FF18
                                     @endif"></div>
                            @endif
                        </td>
                        <td>
                            @if($rou->colorWon == $rou->color)
                                @if($rou->colorWon == 'green')
                                    <font color="lime">+{{$rou->ammount*8}}</font>
                                @elseif($rou->colorWon == 'gold')
                                    <font color="lime">+{{$rou->ammount*24}}</font>
                                @else
                                    <font color="lime">+{{$rou->ammount*2}}</font>
                                @endif
                            @else
                                <font color="red">-{{$rou->ammount}}</font>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            {{$getWinner->links()}}
        @else
            You not placed any bet yet? Go to roulette!
        @endif
    </div>
@stop

@section('scripts_dif')

@stop