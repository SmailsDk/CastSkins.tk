var _ = require('lodash');
var server = require('http').Server();
var io = require('socket.io')(server);
var mysql = require('mysql');
var async = require("async");
var request = require('request');


var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});

var botSteamID = '76561198295495923';
var itemsShowLimit = 999999999999999999;

server.listen(1119);

io.on('connection', function (socket) {
    emitInventory(socket);
});

function emitInventory(socket) {
    request('http://steamcommunity.com/profiles/76561198295495923/inventory/json/730/2', function (error, response, body) {
        if (error) console.log(error);
        if (!error && response.statusCode == 200) {
            var inventory = JSON.parse(body);
            var descriptions = inventory.rgDescriptions;
            var items = inventory.rgInventory;
            var items_names = [];

            var getLockedItems = function (callback) {
                connection.query('SELECT * FROM withdraw_que WHERE status != "escrow"', function (err, rows) {
                    if (err) {
                        console.log(err);
                        return;
                    }
                    var locked_items = [];
                    for (var i in rows) {
                        locked_items.push(rows[i].assetID);
                    }
                    callback(locked_items);
                });
            };

            getLockedItems(function (locked_items) {
                connection.query('SELECT * FROM items', function (err, rows) {

                    if (err) console.log(err);
                    //console.log(locked_items);
                    //console.log(locked_items.indexOf(6007621180));
                    Object.keys(items).map(function (value, index) {

                                var key = items[value].classid + '_' + items[value].instanceid;
                                var price_key = _.findKey(rows, {"marketName": descriptions[key].market_hash_name});
if(rows[price_key] != undefined) {
                                var item_price = rows[price_key].avgPrice30Days;
                                //console.log(items[value].id + '  ');
                                //console.log(locked_items.indexOf(items[value].id));
                                if (item_price > 0 && locked_items.indexOf(parseInt(items[value].id)) < 0 && descriptions[key]['tradable'] > 0) {
                                    var thisoname = descriptions[key].market_hash_name;
                                    //if (thisoname.indexOf("Case") > -1) {
                                    //   // console.log('is case');
                                    //} else {
                                    items_names.push({
                                        "name": descriptions[key].market_hash_name,
                                        "price": (item_price * 10) * 1.2,
                                        "img": descriptions[key].icon_url,
                                        "assetid": items[value].id,
                                        "classid": items[value].classid,
                                        "instanceid": items[value].instanceid,
                                        "color": descriptions[key]['name_color'],
                                        "inspect": descriptions[key]['actions']
                                    });
                                    //}
                                }

                                return;
} else {
    return;
}
                        }
                    );
                    socket.emit('inventory', items_names);


                });


            });


        }
    });
}
