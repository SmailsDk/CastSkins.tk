@extends('welcome')
@section('page_title') Free Skins @stop

@section('content')

    <div class="col-lg-24">
        <div class="container">
            <style>
                .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
                    padding: 8px;
                    line-height: 1.42857143;
                    vertical-align: top;
                    border-top: 1px solid rgba(255, 0, 0, 0.56);
                }
            </style>
            <div class="col-md-24 col-lg-24 text-center" style="color:#fff;">
                <img src="{{asset('images/bannero.png')}}" style="max-width: 100%;" alt="">
                <div class="col-lg-24 text-left">
                    <br><br>

                    <h4>Users who deposit from 20.10.2016 to 15.11.2016 : </h4>
                    <table class="table table-responsive">
                        <tr>
                            <td>Place.</td>
                            <td>User</td>
                            <td>Deposited</td>
                        </tr>
                        <?php $i = 1 ?>
                        @foreach($depositors as $dp)
                            <tr>
                                <td style="vertical-align: middle;">{{$i}}</td>
                                <td style="vertical-align: middle;"><img class="img-circle" width="50" src="{{$dp['avatar']}}" alt=""
                                         style="margin-right: 5px;">
                                    @if($i == 1)
                                        <span style="font-size: 25px;color:gold;">{{$dp['nick']}}</span>
                                    @elseif($i == 2)
                                        <span style="font-size: 25px;color:silver;">{{$dp['nick']}}</span>
                                    @elseif($i == 3)
                                        <span style="font-size: 25px;color:saddlebrown;">{{$dp['nick']}}</span>
                                    @else {{$dp['nick']}} @endif
                                </td>
                                <td style="vertical-align: middle;">{{$dp['deposited']}}
                                    <div class="fa fa-diamond"></div>
                                </td>
                            </tr>
                            <?php $i++ ?>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts_dif')

@stop