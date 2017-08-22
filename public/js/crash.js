var bust = io('185.158.153.99:2020');

bust.on('actualBust', function (data) {
    $('#crashCounter').html('x' + (data / 1000).toFixed(2) + '');
});
bust.on('bustDone', function (data) {
    cashout = false;
    $('.placeBetNow').css('display', 'inline-block');
    $('.cashout').css('display', 'none');
    $('#crashCounter').html('<font color="red">x' + (data / 1000).toFixed(2) + '</font>');
    $('#nextGamein').animate({
        opacity: 1
    }, 1000, function () {
        // Animation complete.
    });
    var next = 10;
    var count = setInterval(function () {
        $('#nextGamein').html('Next game in ' + next + ' seconds');
        next = next - 1;

        if (next == 0) {
            clearInterval(count);
            $('#nextGamein').animate({
                opacity: 0
            }, 1000, function () {
                $('#nextGamein').html('Next game in 10 seconds');
            });

        }
    }, 1000);
});
var lastBustData;
bust.on('last_busts', function (data) {
    if(lastBustData != data) {
        lastBustData = data;
        $('#lastBusts').html('');
        var last = '';
        $.each(data, function (key, value) {

            if ((value['bust_number'] * 1000).toFixed(0) < 1500) {
                last += '<div style="font-weight:bold;float: none;display: inline-block;margin-left: 5px;color:#ED2700;">x' + value['bust_number'] + '</div>';
            } else if ((value['bust_number'] * 1000).toFixed(0) < 3000 && (value['bust_number'] * 1000).toFixed(0) > 1500) {
                last += '<div style="font-weight:bold;float: none;display: inline-block;margin-left: 5px;color:#A200FF;">x' + value['bust_number'] + '</div>';
            } else {
                last += '<div style="font-weight:bold;float: none;display: inline-block;margin-left: 5px;color:#FFF000;">x' + value['bust_number'] + '</div>';
            }

        });
        $('#lastBusts').append(last);
    }

});
bust.on('allBets', function (data) {
    if (data != '') {
       // console.log(data);
        $('.bust_table tbody').html('');
        $.each(data, function (key, value) {
            var bet = '';
            bet += '<tr>';
            bet += '<td><a style="color:#fff;" href="' + value['url'] + '">' + value['nick'] + '</a></td>';
            if(value['cashed_out'] != '0') {
                bet += '<td>'+value['cashed_out']+'</td>';
            } else {
                bet += '<td>--</td>';
            }
            bet += '<td>' + value['amount'] + ' <div class="fa fa-diamond"></div></td>';
            if(value['profit'] != '0') {
                bet += '<td>'+value['profit']+'</td>';
            } else {
                bet += '<td>--</td>';
            }
            bet += '</tr>';
            $('.bust_table tbody').append(bet);
        });
    }
});
function plus(ammount) {
    var coins = parseInt($('#currentBallance').html());
    var now = $('#bet_amount').val();
    now = parseInt(parseFloat(now)) + ammount;
    if (ammount == 'clear') {
        $('#bet_amount').val(0);
        return;
    }
    if (now > coins) {
        alertify.error('Not enouth coins!');
    } else {
        if (coins < ammount) {
            alertify.error('Not enouth coins!');
        } else {
            $('#bet_amount').val(now);
        }
    }
}
var cashout = false;
setInterval(function() {
    var current = (($('#crashCounter').html().replace(/X/gi, ''))*1000).toFixed(0);
    var auto = ($('#auto_cashout').val()*1000).toFixed(0);
    if(parseFloat(current) >= parseFloat(auto) && cashout == false && parseFloat(auto) > 0) {
        cashout = true;
        console.log('Cashout');
        cashOut();
    }
},50);
function razy(ammount) {
    var coins = parseInt($('#currentBallance').html());
    var now = $('#bet_amount').val();
    now = parseInt(parseFloat(now)) * ammount;
    if (ammount == 'max') {
        $('#bet_amount').val(coins);
    } else {
        if (coins < now) {
            alertify.error('Not enouth coins!');
        } else {
            $('#bet_amount').val(now);
        }
    }
}
function placeBet(amount) {
    $('.placeBetNow').attr('disabled', true);
    setTimeout(function () {
        $('.placeBetNow').attr('disabled', false);
    }, 3000);
    $.ajax({
        type: 'POST',
        url: '/placeBust',
        dataType: 'json',
        data: {
            amount: amount
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (response) {

            if (response) {
                if (response.success == 0) {
                    alertify.error(response.response);
                } else {
                    alertify.success(response.response);
                    $('.placeBetNow').css('display', 'none');
                    $('.cashout').css('display', 'inline-block');
                }

            }
        }
    });
}
function cashOut() {
    $.ajax({
        type: 'POST',
        url: '/getUserSteamId64',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (response) {
            if (response['id']) {
                bust.emit('getCurrent', response['id'])
            }
        }
    });
}
bust.on('cashout', function (data) {
    $.ajax({
        type: 'POST',
        url: '/cashOut',
        dataType: 'json',
        data: {
            cashout: data,
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (response) {
            if (response) {
                if (response.success == 0) {
                    alertify.error(response.response);
                } else {
                    $('.placeBetNow').css('display', 'inline-block');
                    $('.cashout').css('display', 'none');
                    alertify.success(response.response);
                }

            }
        }
    });
});