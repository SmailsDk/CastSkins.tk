var _ = require('lodash');
var server = require('http').Server();
var io = require('socket.io')(server);
var mysql = require('mysql');
var async = require("async");
var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});

server.listen(3214);
var SteamBot = false;
var WebApi = false;

var currentGameID = 'SELECT * FROM roulette_history ORDER BY id DESC LIMIT 1';
var request = require("request");

var emiterek = io
    .on('connection', function (socket) {

    });

setInterval(function () {
    getBetsInfo();
}, 1000);

function getBetsInfo() {
    connection.query(currentGameID, function (err, rows) {
        var curGameID = rows[0]['id'];
        connection.query('SELECT color,avatar,url,nick, sum(ammount) as placed, userID64,isStreamer,streamLink FROM placed_bets ' +
            'WHERE gameID="' + curGameID + '" GROUP BY userID64,color ORDER BY placed DESC', function (err, rows) {
            emiterek.emit('getUsersInfo', rows);
            var redCNT = 0;
            var blackCNT = 0;
            var greenCNT = 0;
            var goldCNT = 0;
            var Rett = {};
            async.each(rows, function (value, callback) {
                    if (value['color'] == 'red') {
                        redCNT = parseInt(redCNT) + parseInt(value['placed']);
                    } else if (value['color'] == 'purple') {
                        blackCNT = parseInt(blackCNT) + parseInt(value['placed']);
                    } else if (value['color'] == 'green') {
                        greenCNT = parseInt(greenCNT) + parseInt(value['placed']);
                    } else if (value['color'] == 'gold') {
                        goldCNT = parseInt(goldCNT) + parseInt(value['placed']);
                    }
                    callback();
                },
                function (err) {
                    Rett['red'] = redCNT;
                    Rett['black'] = blackCNT;
                    Rett['green'] = greenCNT;
                    Rett['gold'] = goldCNT;
                    emiterek.emit('getCNTS', Rett);
                }
            );
        });
    });
};

connection.connect(function (err) {
    if (err) {
        console.error('error connecting: ' + err.stack);
        return;
        WebApi = false;
    }
    WebApi = true;
});
