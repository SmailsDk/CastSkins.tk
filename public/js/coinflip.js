$(function () {

    $('#clearInput').on('click', function () {
        clearInput();
    });
    $('.choose_fr').on('click', function () {
        if($('#betammount').val() < 1) {
            alertify.error('You need to select ammount first.');
            return;
        }
        if($(this).attr('id') == 'choose_tt') { 
            $('#choosen_fr').val('tt');
        } else if($(this).attr('id') == 'choose_ct') {
            $('#choosen_fr').val('ct');
        }
        $('.choose_fr').removeClass('active');
        $(this).addClass('active');
    });
    $('#create_room').on('click', function() {
        if ($('#betammount').val() < 1) {
            alertify.error('You need to select ammount first.');
            return;
        }
        if($('#choosen_fr').val() == '') {
            alertify.error('You need to select side first.');
            return;
        }
        $('#create_room').html('Please wait...');
        $.ajax({
            url: '/makeCoinflip',
            method: 'post',
            dataType: 'json',
            data: {
                ammount: $('#betammount').val(),
                side: $('#choosen_fr').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function (response) {

                if (response) {
                    if (response.success == 0) {
                        var type = 'info';
                        alertify.success(response.response);
                    } else {
                        var type = 'success';
                        if(response.id) {
                            window.location.href = '/room/'+response.id+'';
                        }
                    }

                }
            }
        });

    })
    $('.joinroom').on('click', function () {
        var roomID = $(this).attr('id');
        $.ajax({
            url: '/joinRoom',
            method: 'post',
            dataType: 'json',
            data: {
                roomID: roomID
            },
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function (response) {
                if (response) {
                    if (response.success == 0) {
                        alertify.error(response.response);
                        var type = 'info';
                    } else {
                        alertify.success(response.response);
                        var type = 'success';
                    }
                    setTimeout(function() {
                        if(type == 'success') {
                            window.location.href = '/room/'+roomID+'';
                        }
                    },2000);
                }
            }
        });

    })
    $('#setActive').on('click', function () {
        var roomID = $(this).attr('id');
        $.ajax({
            url: '/setActive',
            method: 'post',
            dataType: 'json',
            data: {
                roomID: $('#roomID').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function (response) {

            }
        });

    })

});
function clearInput() {
    $('#betammount').val(0);
}
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
            alertify.error('You need to be logged in!');
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
            alertify.error('You need to be logged in!');
        }
    });
}

