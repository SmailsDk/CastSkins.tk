<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="{{'js/bootstrap.min.js'}}"></script>
<script src="{{'js/alertify.js'}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.6/socket.io.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/alertify.js/0.5.0/alertify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.2.1/mustache.min.js"></script>
<script src="{{asset('js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('js/rotate.js')}}"></script>
<script src="{{asset('js/jquery.mCustomScrollbar.min.js')}}"></script>
<script src="{{asset('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('js/remodal.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/noframework.waypoints.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.js"></script>
<script src="{{asset('js/rAF.js')}}"></script>
<script src="{{asset('js/demo-2.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-62687516-8', 'auto');
    ga('send', 'pageview');

</script>
@if(Auth::check())
    <script>
        $(function() {
            $('#getbal').on('click', function () {
                getBalance();
            });
        });
        $({countNum: 0}).animate({countNum: "{{Auth::user()->coins}}"}, {
            duration: 1000,
            easing: 'linear',
            step: function () {
                $('#currentBallance').html(parseFloat(this.countNum).toFixed(0));
            },
            complete: function () {
                $('#currentBallance').html(parseFloat(this.countNum).toFixed(0));
            }
        });
        setInterval(function () {
            getBalance();
        }, 10000);
        function getBalance() {
            $.getJSON('/checkLogin', function (data) {
                if (data != null) {
                    if (parseFloat(data['coins']) != $('#currentBallance').html()) {
                        if (parseFloat(data['coins']) > $('#currentBallance').html()) {
                            var htmlo = '<div class="chat_message"><div class="top"><div class="right_info"><a href="javascript:void(0);" class="user_name admin">Info Bot</a></div></div><div class="message">You recived ' + (parseFloat(data['coins']) - $('#currentBallance').html()) + ' diamonds !</div></div>';
                            $('#chatmessages').append(htmlo);
                            $('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
                        }
                        $({countNum: $('#currentBallance').html()}).animate({countNum: parseInt(data['coins'])}, {
                            duration: 500,
                            easing: 'linear',
                            step: function () {
                                $('#currentBallance').html(parseFloat(this.countNum).toFixed(0));
                            },
                            complete: function () {
                                $('#currentBallance').html(parseFloat(this.countNum).toFixed(0));
                            }
                        });
                    }

                }
            });
        }
    </script>
@endif