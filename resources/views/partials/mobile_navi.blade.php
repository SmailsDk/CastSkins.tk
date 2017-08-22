<div id="mobile_navi">
    <a href="{{route('index')}}"><img src="../img/logo.jpg" alt="" id="logo"></a>
    <div class="mobile_trigger fa fa-bars"></div>

</div>
<div class="mobile_fader">
    <div class="mobile_trigger fa fa-bars"></div>
    <ul class="navi">
        <li><a href="{{route('index')}}"><i class="fa fa-circle"></i> Roulette</a></li>
        <li><a href="{{route('coinflip')}}"><i class="fa fa-bomb"></i> Coinflip</a></li>
        <li><a href="{{route('withdraw')}}"><i class="fa fa-caret-square-o-up"></i> WITHDRAW</a></li>
        <li><a href="{{route('deposit')}}"><i class="fa fa-caret-square-o-down"></i> DEPOSIT</a></li>
        <li><a href="{{route('history_ruletka')}}"><i class="fa fa-history"></i> History</a></li>
        <li><a href="{{route('buy')}}"><i class="fa fa-history"></i> Buy diamonds</a></li>
        @if(Auth::check())
            <li>
                <img width="30" src="{{Auth::user()->avatar}}" alt="" class="avatar img-circle">
                <span class="nickname">{{str_limit(Auth::user()->nick,20)}}</span>
            </li>
            <li>
                <span class="balance"><span >{{Auth::user()->coins}}</span> <b><i
                                class="fa fa-diamond"></i> </b></span>
            </li>
            <li>
                <a href="{{route('logout')}}" style="margin-right: 10px;float: none;display: inline-block;"><i
                            class="fa fa-blind"></i></a>
                <a href="" class="active" style="float: none;display: inline-block;"><i class="fa fa-plus"
                                                                                        aria-hidden="true"></i></a>
            </li>
        @else
            <li>
                <a href="{{route('login')}}"><i class="fa fa-steam"></i> <span>Sign in through Steam</span></a>
            </li>
        @endif
    </ul>

</div>