<div id="recents" class="col-lg-4 no_padding">
    <div class="top_box">
        <i class="fa fa-clock-o clock_icon"></i> <span>Online Chat</span>
    </div>
    {{--<div class="top_box">--}}
        {{--Select room : <span style="    color: #ed2700;" class="select_room">ENGLISH</span> <i style="color: #ed2700;position: relative;top: -3px;" class="fa fa-sort-desc" aria-hidden="true"></i>--}}
    {{--</div>--}}
    <div class="recents_box" class="general">
        <div id="chatmessages" class="general"></div>
    </div>
    <div class="sendmessage">
        <input type="text" class="sendmessage_text" placeholder="Type your message">
        <div class="emoti"> @include('usable.emoti')</div>
        <div class="sendmessage_submit" type="general">
            <button class="inside">
                Send!
            </button>
        </div>
    </div>
    <div class="recents_footer">
        <div class="col-lg-24 text-center">
            <h6>Users online : <b class="usersOnlineS">0</b> <img src="{{asset('img/ripple.gif')}}" width="15" alt=""
                                                                  style="position: relative;top: -1px;"></h6>
        </div>
    </div>
</div>