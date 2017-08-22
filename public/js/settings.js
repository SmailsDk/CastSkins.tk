$("[name='win_sound']").bootstrapSwitch();
$('input[name="win_sound"]').on('switchChange.bootstrapSwitch', function(event, state) {
    if(state == false) {
        $.cookie('win_sound', 'false', {expires: 99999, path: '/'});
    } else {
        $.cookie('win_sound', 'true', {expires: 99999, path: '/'});
    }
});
if ($.cookie('win_sound') == 'false') {
    $("[name='win_sound']").bootstrapSwitch('state', false);
} else {
    $("[name='win_sound']").bootstrapSwitch('state', true);
}
$('.saveTrade').on('click', function() {
    $.ajax({
        type: 'POST',
        url: '/saveTradeToken',
        dataType: 'json',
        data: {
            tradeURL: $('#tradelink').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (data) {
            if (data.error != 'null') {
                alertify.error(data.error);
            } else {
                alertify.success('You changed your tradeURL with success.');
            }
            // console.log(data);
        }

    });
});
$('.saveEmail').on('click', function () {
    $.ajax({
        type: 'POST',
        url: '/saveEmail',
        dataType: 'json',
        data: {
            email: $('#email').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (data) {
            if (data.error != 'null') {
                alertify.error(data.error);
            } else {
                alertify.success('We sent activation email to your mailbox, please activate your email. Please check in spam first :)');
            }
            // console.log(data);
        }

    });
});
$.get( "badwords.txt", function( data ) {
    data = data.replace(/\n/g, "|");
    data = data.replace(/ /g, "|");
    console.log(data);
});
