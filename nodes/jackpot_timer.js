var _ = require('lodash');
var server = require('http').Server();
var io = require('socket.io')(server);
var mysql = require('mysql');
var async = require("async");
var request = require("request");
var randomstring = require("randomstring");

var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});

server.listen(2223);

var gameTime = 126000; //125 sekund + 2 (na polaczenie z klientem mijaja 1-2 sekundy)
var end_at = 0; // dont touch


// Add a connect listener
var emiterek = io
    .on('connection', function (socket) {
        //getLatestColors(socket);
        //emitujemy timer do nowo polaczonych graczy
        socket.emit('startTimer', (new Date).getTime(), end_at);
    });
var winnerID;



setInterval(function () {
    connection.query('SELECT * FROM history ORDER BY id DESC LIMIT 1', function (err, rows) {
        var endTime = rows[0]['endTime'];
        var winner = rows[0]['winnerSteamId64'];
        var allItemsJson = rows[0]['endTime'];
        //console.log(rows);
        if (winner != null) {

            var roundHash = randomstring.generate({
                length: 25,
                charset: '=!%123456789lcanwfhuiwnexyfgeragncacsbox'
            });
            //console.log('Nie ma w histori nowej rundy, trzeba ją dodać. Hash: ' + roundHash + '');
            connection.query('INSERT INTO history SET roundHash="' + roundHash + '", endTime="0"', function (err, rows) {
                if (err != undefined) {
                    console.log(err);
                }
            });
        }
    });
}, 3000);

setInterval(function () {
    CheckTimerr();
}, 1000);

function CheckTimerr() {
    // aktualnie brak timera
    if (end_at <= (new Date).getTime()) {
        // pobieramy uzytkownikow z aktualnej gry
        connection.query('SELECT (SELECT COUNT(DISTINCT ownerSteamId64) FROM currentPot) as players_count, count(*) as cnt,(SELECT id FROM history ORDER BY id DESC LIMIT 1) as gameid, (SELECT endTime FROM history ORDER BY id DESC LIMIT 1) as endTime FROM currentPot', function (err, rows) {
            if (rows.length != 0) {
                var users = rows[0]['players_count'];
                var endTime = rows[0]['endTime'];
                var roundId = rows[0]['gameid'];

                if (users > 1 && endTime == 0) {
                    var unixTime = parseInt(new Date().getTime() / 1000, 10);

                    connection.query('UPDATE history SET endTime="' + unixTime + '" WHERE id= ' + roundId, function (err, rows) {
                        if (err) {
                            console.log('Wystąpił błąd');
                            console.log(err);
                        } else {
                            //puszczamy nowy timer
                            emiterek.emit('bet', true);
                            end_at = (new Date).getTime() + 127000;
                            console.log('Starting timer...');
                            emiterek.emit('startTimer', (new Date).getTime(), end_at);

                            setTimeout(proceedWinners, 128000, roundId);
                            setTimeout(function() {
                                emiterek.emit('bet', false);
                            },105000);
                            setTimeout(function () {
                                emiterek.emit('bet', true);
                            }, 140000);
                        }
                    });
                }
            }
        });
    } else {
        /*
         * Aktualnie chodzi timer - co sobie tam nie wymyslisz mozesz dodac :)
         */
    }
}
setInterval(function () {
    if (winnerID != '') {
        emiterek.emit('clearGame', winnerID);
        setTimeout(function () {
            winnerID = '';
            emiterek.emit('clearWinner', true);
        }, 950);
        return;
    }
}, 1000);
function proceedWinners(roundID) {
    var winnerIDE = '';
    request({
        url: 'http://csgourban.com/getWinner2124954id273',
        json: true
    }, function (error, response, body) {
        if (body == undefined) {
            return;
        }
        winnerID = body['winnerSteamId'];
        winnerIDE = body['winnerSteamId'];
        var haveCSBOX = body['haveCSBOX'];
        var nick = body['nick'];
        var prowizja = body['prowizja'];

        console.log('Have : ' + haveCSBOX + ' . Nick: ' + nick + ' . Zabrałem ' + prowizja + '% prowizji.');
        if (body['allPlayers'] == undefined) {
            return;
        }
        console.log('Daj zwycięzce');
        var winnerItems = body['tradeItems'];

        var toKeep = body['profitItems'];
        var WinnerTradeToken = body['winnerTradeToken'];
        if (WinnerTradeToken == undefined || WinnerTradeToken == '') {
            if (winnerIDE != undefined) {
                sendMessage(winnerIDE, 'Nie wprowadziłeś tradeURL na stronie i nie mam jak ci wysłać oferty, zgłoś problem poprzez formularz kontaktowy na stronie w celu odebrania wygranej');
                return;
            }
        }
        var item = [], num = 1;
        var itemo = [], numo = 1;
        var itemos = [], numos = 1;
        var itemToSend = [];
        var itemToKepp = [];
        var numero = 0;
        var itemosID = [];
        var itemssToSend = [];
        var itemsToSend = winnerItems;
        var itemsToKepp = toKeep;

        async.each(winnerItems, function (value, callback) {
            itemssToSend.push(value);
            callback();
        }, function (err) {
            async.each(itemssToSend, function (value, callback) {
               // console.log(itemssToSend);
                itemosID.push({"name": value['itemName']});
                numo++;
                callback();
            }, function (err) {
                if (err) {
                    console.log('Ełoł.');
                    return;
                } else {
                    if (numo > 0) {
                        async.each(itemosID, function (value, callback) {
                            // console.log(value);
                            var itemnamed = value['name'];
                            var roundID = body['gameID'];
                            connection.query('INSERT INTO jackpot_que SET user_id="' + winnerIDE + '",' +
                                'assetID="0",' +
                                'itemname="' + itemnamed + '", roundID="' + roundID + '"', function (error, results, fields) {
                                if (error) {
                                    socket.emit('processing', 'error');
                                    console.log(error);
                                    return;
                                } else {
                                    callback();
                                }
                            });
                        }, function (err) {
                            if (err) {
                                return;
                            } else {
                                console.log('Dodalem itemy do kolejki wysyłania.');
                            }
                        });
                    }
                }
            });
        });
        return;
        var prevID = 0;


    });
}

