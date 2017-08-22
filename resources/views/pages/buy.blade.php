@extends('welcome')
@section('page_title') Buy coins @stop

@section('content')

    <div class="container" id="buyCoins">


        <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
            @if(!Auth::check())
                <h4>You need to be logged in</h4>
            @else
                <br><br><br>
                <h4>Buy coins using G2 PAY</h4>
                <hr>
                <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
                    <div class="col-lg-16 col-md-12 col-sm-12 col-xs-24 no_padding">
                        <div class="form-group">
                            <label for="email">Email adress:</label>
                            <input type="email" name="email" required v-model="email" placeholder="Your email adress"
                                   class="form-control" value="{{Auth::user()->email}}" id="email">
                        </div>
                        <div class="form-group">
                            <label for="ammout">Coins ammout:</label>
                            <input type="tel" name="amm" required class="form-control"
                                   placeholder="Provide conins ammout you want to buy" v-model="ammout" id="ammout">
                        </div>
                        <div class="form-group">
                            User Steamid64 : <b>{{Auth::user()->steamId64}}</b> <br>
                            Email address: <b>@{{ email }}</b> <br>
                            Order Cost: <b class="font-green" id="orderCost">@{{ cost }}</b> <br>
                            Order datetime: <b>{{$today}}</b>
                        </div>
                        <div id="checkoutForm">
                            <img id="checkout" style="width: 250px;" src="{{asset('img/checkout.png')}}" class="max100"
                                 alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-24 text-center">
                        <img src="{{asset('img/payments_240.jpg')}}" alt="">
                    </div>
                    <hr>
                    @if($getUserPay != '0')
                        <table class="table user_history_table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>COINS</th>
                                <th>COST</th>
                                <th>DATE</th>
                                <th>STATUS</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($getUserPay as $pay)

                                <tr>
                                    <td>{{$pay->orderID}}</td>
                                    <td>{{$pay->coins}}</td>
                                    <td>{{$pay->cost}} EUR</td>
                                    <td>{{$pay->created_at}}</td>
                                    <td>@if($pay->status == '1')
                                            <font color="yellow">Pending</font> @elseif($pay->status == '2')
                                            <font color="#adff2f">Done</font> @elseif($pay->status == '3') <font
                                                    color="red">Rejected</font> @endif</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
                            <h4>No purchases by You <br><br><br><br><br></h4>
                        </div>
                    @endif
                </div>
            @endif
            <hr>
            @if(!Auth::check())
                YOU NEED TO LOG IN
            @else
                <p style="color:red;">
                    <b>Please be advice. For now we are supporting only Polish numbers. We will add more
                        payments method as soon as possible. Be patient.</b>
                </p>
                <img src="{{asset('images/simpay_banner_700_41.png')}}" alt="">
                <hr>
                <style>
                    html body .table_simpay tr th, html body .table_simpay td {
                        height: 30px !important;
                        vertical-align: middle !important;
                        padding: 10px !important;
                    }
                </style>
                <form id="simpay_buy">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="steamid" value="{{Auth::user()->steamId64}}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">User ID : </label>
                        <input type="text" class="form-control" id="usrID64" placeholder="User ID"
                               value="{{Auth::user()->steamId64}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="smscode">Code From SMS :</label>
                        <input type="text" name="code" class="form-control" id="smscode"
                               placeholder="Code From SMS"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="number">Choose number :</label>
                        <select v-model="number" onchange="updateCost()" class="form-control" name="number"
                                id="number">
                            <option selected value="">SELECT NUMBER</option>
                            @foreach($codes as $code)
                                <option value="{{$code->numbo}}">{{$code->numbo}} ({{$code->cost}} PLN
                                    BRUTTO) - {{$code->coins}} COINS
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Submit code</button>
                </form>
                <style>
                    #phonenumber .messagetosend {
                        position: absolute;
                        top: 123px;
                        left: 106px;
                        right: 0;
                        margin: auto;
                    }

                    #phonenumber {
                        position: relative;
                    }

                    #phonenumber img {
                        float: none;
                    }
                </style>
                <div id="phonenumber" class="col-lg-12 text-center">
                    <div class="messagetosend">
                        SEND SMS TO <br>
                        <b>@{{number}}</b>
                        <br><br>
                        WITH MESSAGE : <br>
                        <b>SIM.CSGOURBAN</b>
                    </div>
                    <img src="{{asset('images/rs-slider1-phone_1.png')}}" alt="">
                </div>

            @endif
        </div>
        <hr>
        @if($getsms != '0')
            <table class="table user_history_table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>COINS</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                </tr>
                </thead>
                <tbody>
                @foreach($getsms as $pay)

                    <tr>
                        <td>{{$pay->id}}</td>
                        <td>{{$pay->coins}}</td>
                        <td>{{$pay->date}}</td>
                        <td><font color="#adff2f">Done</font></td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        @else
            <div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding">
                <h4>No purchases by You <br><br><br><br><br></h4>
            </div>
        @endif
        <hr>

    </div>

@stop
<style>

</style>
@section('scripts_dif')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js" type="text/javascript"></script>
    <script src="{{asset('js/buy.js')}}"></script>
    <script>
        function updateCost(number, cost) {
            $('#sim_number').val($('#number').val());
        }
        new Vue({
            el: 'body',
            data: {
                number: '92555',
                cost: '23,37'
            }
        });
    </script>
    <script>
        $('#simpay_buy').on('submit', function () {
            var formdata = $(this).serialize();
            $.ajax({
                url: '/buySimpay.php',
                method: 'post',
                dataType: 'json',
                data: formdata,
                headers: {
                    'X-CSRF-TOKEN': $('#_token').val()
                },
                success: function (data) {
                    if (data['buyed'] == false) {
                        alertify.error('Please check your code!');
                    } else {
                        alertify.success('Thanks for supporting our site ! You recived : ' + data['coins'] + ' coins!');
                    }
                }
            });
            return false;
        })
    </script>
@stop