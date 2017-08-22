@extends('welcome')
@section('page_title') Your transfer History @stop

@section('content')
    <div class="container">
        <table class="table user_history_table">
            <thead>
            <tr>
                <th>ID</th>
                <th>FROM</th>
                <th>TO</th>
                <th>COINS</th>
                <th>DATE</th>
            </tr>
            </thead>
            <tbody>
            @foreach($getTransferHistory as $history)

                <tr>
                    <td>{{$history->id}}</td>
                    <td>@if($history->from_id == Auth::user()->steamId64) YOU @else {{$history->from}} @endif</td>
                    <td>@if($history->to_id == Auth::user()->steamId64) YOU @else {{$history->to}} @endif</td>
                    <td>{{$history->coins}}</td>
                    <td>{{$history->created_at}}</td>
                </tr>

            @endforeach

            </tbody>
        </table>

    </div>
@stop

@section('scripts_dif')

@stop