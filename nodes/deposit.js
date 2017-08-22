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
var itemsShowLimit = 99999;

server.listen(11199);

io.on('connection', function (socket) {

});
var emiterek = io
    .on('connection', function (socket) {
        socket.on('getPlayerInv', function (data) {
            emitInventory(socket, data);
        });
    });

function emitInventory(socket, userID) {
    request('http://steamcommunity.com/profiles/' + userID + '/inventory/json/730/2', function (error, response, body) {
        if (error) console.log(error);

        if (!error && response.statusCode == 200) {
            var inventory = JSON.parse(body);
            var descriptions = inventory.rgDescriptions;
            var items = inventory.rgInventory;
            var items_names = [];

            connection.query('SELECT marketName,avgPrice30Days,sellOrders FROM items', function (err, rows) {
                if (err) return;
                Object.keys(items).map(function (value, index) {
                        if (index <= itemsShowLimit) {
                            var key = items[value].classid + '_' + items[value].instanceid;
                            var price_key = _.findKey(rows, {"marketName": descriptions[key].market_hash_name});
                            if (rows[price_key] != undefined) {
                                var item_price = rows[price_key].avgPrice30Days;
                                var sellOrders = rows[price_key].sellOrders;
                                if (item_price > 0 && descriptions[key]['tradable'] > 0 && sellOrders > 10) {
                                        if ((item_price * 10) > 99) {
                                            items_names.push({
                                                "name": descriptions[key].market_hash_name,
                                                "price": item_price * 10,
                                                "img": descriptions[key].icon_url,
                                                "assetid": items[value].id,
                                                "classid": items[value].classid,
                                                "instanceid": items[value].instanceid,
                                                "color": descriptions[key]['name_color']
                                            });
                                        }
                                }
                            }
                            return;
                        }
                    }
                );
                socket.emit('inventory', items_names);
            });


        }
    });
}
