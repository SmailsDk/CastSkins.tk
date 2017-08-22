@extends('welcome')
@section('page_title') Roulette History @stop

@section('content')
    <div class="container" style="margin-bottom: 100px;">
        @foreach($history as $item)
            <div class="pull-left img-circle" style="width: 30px;height: 30px;margin-right: 5px;margin-bottom: 5px;background:
            @if($item->colorWon == 'black' || $item->colorWon == 'purple')
                    #A200FF
            @elseif($item->colorWon == 'red')
                    #ed2700
            @elseif($item->colorWon == 'gold')
                    #FFF000
            @elseif($item->colorWon == 'green')
                    #00FF18
            @endif"></div>
        @endforeach
    </div>
@stop

@section('scripts_dif')

@stop