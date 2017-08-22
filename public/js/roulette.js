var red = [
    '4004deg', '4017deg', '4037deg', '4043deg', '4058deg', '4084deg', '4117deg', '4143deg', '4149deg', '4171deg', '4184deg', '4190deg', '4211deg', '4218deg', '4225deg', '4244deg', '4257deg', '4290deg', '4311deg', '4318deg', '4324deg', '4337deg', '4344deg', '4363deg'
];
var purple = [
    '4023deg', '4051deg', '4070deg', '4091deg', '4097deg', '4104deg', '4111deg', '4124deg', '4131deg', '4158deg', '4162deg', '4177deg', '4197deg', '4204deg', '4231deg', '4370deg', '4238deg', '4251deg', '4329deg', '4265deg', '4304deg'
];
var gold = [
    '4030deg'
];
var green = [
    '4282deg', '4424deg', '4436deg', '4496deg'
];
var purples = ['2', '4', '6', '8', '10', '12', '14', '16', '18', '20', '22', '22', '24', '26', '28', '30', '32', '34', '36'];
var reds = ['1', '3', '5', '7', '9', '11', '13', '15', '17', '19', '25', '88', '31', '33', '27', '35', '37'];
var golds = ['21'];
var greens = ['0', '70'];

var freeze = false;


function spin(color, faction) {
    if ($.cookie('win_sound') != 'false') {
        playSound('start_roll');
    }
    var item = color[Math.floor(Math.random() * color.length)];
    $('#wheel').css({
        transition: "all 5s cubic-bezier(0.000, 0.585, 0.000, 1)",
        transform: "rotate(" + item + ")"
    });
    setTimeout(function () {
        if ($.cookie('win_sound') != 'false') {
            playSound('stop_roll');
        }
        var multiply = 0;
        if (faction == 'purple') {
            faction = 'black';
            multiply = 2;
        } else if (faction == 'black' || faction == 'red') {
            multiply = 2;
        } else if (faction == 'green') {
            multiply = 8;
        } else {
            multiply = 24;
        }

        $("#" + faction + "Total").css('color', 'lime');
        $({countNum: parseFloat($("#" + faction + "Total").html())}).animate({countNum: parseFloat($("#" + faction + "Total").html()) * multiply}, {
            duration: 1000,
            easing: 'linear',
            step: function () {
                $("#" + faction + "Total").html(parseFloat(this.countNum).toFixed(0))
            },
            complete: function () {
                $("#" + faction + "Total").html(parseFloat(this.countNum).toFixed(0))

                $('.roulette_bets .tableusers tbody').animate({
                    height: "0px"
                }, 2000, function () {
                    setTimeout(function () {
                        $.each($('.totalBet'), function (key, value) {
                            $('#' + value['id']).removeAttr("style");
                            $('#' + value['id']).css('color', 'white');
                            $('.user_bet:first-of-type span').html('0');
                        });
                    }, 2000);
                });
            }
        });
        $.each($('.totalBet'), function (key, value) {
            if (value['id'] != '' + faction + 'Total') {
                $('#' + value['id']).css('color', 'red');
                $({countNum: parseFloat($('#' + value['id']).html())}).animate({countNum: parseFloat($('#' + value['id']).html()) - (parseFloat($('#' + value['id']).html()) * 2)}, {
                    duration: 1000,
                    easing: 'linear',
                    step: function () {
                        $('#' + value['id']).html(parseFloat(this.countNum).toFixed(0))
                    },
                    complete: function () {
                        $('#' + value['id']).html(parseFloat(this.countNum).toFixed(0))
                    }
                });
            }
        });


    }, 5100);
    setTimeout(function () {

        $('#wheel').css({
            transition: "all 0.5s cubic-bezier(0.000, 0.585, 0.000, 1)",
            transform: "rotate(0deg)"
        });


    }, 10000);
}
var socket = io('37.59.1.92:2222');
var rou = io('37.59.1.92:3214');

socket.on('freeze', function (data) {
    freeze = data;
    $('.place_bet').attr('disabled', data);
});
socket.on('lastColors', function (data) {
    setTimeout(function () {
        $('#last_bets').html('');
        //console.log(data);
        $.each(data, function (key, value) {
            $('#last_bets').append('<div class="bet ' + value['colorWon'] + '"></div>');
        });
    }, 7000);
});

var winnerID = 99;
setInterval(function () {
    if (winnerID != 99) {
        if (winnerID == 'purple') {
            spin(purple, 'purple');
        }
        if (winnerID == 'red') {
            spin(red, 'red');
        }
        if (winnerID == 'gold') {
            spin(gold, 'gold');
        }
        if (winnerID == 'green') {
            spin(green, 'green');
        }
        winnerID = 99;
        return;
    }
}, 1000);
socket.on('clearGame', function (data) {
    winnerID = data;
});
var winoAnimID = 0;

var sekundy = 0;
var countDown = false;
socket.on('startTimer', function (actualTime, endAt) {
    var korekta = Math.abs(actualTime - (new Date).getTime());
    //console.log(korekta);
    var odliczanie = setInterval(function () {
        var czas = (new Date).getTime() + korekta;
        sekundy = parseInt(parseInt((endAt - czas) / 1000));
        var sekonds = sekundy - 5;
        // console.log(parseInt(sekundy));
        if (sekundy > 0) {
            // $('.timerTime').html(sekundy);
            if (countDown != true) {
                CountDownTimer(sekundy);
                countDown = true;
            }
        }
        if (sekundy < 0) {
            $('.timerTime').html('32:000');
            if (freeze != true) {
                $('.user_red').html('0');
                $('.user_black').html('0');
                $('.user_green').html('0');
                $('.user_gold').html('0');
            }
            clearInterval(odliczanie);
        }


    }, 1000);
});

function CountDownTimer(time) {
    var parseTime = parseFloat('' + time + '000');
    $({countNum: parseTime}).animate({countNum: 0}, {
        duration: parseTime,
        easing: 'linear',
        step: function () { 
            var much = parseFloat(this.countNum);
            timerFormeter(much);
        },
        complete: function () {
            var much = parseFloat(this.countNum);
            timerFormeter(much);
            if (countDown != false) {
                countDown = false
            }
            ;
        }
    });
}
function timerFormeter(much) {
    if (much < 10000 && much > 1000) {
        $('.timerTime').html('0' + (numeral(much).format('0,000')).replace(/,/gi, ':'));
    } else if (much < 1000 && much > 100) {
        $('.timerTime').html('00:' + (numeral(much).format('0,000')).replace(/,/gi, ':'));
    } else if (much < 100 && much > 10) {
        $('.timerTime').html('00:0' + (numeral(much).format('0,000')).replace(/,/gi, ':'));
    } else if (much < 10 && much > 1) {
        $('.timerTime').html('00:00' + (numeral(much).format('0,000')).replace(/,/gi, ':'));
    } else if (much < 1) {
        $('.timerTime').html('00:000');
    } else {
        $('.timerTime').html((numeral(much).format('0,000')).replace(/,/gi, ':'));
    }
}
rou.on('getUsersInfo', function (data) {
    if (data != null && freeze != true) {
        $('.roulette_bets .tableusers tbody').css('height', 'inherit');
        $('#blackUsers').html('');
        $('#redUsers').html('');
        $('#greenUsers').html('');
        $('#goldUsers').html('');
        $.each(data, function (key, value) {
            var usernick = value['nick'];
            usernick = usernick.replace(reBadLinks, replacement);
            usernick = usernick.substr(0, 10) + '...';
            if (value['color'] == 'red') {
                var colored = '#ED2700';
            } else if (value['color'] == 'purple') {
                var colored = '#A200FF';
            } else if (value['color'] == 'green') {
                var colored = '#00FF18';
            } else {
                var colored = '#FFF000';
            }
            var user = '';
            user += '<tr style="height: 40px;vertical-align: middle; color:#fff;">';
            user += '<td style="border-top: 0;">';
            user += '<div class="image-overlay img-circle" style="width: 34px;height: 34px;' +
                'position: relative;background: ' + colored + '"><img style="position: absolute;top: 0;right: 0;left: 0;bottom: 0;margin:auto;' +
                '" class="img-circle" data-original-title="12" src="' + value['avatar'] + '" width="30" height="30" alt=""></div>';
            user += '</td">';
            user += '<td style="border-top: 0;">';
            if (value['isStreamer'] == '1') {
                user += '<a target="_blank" style="color: #2ab4c0!important;text-decoration: none;word-break: break-all;" href="' + value['streamLink'] + '"><b><i class="fa fa-twitch"></i> ' + usernick + '</b></a>';
            } else {
                user += '<a target="_blank" style="color:#fff;text-decoration: none;word-break: break-all;" href="' + value['url'] + '">' + usernick + '</a>';
            }
            user += '</td">';
            user += '<td style="border-top: 0;">';
            user += '' + value['placed'] + ' <i class="fa fa-diamond"></i>';
            user += '</td">';
            user += '</tr>';
            if (value['color'] == 'purple') {
                $('#blackUsers').append(user);
            } else if (value['color'] == 'red') {
                $('#redUsers').append(user);
            } else if (value['color'] == 'green') {
                $('#greenUsers').append(user);
            } else if (value['color'] == 'gold') {
                $('#goldUsers').append(user);
            }
        });
    }
});

$(function () {
    $('#clearInput').on('click', function () {
        clearInput();
    });
})
function plus(ammount) {
    $.getJSON('/checkLogin', function (data) {
        if (data != null) {
            var steamID = data['steamId64'];
            var coins = data['coins'];
            var now = $('#betammount').val();
            now = parseInt(parseFloat(now)) + ammount;
            if (now > coins) {
                alertify.error('Not enouth coins!');
            } else {
                if (coins < ammount) {
                    alertify.error('Not enouth coins!');
                } else {
                    $('#betammount').val(now);
                }
            }
        } else {
            alertify.error('You need to be log in to use this option!');
        }
    });

}
function razy(ammount) {
    $.getJSON('/checkLogin', function (data) {
        if (data != null) {
            var steamID = data['steamId64'];
            var coins = data['coins'];
            var now = $('#betammount').val();
            now = parseInt(parseFloat(now)) * ammount;
            if (ammount == 'max') {
                $('#betammount').val(coins);
            } else {
                if (coins < now) {
                    alertify.error('Not enouth coins!');
                } else {
                    $('#betammount').val(now);
                }
            }


        } else {
            alertify.error('You need to be log in to use this option!');
        }
    });
}
function clearInput() {
    $('#betammount').val(0);
}
function placeBET(ammount, color) {
    $('.place_bet').attr('disabled', true);
    setTimeout(function () {
        $('.place_bet').attr('disabled', false);
    }, 3000);
    // if (sekundy != -1 || freeze != false) {
    //     if (sekundy < 5 && sekundy > -2) {
    //         alertify.error('To late ! Wait until round will finish');
    //         return;
    //     }
    // }
    // console.log(coins);
    if (ammount < 1) {
        alertify.error('You cannot place nothing!');
        return;
    } else {
        $.ajax({
            type: 'POST',
            url: '/placeBet',
            dataType: 'json',
            data: {
                ammount: ammount,
                color: color
            },
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function (data) {
                if (data['color'] == 'red') {
                    var currentPlaced = parseInt($('.user_red').html());
                    currentPlaced = currentPlaced + parseInt(data['ammount']);
                    $('.user_red').html(currentPlaced);
                }
                if (data['placedMuch']) {
                    alertify.error('Max 4 bets in round!');
                }
                if (data['color'] == 'purple') {
                    var currentPlaced = parseInt($('.user_black').html());
                    currentPlaced = currentPlaced + parseInt(data['ammount']);
                    $('.user_black').html(currentPlaced);
                }
                if (data['color'] == 'gold') {
                    var currentPlaced = parseInt($('.user_gold').html());
                    currentPlaced = currentPlaced + parseInt(data['ammount']);
                    $('.user_gold').html(currentPlaced);
                }
                if (data['color'] == 'green') {
                    var currentPlaced = parseInt($('.user_green').html());
                    currentPlaced = currentPlaced + parseInt(data['ammount']);
                    $('.user_green').html(currentPlaced);
                }
                if (data['baaad'] == true) {
                    alertify.error('Niu niu!');
                    return;
                }
                if (data['improvements'] == true) {
                    alertify.error('We are working on improvements.');
                    return;
                }
                if (data['toLow'] == true) {
                    alertify.error('Minimum bet is 200 diamonds!');
                    return;
                }
                if (data['can_bet'] == false) {
                    alertify.error('You have withdraw request pending, you cannot bet!');
                    return;
                }

                if (data['placed'] != false && data['coins'] != false) {
                    var coins = data['coins'];
                    var htmlo = '<div class="chat_message"><div class="top"><div class="right_info"><a href="javascript:void(0);" class="user_name admin">Info Bot</a></div></div><div class="message">You spent ' + ammount + ' diamonds on ' + data['color'] + '.</div></div>';
                    $('#chatmessages').append(htmlo);
                    $('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
                    $({countNum: $('#currentBallance').html()}).animate({countNum: coins}, {
                        duration: 1000,
                        easing: 'linear',
                        step: function () {
                            $('#currentBallance').html(parseFloat(this.countNum).toFixed(0))
                        },
                        complete: function () {
                            $('#currentBallance').html(parseFloat(this.countNum).toFixed(0))
                        }
                    });
                } else if (data['placed'] != false && data['coins'] == '0') {
                    var coins = data['coins'];
                    var htmlo = '<div class="chat_message"><div class="top"><div class="right_info"><a href="javascript:void(0);" class="user_name admin">Info Bot</a></div></div><div class="message">You spent all your diamonds on ' + data['color'] + '.</div></div>';
                    $('#chatmessages').append(htmlo);
                    ('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
                    $({countNum: $('#currentBallance').html()}).animate({countNum: coins}, {
                        duration: 1000,
                        easing: 'linear',
                        step: function () {
                            $('#currentBallance').html(parseFloat(this.countNum).toFixed(0))
                        },
                        complete: function () {
                            $('#currentBallance').html(parseFloat(this.countNum).toFixed(0))
                        }
                    });

                    // swal('Yea', 'You placed a bet to ' + color + '. Your current coins are : ' + coins + '!', 'success');
                    // $('#betammount').val(0);
                } else {
                    if (data['logged'] == false) {
                        alertify.error('You need to be log in to use this option!');
                    } else if (data['coins'] == false) {
                        alertify.error('You are not that rich!');
                        // $('#betammount').val(0);
                    } else if (data['coins'] == '0') {
                        alertify.error('You are empty!');
                        //$('#betammount').val(0);
                    }

                }
            }

        });
    }
}
$('#blackTotal').html('0');
$('#redTotal').html('0');
$('#greenTotal').html('0');
$('#goldTotal').html('0');
rou.on('getCNTS', function (data) {
    if (freeze != true) {
        $.each(data, function (key, value) {
            $.each(data, function (key, value) {
                reCount(key, value);
            });
        });
    }
});
function reCount(key, value) {
    var freshValue = value;
    var total = parseFloat($("#" + key + "Total").html());
    if (parseInt(total) != parseFloat(freshValue) && parseFloat(freshValue) > parseFloat(total)) {
        $({countNum: total}).animate({countNum: parseFloat(freshValue)}, {
            duration: 1000,
            easing: 'linear',
            step: function () {
                $("#" + key + "Total").html(parseFloat(this.countNum).toFixed(0))
            },
            complete: function () {
                $("#" + key + "Total").html(parseFloat(this.countNum).toFixed(0))
            }
        });
    } else if (parseFloat(freshValue) == '0' && parseFloat(total) != parseFloat(freshValue)) {
        $({countNum: parseFloat(total)}).animate({countNum: 0}, {
            duration: 500,
            easing: 'linear',
            step: function () {
                $("#" + key + "Total").html(parseFloat(this.countNum).toFixed(0))
            },
            complete: function () {
                $("#" + key + "Total").html(parseFloat(this.countNum).toFixed(0))
            }
        });
    } else if (parseFloat(freshValue) != '0' && parseFloat(total) != parseFloat(freshValue)) {
        $({countNum: parseFloat(total)}).animate({countNum: 0}, {
            duration: 1000,
            easing: 'linear',
            step: function () {
                var count = parseFloat(this.countNum);
                $("#" + key + "Total").html(parseFloat(this.countNum).toFixed(0))
            },
            complete: function () {
                $("#" + key + "Total").html(parseFloat(this.countNum).toFixed(0))
            }
        });
    } else if (parseFloat(total) == parseFloat(freshValue)) {
        $("#" + key + "Total").html(parseFloat(freshValue))
    }
}
