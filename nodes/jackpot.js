var _ = require('lodash');
var server = require('http').Server();
var io = require('socket.io')(server);
var mysql = require('mysql');
var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});
var md5 = require('MD5');
server.listen(2344);

var currentPot = "SELECT * FROM currentPot ORDER BY itemPrice DESC";
var getPlayers = ' SELECT *, count(currentPot.id) as cnt, sum(itemPrice) as userPutIN, ownerSteamId64 as steamid FROM currentPot LEFT JOIN users ON users.steamId64=currentPot.ownerSteamId64 group by ownerSteamId64 ORDER BY userPutIN DESC';
var lastItemInPot = "SELECT * FROM currentPot ORDER BY ID DESC LIMIT 1";
var currentPotValue = "SELECT * FROM currentPot ORDER BY ID DESC";
var getWholeHistory = "SELECT count(*) as Count FROM history ORDER BY ID DESC";

var lastJackpotData = 0;

var potItemsCount = 0;
var potValuation = 0;
var jackpot = io
    .on('connection', function (socket) {
        emitFirst(socket);
        updateAllGamesStats(socket);
    });

setInterval(function () {
    emitData(jackpot);
}, 1000);

function emitData(jackpot) {
    connection.query("SELECT roundHash FROM history ORDER BY id DESC LIMIT 1", function (err, rows, fields) {
        if (rows.length > 0) {
            jackpot.emit('roundHash', rows[0]);
        }
    });
    connection.query(currentPot, function (err, rows) {
        var thisCount = rows.length;
        if (thisCount > 0) {
            if (rows.length != lastJackpotData) {
                connection.query(getPlayers, function (err, rows, fields) {
                    jackpot.emit('allPlayers', rows);
                });
                jackpot.emit('PotItemsCount', rows.length);
                jackpot.emit('allItemsPot', rows);
                var potValue = 0;
                rows.forEach(function (value, index) {
                    var price = value['itemPrice'];
                    potValue += price;
                });
                jackpot.emit('potValue', potValue);
                updateAllGamesStats(jackpot);

                lastJackpotData = rows.length;
            }
        } else {
            jackpot.emit('PotItemsCount', 0);
            jackpot.emit('allItemsPot', 0);
            jackpot.emit('potValue', 0);
            jackpot.emit('allPlayers', 0);
        }
    });
}
function emitFirst(socket) {
    connection.query("SELECT roundHash FROM history ORDER BY id DESC LIMIT 1", function (err, rows, fields) {
        if (rows.length > 0) {
            socket.emit('roundHash', rows[0]);
        }
    });
    connection.query(currentPot, function (err, rows) {
        var thisCount = rows.length;
        if (thisCount > 0) {
            connection.query(getPlayers, function (err, rows, fields) {
                socket.emit('allPlayers', rows);
            });
            socket.emit('PotItemsCount', rows.length);
            socket.emit('allItemsPot', rows);
            var potValue = 0;
            rows.forEach(function (value, index) {
                var price = value['itemPrice'];
                potValue += price;
            });
            socket.emit('potValue', potValue);
        } else {
            jackpot.emit('PotItemsCount', 0);
            jackpot.emit('allItemsPot', 0);
            jackpot.emit('potValue', 0);
            jackpot.emit('allPlayers', 0);
        }
    });
}

function updateAllGamesStats(socket) {
    connection.query(getWholeHistory, function (err, rows) {
        socket.emit('historyCount', rows[0]['Count']);
    });
}