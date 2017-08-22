var withdraws = io('37.59.1.92:1119');
$('#inventory-skins').html('Loading items, please wait.');

withdraws.on('inventory', function (data) {
    $('#inventory-skins').html('Loading items, please wait.');
    var html = '';
    var temp = [];
    $.each(data, function (key, value) {
        temp.push({v: value, k: key});
    });
    temp.sort(function (a, b) {
        if (parseInt(a.v.price) > parseInt(b.v.price)) {
            return -1
        }
        if (parseInt(a.v.price) < parseInt(b.v.price)) {
            return 1
        }
        return 0;
    });
    // $.each(temp, function (index, value) {
    //     html += '<li id="' + value.v.assetid + '" name="' + value.v.name + '" class="itemInWithdraw" style="">' +
    //         '<img src="https://cdn0.iconfinder.com/data/icons/fatcow/32x32/tick.png" class="tick"/>' +
    //         '<img class="item itemPic img-responsive" src="https://steamcommunity-a.akamaihd.net/economy/image/' + value.v.img + '/70x70">' +
    //         '<div class="itemName"><span>' + value.v.name + '</span></div>' +
    //         '<div class="itemPrice"><span>' + value.v.price + ' COINS</span></div>' +
    //         '</li>';
    // });
    $.each(temp, function (index, value) {
        html += '<div style="" class="item" >' +
            '<i id="locked" class="fa fa-diamond"></i>' +
            '<img src="https://steamcommunity-a.akamaihd.net/economy/image/class/730/' + value.v.classid + '/100fx100fx">' +
            '<p class="name">' + value.v.name + '</p>' +
            '<p class="price">' + value.v.price + ' <i class="fa fa-diamond"></i></p>' +
            '</div>';
    });


    $('#inventory-skins').html('');
    //$('.inventory').append(html);
    $('#inventory-skins').append(html);

    $('#inventory-skins .item').on('click', function () {


        $(this).toggleClass('active');
        var cnt = $('.item.active');
        if (cnt.length > 10) {
            $(this).removeClass('active');
            alertify.error('You can withdraw max 10 items at time');
        }
        var trade = $('#inventory-skins .item.active');
        var selected = 0;
        $.each(trade, function (key, value) {
            selected = selected + parseInt($('#' + value['id']).attr('price'));
        });
        $('.selected_am').html(selected);
    });
    $('#unselect').on('click', function () { 
        $('.selected_am').html('0');
        $('#inventory-skins .item').removeClass('active');
    });
    $('#deposit_selected').click(function (e) {
        alertify.error('Withdraw is only for users who deposit at least 5000 diamonds.');
        return;
        e.preventDefault();
        e.stopPropagation();
        $('#deposit_selected').attr('disabled', true);
        setTimeout(function() {
            $('#deposit_selected').attr('disabled', false);
        },30000);
        var trade = $('#inventory-skins .item.active');
        $('#inventory-skins .item').removeClass('active');
        if (trade.length > 0) {
            var selected_items = [];
            $.each(trade, function (key, value) {
                selected_items.push({"assetid": value['id'], "name": $('#' + value['id'] + ' .name').html()});
            });
            $.ajax({
                url: '/withdrawSelected',
                method: 'post',
                dataType: 'json',
                data: {
                    items: selected_items
                },
                headers: {
                    'X-CSRF-TOKEN': $('#_token').val()
                },
                success: function (data) {
                    if (data) {

                        if (data.success == 0) {
                            alertify.error(data.response);
                        } else {
                            alertify.success(data.response);
                        }
                    }
                }
            });
            // console.log(selected_items);
        }
    });
});


