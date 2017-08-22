function checkResize() {
    $('#noItems').css('width', $('.jackpotPeople').width());
    $('#noItems').css('height', $('.jackpotPeople').height() - 20);
}
$( window ).resize(function() {
    checkResize();
});
$(function () {
    setInterval(function () {
        if ($('#potValue').html() != undefined) {
            document.title = '$' + $('#potValue').html() + ' | csgourban.com - CSGO Jackpot';
        }
    }, 1500);
    $(".user_scroller").mCustomScrollbar({
        scrollButtons: {enable: true},
        scrollbarPosition: "inside",
        axis: "y" // horizontal scrollbar
    });
    checkResize();
    for (var j = 0; j < 100; j++) {
        $('.inside_anim').append('<img src="img/default.jpg" alt="">');
    }
    var winnerID = 0;
    var sekonds = 0;
    var canbet = true;
    var socketos = io.connect('37.59.1.92:8799')
        .on('connect', function (data) {
            setInterval(function () {
                socketos.emit('bet', canbet);
                if(canbet == false) {
                    $('#tstatus').html('<font color="red">Bot no longer accepting bids until the end of this round</font>');
                } else {
                    $('#tstatus').html('<font color="#adff2f">Now you can send offers to the BOT</font>');
                }
            }, 1000);
        })
        .on('accepted', function (data) {
            if (data == window.userID) {
                $('#status').html('Your offer has been <font color="#adff2f">declined</font>');
            } else {
                $('#status').html('Awaiting for offers.');
            }
        })
        .on('offerFrom', function (data) {
            if (data == window.userID) {
                $('#status').html('Your offer is currently being <font color="yellow">processed</font>');
            } else {
                $('#status').html('Processing offers.');
            }
        })
        .on('declined', function (data) {
            if (data == window.userID) {
                $('#status').html('Your offer has been <font color="red">declined</font>');
            } else {
                $('#status').html('Awaiting for offers.');
            }
        });
    io.connect('37.59.1.92:2223')

        .on('bet', function (data) {
            canbet = data;
        })
        .on('startTimer', function (actualTime, endAt) {
            //console.log('start');
            var korekta = Math.abs(actualTime - (new Date).getTime());
            //console.log(korekta);
            var odliczanie = setInterval(function () {
                var czas = (new Date).getTime() + korekta;
                var sekundy = parseInt(parseInt((endAt - czas) / 1000));
                //console.log(sekundy);
                sekonds = sekundy - 5;
                if (sekundy > 0) {
                    $('#timer').html("" + sekundy + " SECONDS ");
                }
                if (sekundy < 0) {
                    clearInterval(odliczanie);
                    $('#timer').html("125 SECONDS");
                }

            }, 1000);
        })
        .on('clearGame', function (data) {
            winnerID = data;
        })
        .on('clearWinner', function (data) {
            winnerID = 0;
        });
    io.connect('37.59.1.92:2344')
        .on('potValue', function (data) {
            //$('#potValue').html(getFormattedPrice(data));
            PotValue = getFormattedPrice(data);
            $('#potPrice').html('$' + PotValue);
        })
        .on('historyCount', function (data) {
            $('#allgames').html(data + 1400);
        })
        .on('allPlayers', function (data) {
            // console.log(sekonds);
            if (data != '') {
                $('#noItems').fadeOut();
                $('.inside_anim').html('');
                $('.usersInPot').html('');
                $.each(data, function (key, value) {
                    var percentageChance = (getFormattedPrice(value['userPutIN']) / PotValue * 100);
                    percentageChance = percentageChance.toFixed(2);
                    var dataso = {
                        nick: value['nick'],
                        chance: percentageChance,
                        items_count: value['cnt'],
                        price: getFormattedPrice(value['userPutIN']),
                        url: value['url'],
                        avatar: value['avatar'],
                        steamID: value['ownerSteamId64']
                    };

                    var user = ' <div class="user col-lg-24 col-md-24 col-xs-24 col-sm-24">';
                    user += '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-15 user_in">';
                    user += '<div class="left_image"><img src="' + dataso.avatar + '" alt=""></div>';
                    user += '<div class="user_name"><a href="' + dataso.url + '">' + dataso.nick + '</a></div>';
                    user += '<div class="user_info">' + dataso.items_count + ' skin(s) - <b>' + dataso.chance + '%</b> - <b>$' + dataso.price + '</b></div>';
                    user += '<div class="user_progress"><div  style="width: ' + dataso.chance + '%;" class="inside"></div></div>';
                    user += '</div>';
                    user += '<div class="col-lg-18 col-md-12 col-sm-12 col-xs-8 no_padding text-left itemsInsideUser ">';
                    user += '<div class="itemsScroll items_' + dataso.steamID + '">';
                    user += '</div>';
                    user += '</div>';
                    user += '</div>';

                    $('.usersInPot').append(user);
                    $.getJSON('/getUserItems/' + dataso.steamID + '', function (data) {
                        var items = '';
                        $.each(data, function (key, value) {
                            var dataa = {
                                color: value['itemRarityColor'],
                                icon: value['itemIcon'],
                                itemname: value['itemName'],
                                itemprice: getFormattedPrice(value['itemPrice']),
                                owner: value['ownerSteamId64']
                            };
                            items += '<div class="item">';
                            items += '<div class="colorLock" style="background: #' + dataa.color + ';"></div>';
                            items += '<div class="itemPrice">$' + dataa.itemprice + '</div>';
                            items += '<img src="http://steamcommunity-a.akamaihd.net/economy/image/' + dataa.icon + '/50fx50f" alt="">';
                            items += '</div>';
                        });
                        $('.itemsScroll.items_' + dataso.steamID + '').append(items);
                        $('.items_' + dataso.steamID + '').mCustomScrollbar({
                            scrollButtons: {enable: true},
                            scrollbarPosition: "inside",
                            axis: "y" // horizontal scrollbar
                        });
                    });


                    for (var j = 0; j < percentageChance + 40; j++) {
                        $('.inside_anim').append('<img src="' + dataso.avatar + '"/>');
                    }
                    $('.inside_anim').append('<img class="user_' + value['ownerSteamId64'] + '" src="' + dataso.avatar + '"/>');
                    for (var j = 0; j < percentageChance + 10; j++) {
                        $('.inside_anim').append('<img src="' + dataso.avatar + '"/>');
                    }
                    var parent = $(".inside_anim");
                    var divs = parent.children();
                    while (divs.length) {
                        parent.append(divs.splice(Math.floor(Math.random() * divs.length), 1)[0]);
                    }
                });

            }


        })
        .on('roundHash', function (data) {
            var hash = data['roundHash'];
            $('.table_user .liveroundhash').html('#' + hash + '');
        })
        .on('PotItemsCount', function (data) {
            $('#potCount').html(data);
        });
    setInterval(function () {
        if (winnerID != 0) {
            // console.log(winnerID);
            setTimeout(function() {
                $.getJSON('/getLastWinner/', function (data) {
                    var last_winner = '';
                    last_winner += '<img src="img/crown.png" width="20" alt="">';
                    last_winner += '<h4 class="winnerName">';
                    last_winner += '' + (data['lastWinnerData'][0]['nick']).substr(0, 10) + '<br>';
                    last_winner += 'Won <b>$' + getFormattedPrice(data['lastWinner'][0]['potPrice']) + ' </b> with <b>' + (((getFormattedPrice(data['lastWinner'][0]['userPutInPrice']) / data['lastWinner'][0]['potPrice'] * 100).toFixed(2))*100).toFixed(2) + '%</b> chanse.</b>';
                    last_winner += '</h4>';

                    var winerom = data['lastWinner'][0]['winnerSteamId64'];
                    var ppr = parseInt("-" + $('.user_' + winerom + '').position().left) + 170;
                    if ($.cookie('win_sound') != 'false') {
                        playSound('start_roll');
                    }
                    $('.inside_anim').css({
                        transition: "all 10s cubic-bezier(0,1,1,1)",
                        transform: "translate(" + ppr + "px)"
                    });
                    setTimeout(function () {
                        if ($.cookie('win_sound') != 'false') {
                            playSound('stop_roll');
                        }
                        $('.last_winnero').html(last_winner);
                    }, 11000);
                    setTimeout(function () {
                        $('#noItems').fadeIn();
                        $('.inside_anim').html('');
                        $('.usersInPot').html('');
                        $('.inside_anim').css('transition', 'none');
                        $('.inside_anim').css('transform', 'none');
                        $('.inside_anim').css('left', -31);
                        for (var j = 0; j < 100; j++) {
                            $('.inside_anim').append('<img src="https://steamdb.info/static/img/default.jpg" alt="">');
                        }
                    }, 16000);
                });

            },1500);
            winnerID = 0;
            return;
        }
    }, 700);

});
