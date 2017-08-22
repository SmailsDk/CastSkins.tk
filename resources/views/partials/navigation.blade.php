<div id="navigation" class="col-lg-24 col-md-24 col-xs-24 col-sm-24">
    <a href="{{route('index')}}"><img src="../img/logo.jpg" alt="" id="logo"></a>
    <div class="socials">
        <a target="_blank" href="#"><i class="fa fa-facebook"></i></a>
        <a target="_blank" href="#"><i class="fa fa-steam"></i></a>
    </div>
    <div class="info_bar">
        <span>
            <a href="http://bskn.co/?ref_alias=kj-ax59iw4o"><img style="    width: 130px;
    position: relative;
    top: -2px;" src="{{asset('images/bitskins-logo-light.png')}}" alt=""></a>
        </span>
    </div>


        <div class="pull-right links_right">
            <div class="link">
                <a href="javascript:void(0);" data-remodal-target="faq">
                    <i class="fa fa-book"></i> <span>Faq</span>
                </a>
            </div>
            @if(!Auth::check())
            <div class="link">
                <a href="{{route('login')}}"><i class="fa fa-steam"></i> <span>Sign in through Steam</span></a>
            </div>
            @else
                <div class="link buyCoins">
                    <a href="{{route('buy')}}"><i class="fa fa-cart-plus"></i> <span>Buy diamonds</span></a>
                </div>
                <div class="link">
                    <a href="javascript:void(0);" data-remodal-target="freecoins" id="freecoins"><i class="fa fa-money"></i> <span>FREE DIAMONDS</span></a>
                </div>
                <div class="double_parent" style="position: relative;">

                    <div class="doublelink dropdown user_drop" data-toggle="dropdown">
                        <img width="30" src="{{Auth::user()->avatar}}" alt="" class="avatar img-circle">
                        <span class="nickname">{{str_limit(Auth::user()->nick,20)}} <span class="caret"></span></span>
                    </div>
                    <ul class="dropdown-menu user_dropd">
                        <li><a href="{{route('settings')}}">Settings</a></li>
                        {{--<li><a href="{{route('jackpotUserHistory')}}">Jackpot History</a></li>--}}
                        <li><a href="{{route('rouletteUserHistory')}}">Roulette History</a></li>
                        <li><a href="{{route('coinflipUserHistory')}}">Coinflip History</a></li>
                        <li><a href="javascript:void(0);" data-remodal-target="gift">Send gift</a></li>
                        <li><a href="{{route('transfers')}}">Transfer History</a></li>
                    </ul>
                    <div class="doublelink">
                        <span class="balance"><span  id='currentBallance'>0</span> <b><i class="fa fa-diamond"></i><i style="cursor:pointer" class="fa fa-refresh noselect" id="getbal"></i> </b></span>
                    </div>
                </div>
                <div class="double_parent buttons">
                    <div class="doublelink ">
                        <a href="{{route('logout')}}"><i class="fa fa-blind"></i></a>
                    </div>
                    <div class="doublelink">
                        <a href="{{route('buy')}}" class="active"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    </div>
                </div>
            @endif

        </div>

</div>