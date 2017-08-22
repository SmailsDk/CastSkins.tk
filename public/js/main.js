var PotValue;
var reBadLinks = /csgojackpot.com|CSGOBooth.com|CSGOBubble.com|GambleCSGO.com|CSGODICES.COM|CSGOBAND.COM|CSGObig.com|CSGORumble.com|CSGOking.com|SkinsVictory.com|SGOburn.com|CSGOLOWER.COM|csgojackbet.com|csbahis.com|CSGODRAW.COM|CSGO.FREEPOT.CO|SKINOMANIAK.PL|SWAGPOT.COM|SkinsProject.p|csgowild.com|YGRoulette.pl|csgodream.net|csgo.mk|EASY.GL|OnionPot.eu|CSGOBankBot.com|CSGOHill.com|CSGOMANGO.RU|joyskins.top|SkinProject.pl|csgo-Try.com|TOXICJACKPOT.COM|CSGOFROST.COM|CSGOGrow.com|CSGO-LOTTERY.COM|csgofungames.com|csgobattle.com|SKINJACKPOT.net|Siknsproject.pl|csgofinish.com|CSGOHOUSE.COM|Skinprofit.net|CSgetto.com|CSGOROOT.COM|skinsanity.gg|CSGOCenter.com|NINJACKOP.com|lucky-cowboy.com|skinsanity.com|CSGOREAPER.com|CSGOLoteria.pl|skins2.com|CSGOPOT.win|csgorivalry.com|csgosmile.com|CSGOKILLER.com|CSGOHorses.com|Winaskin.com|SkinsGambling.com|csgofast.com|CSGOHLL.com|CSGOTHRONE.com|csgoSwapper.com|CSGO2x.com|csgo-skin.win|CSGO-Easy.com|CSGOpaka.pl|CSGORev.com|CSGODUCK.COM|betcsgoskin.com|CSGOx14.com|CSGOx14|CSGOAIMPOT.COM|NINJACKPOT.COM|BESTPOTCS.COM|IONIZE.PL|CSGOPULSE.com|Skins.ee|KICKBACK.COM|1bet1win.pl|CSGORaffling.com|CSGO.BEST|CSGOSTRONG|DobryJackpot.pl|CSGOHILL.com|csgo-gambler.com|CSGOAtse.com|CSGOBrawl.com|CSGOVAC.PL|CSGOBETSHARK.COM|csgostakes.com|CSGOSpeed.com|CSGO-SkinWins.com|CSBICEPS.COM|CSGOJoe.com|SKINOMAT.COM|skinpot.eu|loscgo.com|CSGOSKINNY.COM|CSGOVAC.COM|SkinsVictory.com|csgo-city.ru|BestUP.pl|EZPROFIT.PL|CSGOCyrex.com|csgo-saloon.com|RushSkins.com|CSGOPOT.PL|DiceStrike.com|SKIN4.PRO|WinEzSkins.pl|CSGOSell.com|IziSkin.com.pl|csgoRamboPot.com|CSGODiceGame.com|CSGO-EASYSKINS.COM|CSBETGO.COM|CSGOSkins.net|CSGOFADE.NE|csgofade|SKINHELL.COM|CSGOEgg.com|cebulomat.pl|CSGOSHUFFLE.COM|CSGOClever.com|PunchPot.com|multispot.pl|pot4skins.com|CSGOLOW.PL|ygpot.com|loscsgo.com|csgobestpot.com|biedomat.pl|LOSCSGO.COM|CSGO-Skins.pl|skinsproject.pl|csdraw.com|csgodouble|free coins|csgopot.com|CSGOFOREST.com|skinsroad.com|luckyshots.pl/gi;
var reBadWords = /huj|przejebalem|jebany|dojebie|Kurwo|script|wjebie|idiota|ch.j|ahole|zjebany|anus|ash0le|ash0les|asholes|ass|Ass Monkey|Assface/gi;
var replacement = '';
$(document).ready(function () {
    io.connect('185.158.153.99:2020')
        .on('usersOnline', function (data) {

            $('.usersOnlineS').html(data);
        });
    checkHeightsFirst();
    setTimeout(function () {
        $('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
    }, 1500);
    $('.mobile_trigger').on('click', function () {
        $('.mobile_fader').toggle('slow');
        $('.mobile_fader').toggleClass('toggled');
        if ($('.mobile_fader').hasClass('toggled')) {
            $('body').css('overflow', 'hidden');
        } else {
            $('body').css('overflow', 'auto');
        }
    });
    $('#freecoinsCode').on('submit', function () {
        $.ajax({
            url: '/getFreeCoins',
            method: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function (data) {
                if (data.success == 0) {
                    alertify.error(data.response);
                } else {
                    alertify.success(data.response);
                }

            }
        });
        return false;
    });
});
setInterval(function () {
    $('.buyCoins a').addClass('animated flash');
    setTimeout(function () {
        $('.buyCoins a').removeClass('animated flash');
    }, 3000);
}, 30000);
$(window).resize(function () {
    checkHeights();
});
var isAdmino = false;
function checkIsAdmin() {
    $.getJSON('/checkIsAdmin', function (data) {
        if (data == 1) {
            isAdmino = true;
        } else {
            $('#modal').remove();
        }
        return isAdmino;
    });
}
checkIsAdmin();
function checkHeightsFirst() {
    var bodyHeight = $('body').height();
    var naviHeight = $('#navigation').height();
    var chatHeight = bodyHeight - naviHeight;
    $('#recents').css('height', chatHeight);
    var chatrow = chatHeight - 48 - 34 - 37;
    $('.recents_box').css('height', chatrow);
    var contentHeight = $('body').height() - $('#navigation').height() - $('#bottom_navi').height() - 86;
    $('#content_content').css('height', contentHeight);

    $('#content_content').mCustomScrollbar({
        scrollButtons: {enable: true},
        scrollbarPosition: "inside"
    });
    $('.recents_box').mCustomScrollbar("destroy");
    $('.recents_box').mCustomScrollbar({
        scrollButtons: {enable: true},
        scrollbarPosition: "inside"
    });
    $('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
}
function checkHeights() {
    var bodyHeight = $('body').height();
    var naviHeight = $('#navigation').height();
    var chatHeight = bodyHeight - naviHeight;
    $('#recents').css('height', chatHeight);
    var chatrow = chatHeight - 48 - 34 - 37;
    $('.recents_box').css('height', chatrow);
    $('#chatmessages').css('height', chatrow);

    $('.recents_box').mCustomScrollbar("destroy");
    $('.recents_box').mCustomScrollbar({
        scrollButtons: {enable: true},
        scrollbarPosition: "inside"
    });
    $('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
    var contentHeight = $('body').height() - $('#navigation').height() - $('#bottom_navi').height() - 86;
    $('#content_content').css('height', contentHeight);
    $('#content_content').mCustomScrollbar("destroy");
    $('#content_content').mCustomScrollbar({
        scrollButtons: {enable: true},
        scrollbarPosition: "inside"
    });

}
var chat = io.connect('185.158.153.99:2020')
    .on('all-messages', function (data) {
        $('#chatmessages').html('');
        $.getJSON('/getLastMessages', function (data) {
            $.each(data, function (key, value) {
                var date = moment(value['message']['created_at'] + 2).format('HH:mm');
                var usernick = value['user']['nick'];
                var room = value['message']['room'];
                usernick = usernick.replace(reBadLinks, replacement);
                generateChatMessage(room, value['user']['isAdmin'], value['user']['isMod'], value['user']['isStreamer'], value['user']['steamLink'],
                    usernick, value['user']['avatar'], value['message']['text'], value['user']['url'], value['user']['steamId64'], value['message']['id']);
            });
        });
    })
    .on('new_message', function (data) {
        $.getJSON('/getLastMessage', function (data) {
            $.each(data, function (key, value) {
                var date = moment(value['message']['created_at'] + 2).format('HH:mm');
                var usernick = value['user']['nick'];
                usernick = usernick.replace(reBadLinks, replacement);
                var room = value['message']['room'];
                generateChatMessage(room, value['user']['isAdmin'], value['user']['isMod'], value['user']['isStreamer'],
                    value['user']['steamLink'], usernick, value['user']['avatar'], value['message']['text'], value['user']['url'], value['user']['steamId64'], value['message']['id']);
            });
        });

    });

var globalMessages = io.connect('185.158.153.99:2020')
    .on('globalMessage', function (data) {
        if (data != false) {
            $('.globalmessage').html(data).fadeIn('slow');
        } else {
            $('.globalmessage').html('').fadeOut('slow');
        }
    });

$('.emot_inbox').on('click', function (t) {
    var ThisEmoti = $(this).attr('id');
    $('.sendmessage_text').val($(".sendmessage_text").val() + ThisEmoti + " ")
})

$('.sendmessage_submit').on('click', function () {
    sendMessage($(this).attr('type'));
});
$('.sendmessage').keypress(function (e) {
    if (e.which == 13) {
        sendMessage($('.sendmessage_submit').attr('type'));
    }
});

$("#jackpot_pot").css('height', $('#left_navi').height());
// Appending chat messages

function sendMessage(type) {
    var text = $('.sendmessage_text').val();
    $('.sendmessage_text').prop('disabled', true);
    $('.inside').prop('disabled', true);
    setTimeout(function () {
        $('.sendmessage_text').prop('disabled', false);
        $('.inside').prop('disabled', false);
    }, 5000);
    alertify.error('You need to deposit before you chat!');
    // $.ajax({
    //     url: '/sendChatMessage',
    //     method: 'post',
    //     dataType: 'json',
    //     data: {
    //         message: text,
    //         type: type
    //     },
    //     headers: {
    //         'X-CSRF-TOKEN': $('#_token').val()
    //     },
    //     success: function (data) {
    //         $('.sendmessage_text').val('');
    //         if (data.success == 0) {
    //             alertify.error(data.response);
    //         } else {
    //             alertify.success(data.response);
    //         }
    //
    //     }
    // });
}
function generateChatMessage(room, isAdmin, isMod, isStreamer, steamLink, name, avatar, message, link, messageUserID, messageID) {

    message = message.replace(/:D/gi, '<img src = "../images/emoticons/1f600.png" height="24" width="24" alt = ":D" title = ":D" >');
    message = message.replace(/:mad:/gi, '<img src = "../images/emoticons/1f621.png" height="24" width="24" alt = ":mad:" title = ":mad:" >');
    message = message.replace(/:hey:/gi, '<img src = "../images/emoticons/1f44b.png" height="24" width="24" alt = ":hey:" title = ":hey:" >');
    message = message.replace(/:cry:/gi, '<img src = "../images/emoticons/1f62d.png" height="24" width="24" alt = ":cry:" title = ":cry:" >');
    message = message.replace(/:poo:/gi, '<img src = "../images/emoticons/1f4a9.png" height="24" width="24" alt = ":poo:" title = ":poo:" >');
    message = message.replace(/:kiss:/gi, '<img src = "../images/emoticons/1f618.png" height="24" width="24" alt = ":kiss:" title = ":kiss:" >');
    message = message.replace(/:money:/gi, '<img src = "../images/emoticons/1f4b0.png" height="24" width="24" alt = ":money:" title = ":money:" >');
    message = message.replace(/:aaaa:/gi, '<img src = "../images/emoticons/1f631.png" height="24" width="24" alt = ":aaaa:" title = ":aaaa:" >');
    message = message.replace(/:hahaha:/gi, '<img src = "../images/emoticons/1f602.png" height="24" width="24" alt = ":hahaha:" title = ":hahaha:" >');
    message = message.replace(/:haha:/gi, '<img src = "../images/emoticons/1f606.png" height="24" width="24" alt = ":haha:" title = ":haha:" >');
    message = message.replace(/:kosa:/gi, '<img src = "../images/emoticons/kosa.png" height="24" width="24" alt = ":kosa:" title = ":kosa:" >');
    message = message.replace(/:kappa:/gi, '<img src = "../images/emoticons/kappa.png" height="24" width="24" alt = ":kappa:" title = ":kappa:" >');
    message = message.replace(/:fail:/gi, '<img src = "../images/emoticons/fail.png" height="30" width="30" alt = ":fail:" title = ":fail:" >');
    message = message.replace(/:snipe:/gi, '<img src = "../images/emoticons/snipe.png" height="24" width="24" alt = ":snipe:" title = ":snipe:" >');
    message = message.replace(/:live:/gi, '<img src = "../images/emoticons/live.png" width="130" alt = ":live:" title = ":live:" >');
    message = message.replace(/:facepalm:/gi, '<img src = "../images/emoticons/facepalm.gif" alt = ":facepalm:" title = ":facepalm:" >');
    message = message.replace(/:banana:/gi, '<img src = "../images/emoticons/banana.gif" alt = ":banana:" title = ":banana:" >');
    message = message.replace(/:like:/gi, '<img src = "../images/emoticons/like.png" alt = ":like:" title = ":like:" >');
    message = message.replace(/:loser:/gi, '<img src = "../images/emoticons/loser.png" alt = ":loser:" title = ":loser:" >');
    message = message.replace(/script/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/meta/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/SCAM/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/scam/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/s c a m/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/S.C.A.M/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/SC*AM/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/s.c.a.m/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/rigged/gi, 'I LOVE CSGOURBAN.COM !');
    message = message.replace(/:putin:/gi, '<a href="https://www.youtube.com/watch?v=v_0okIxnXcw" target="_blank"><img style="max-width:100%;" src = "../images/emoticons/putin.jpg" alt = ":putin:" title = ":putin:" ></a>');

    var msg = '';
    msg += '<div class="chat_message">';
    msg += '<div class="top">';
    msg += '<div class="left_image">';
    msg += '<div class="image_overlay img-circle">';
    msg += '<img class="img-circle" src="' + avatar + '" alt="">';
    msg += '</div>';
    msg += '</div>';
    msg += '<div class="right_info">';
    msg += '<div class="clock">';
    msg += '<img src="img/clock.png" alt=""><span class="time">x</span>';
    msg += '</div>';
    if (isAdmin == 1) {
        msg += '<a href="javascript:void(0);" userID="' + messageUserID + '" class="user_name admin">' + name + '</a>';
    } else if (isMod == 1) {
        msg += '<a href="javascript:void(0);" userID="' + messageUserID + '" class="user_name mod">' + name + '</a>';
    } else if (isStreamer == 1) {
        msg += '<a href="javascript:void(0);" userID="' + messageUserID + '" class="user_name streamer"><i class="fa fa-twitch"></i> ' + name + '</a>';
    } else {
        msg += '<a href="javascript:void(0);" userID="' + messageUserID + '" class="user_name">' + name + '</a>';
    }
    if (isAdmino == true) {
        msg += '<i class="fa fa-cogs admin_options" userID="' + messageUserID + '" onclick="banUserGenerate(this)"></i>';
    }
    msg += '</div>';
    msg += '</div>';
    msg += '<div class="message">';
    msg += message;
    msg += '</div>';
    msg += '</div>';
    msg += '';
    $('#chatmessages.' + room + '').append(msg);
    $('.recents_box').mCustomScrollbar("scrollTo", 'bottom');
    getUserProfile();
}

function banUserGenerate(t) {
    console.log($(t).attr('userID'));
    var bano = '';
    bano += '<div class="banPlayer" style="display: block;">';
    bano += '<div class="modalo">';
    bano += '<button data-remodal-action="close" class="remodal-close"></button>';
    bano += '<h1>Choose days for ban</h1>';
    bano += '<input type="hidden" value="" class="banthisplayer">';
    bano += '<input class="form-control howmuchdays" type="text" placeholder="1">';
    bano += '</p>';
    bano += '<br>';
    bano += '<button style="margin-right: 5px;" class="btn btn-success closo">SAFE</button>';
    bano += '<button id="banPlayer" class="btn btn-danger">BAN</button>';
    bano += '</div></div>';
    bano += '</div>';
    $('body').append(bano);
    $('.banthisplayer').val($(t).attr('userID'));
    $('.remodal-close, .closo').on('click', function () {
        $('.banPlayer').remove();
    });
    $('#banPlayer').on('click', function () {
        BanPlayer();
    });
}
// setTimeout(function () {
//     $('.admin_options').on('click', function () {
//         console.log($(this).attr('userID'));
//         $('#banthisplayer').val($(this).attr('userID'));
//     });
// }, 5000);

function BanPlayer() {
    var player = $('.banthisplayer').val();
    var days = $('.howmuchdays').val();
    $.ajax({
        url: '/user/timeout',
        method: 'post',
        dataType: 'json',
        data: {
            timeoutTime: days,
            userid: player
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (data) {
            $('.banPlayer').remove();
            if (data.success == 0) {
                alertify.error(data.response);
            } else {
                alertify.success(data.response);
            }
        }
    });
}
function playSound(name) {
    var player = $('#' + name + '')[0];
    player.currentTime = 0;
    player.play();
}

function getFormattedPrice(cents) {
    if (typeof cents !== 'number') {
        cents = parseInt(cents);
    }
    var price = cents / 100;
    if (cents % 100 === 0) { //If it is an even dollar, add the .00
        price = price + '.00';
    } else if (cents % 10 === 0) { //If it is like $3.40, add the trailing 0
        price = price + '0';
    }

    return '' + price;
}
function sendCoins() {
    var tosteamID = $('#tosteamid').val();
    var much = $('#howmuchcoins').val();
    $.ajax({
        type: 'POST',
        url: '/transferCoins',
        dataType: 'json',
        data: {
            tosteamID: tosteamID,
            much: much
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (data) {
            $('#tosteamid').val('');
            $('#howmuchcoins').val('');
            if (data.success == 0) {
                alertify.error(data.response);
            } else {
                alertify.success(data.response);
                $('.remodal-close').trigger('click');
            }
        }
    });
}
function getUserProfile() {
    $('.openprofile').on('click', function () {
        var ID = $(this).attr('userID');
        $.getJSON('/getUserProfile/' + ID + '', function (data) {
            $('.user_' + ID + '').remove();
            var useros = '<div style="display: none;" class="user_' + ID + ' profile"><div class="overlay"><div class="box "><div class="fa fa-close closer"></div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">';
            useros += '<img src="' + data['avatar'] + '" height="125"/></div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><br>';
            useros += '<h3><b class="text-uppercase">' + data['nick'] + '</b></h3>';
            useros += '<h5>Joined on ' + data['created_at'] + '</h5>';
            useros += '<hr>';
            useros += '<div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">';
            useros += '<div class="col-md-8 col-md-8 col-sm-12 col-xs-24"><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">' + data['refs'] + '</div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><b>REFERRALS</b></div></div>';
            useros += '<div class="col-md-8 col-md-8 col-sm-12 col-xs-24"><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">' + data['totalBet'] + '</div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><b>TOTAL BET</b></div></div>';
            useros += '<div class="col-md-8 col-md-8 col-sm-12 col-xs-24"><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">USER STEAM PROFILE</div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><a style="color:#333;font-size: 20px;text-decoration: none !important;" class="fa fa-steam" href="' + data['url'] + '"></a></div>';
            useros += '</div></div><hr>';
            useros += '<div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><div class="col-md-8 col-md-8 col-sm-12 col-xs-24">';
            useros += '<div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">' + data['totalWith'] + '</div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><b>TOTAL WITHDRAW</b></div>';
            useros += '</div><div class="col-md-8 col-md-8 col-sm-12 col-xs-24"><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">' + data['totalDepo'] + '</div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><b>TOTAL DEPOSITS</b></div>';
            useros += '</div><div class="col-md-8 col-md-8 col-sm-12 col-xs-24">';
            useros += '<div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center">' + data['coins'] + '</div><div class="col-lg-24 col-md-24 col-sm-24 col-xs-24 no_padding text-center"><b>BALANCE</b></div>';
            useros += '</div></div></div></div></div></div>';
            useros += '';
            useros += '';
            $('body').append(useros);

            $('.profile .closer').on('click', function () {
                $('.profile').fadeOut();
                $('.profile').remove();
            });
            setTimeout(function () {
                $('.user_' + ID + '').fadeIn();
            }, 1000);
        });
    });
};
(function ($) {
    $.fn.countTo = function (options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function () {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1500,  // how long it should take to count between the target numbers
        refreshInterval: 10,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };
})(jQuery);