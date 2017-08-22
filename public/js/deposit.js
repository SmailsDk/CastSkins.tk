


var withdraws = io('37.59.1.92:11199');
$('#inventory-skins').html('');
var valued = 0;
$('.reloadInventory').on('click', function() {
    valued = 0;
    withdraws.emit('getPlayerInv', $(this).attr('userID'));
    $('.reloadInventory').attr('disabled', true);
    setTimeout(function() {
        $('.reloadInventory').attr('disabled', false);
    },30000);
    $('#inventory-skins').html('Loading items, please wait.');
});
withdraws.on('inventory', function (data) {
    $('#inventory-skins').html('Loading items, please wait.');
    var html = '';
    var temp = [];
    $.each(data, function (key, value) {
        temp.push({v: value, k: key});
    });
    temp.sort(function (a, b) {
        if (a.v.price > b.v.price) {
            return -1
        }
        if (a.v.price < b.v.price) {
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
        html += '<div class="item" price="' + (value.v.price).toFixed(2) + '" id="' + value.v.assetid + '" asset-id="' + value.v.assetid + '">' +
            '<i id="locked" class="fa fa-diamond"></i>' +
            '<img src="http://steamcommunity-a.akamaihd.net/economy/image/' + value.v.img + '/110fx50f">' +
            '<p class="name">' + value.v.name + '</p>' +
            '<p class="price">' + (value.v.price).toFixed(2) + ' <i class="fa fa-diamond"></i></p>' +
            '<div class="color" style="background-color: #' + value.v.color + '"></div>' +
            '</div>';
        valued = valued + value.v.price;
        $('.loaded_price').html(valued);
        $('.usd_val').html(valued/1000);
    });


    $('#inventory-skins').html('');
    //$('.inventory').append(html);
    $('#inventory-skins').append(html);

    $('#inventory-skins .item').on('click', function(){
        $(this).toggleClass('active');
        var cnt = $('.item.active');
        if(cnt.length > 20) {
            $(this).removeClass('active');
            alertify.error('You can deposit max 20 items at time');
        }
        var trade = $('#inventory-skins .item.active');
        var selected = 0;
        $.each(trade, function (key, value) {
            selected = selected + parseInt($('#'+value['id']).attr('price'));
        });
        $('.selected_am').html(selected);
    });
    $('#unselect').on('click', function(){
        $('.selected_am').html('0');
        $('#inventory-skins .item').removeClass('active');
    });
    $('#deposit_selected').click(function (e) {
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
                selected_items.push({"assetid": value['id'], "name": $('#'+value['id']+' .name').html()});
            });
            $.ajax({
                url: '/depositSelected',
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
        } else {
            alertify.error('No items selected.');
        }
    });
});


