var _ = require('lodash');
var server = require('http').Server();
var io = require('socket.io')(server);
var mysql = require('mysql');
var async = require("async");
var request = require("request");
var randtoken = require('rand-token');
var random = require("random-js")();

var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});
connection.connect(function (err) {
    if (err) {
        console.error('error connecting database: ' + err.stack);
        process.exit(1);
        return;
    }
});


server.listen(2020);


var numbers;
var bust_number;
reGeneratePatern();
var currentGameID = 'SELECT * FROM bust_history ORDER BY id DESC LIMIT 1';


var bust = io.on('connection', function (socket) {
    getLastBusts();
    socket.on('getCurrent', function (data) {
        connection.query('SELECT count(*) as cnt FROM bust_bets WHERE userID64="' + data + '" AND cashed_out="0" AND gameID="' + currentBustID + '"', function (err, rows) {
            if (rows[0]['cnt'] == 1) {
                var cashoutData = (actualBust / 1000).toFixed(3);
               // console.log(cashoutData);
                socket.emit('cashout', cashoutData);
            }
        });
        //console.log((actualBust / 1000).toFixed(3));
    });
});

// chato.emit('all-messages', true);
// chato.on('refreshChatrefreshChat', function (data) {})
function getRandom() {
    reGeneratePatern();
    bust_number = numbers[Math.floor(Math.random() * numbers.length)];
    return bust_number;
}
function reGeneratePatern() {
    numbers = [];
    for (var i = 0; i < 20; i++) {
        numbers.push(((random.integer(100, 101)) / 100).toFixed(2));
    }
    for (var i = 0; i < 80; i++) {
        numbers.push(((random.integer(105, 111)) / 100).toFixed(2));
    }
    for (var i = 0; i < 140; i++) {
        numbers.push(((random.integer(100, 201)) / 100).toFixed(2));
    }
    for (var i = 0; i < 62; i++) {
        numbers.push(((random.integer(100, 501)) / 100).toFixed(2));
    }
    for (var i = 0; i < 38; i++) {
        numbers.push(((random.integer(100, 1801)) / 100).toFixed(2));
    }
    for (var i = 0; i < 10; i++) {
        numbers.push(((random.integer(100, 8901)) / 100).toFixed(2));
    }
    for (var i = 0; i < 3; i++) {
        numbers.push(((random.integer(100, 24501)) / 100).toFixed(2));
    }
}
function getBetsInfo() {
    connection.query(currentGameID, function (err, rows) {
        var curGameID = rows[0]['id'];
        connection.query('SELECT cashed_out,avatar,url,nick,ammount,userID64,cashed_out FROM bust_bets ' +
            'WHERE gameID="' + curGameID + '" ORDER BY ammount DESC', function (err, rows) {
            bust.emit('getUsersInfo', rows);
        });
    });
};
setInterval(StartGame, 1000);
setInterval(getPlacedBets, 1000);
var currentBustID = 0;
var started = false;
var bustNumber;
var actualBust = 0;
Number.prototype.between = function (a, b, inclusive) {
    var min = Math.min(a, b),
        max = Math.max(a, b);

    return inclusive ? this >= min && this <= max : this > min && this < max;
}
function getLastBusts() {
    connection.query('SELECT * FROM bust_history WHERE bust_number!="0" ORDER BY id DESC LIMIT 5', function (err, rows) {
        bust.emit('last_busts', rows);
    });
}
function StartGame() {
    if (started == false) {
        started = true;
        bust.emit('waittime', true);
        console.log('Start in 10 seocnds');
        setTimeout(function () {
            bustNumber = getRandom() * 1000;
            var intervalTime = '' + bustNumber + '0';
            console.log('BustNumber is : ' + bustNumber + '');
            actualBust = 1000;

            connection.query('SELECT * FROM bust_history WHERE bust_number="0" ORDER BY id DESC LIMIT 1', function (err, rows) {
                currentBustID = rows[0]['id'];
                console.log('Current ID : ' + currentBustID + '');
                connection.query('UPDATE bust_history SET started="1" WHERE id="' + currentBustID + '"', function (err, rows) {
                });
                var countdown = setInterval(function () {
                    var addon = 0;
                    if (bustNumber == 1000 && actualBust == 1000) {
                        bust.emit('bustDone', bustNumber);
                        console.log('' + (actualBust / 1000).toFixed(3) + '/' + (bustNumber / 1000).toFixed(3) + ' | ' + fixedBust + ' | Dodaje po : ' + addon + '');
                        reGeneratePatern();
                        clearInterval(countdown);
                        connection.query('UPDATE bust_history SET bust_number="' + (actualBust / 1000).toFixed(2) + '" WHERE id="' + currentBustID + '"', function (err, rows) {
                            if (err) {
                                console.log(err)
                            } else {
                                console.log(currentBustID);
                            }
                            request({
                                url: 'http://csgourban.com/createNewBust/G3MIFP27ejGeox5sUjkCbNcBalunF0JNCoCPJZMQkwNAmh96n3',
                                json: true
                            }, function (error, response, body) {
                                console.log('Bust Done');
                                getLastBusts();
                                started = false;
                                currentBustID = currentBustID + 1;
                            });
                        });
                    }
                    if (actualBust != bustNumber) {
                        var fixedBust = parseInt(actualBust.toFixed(0));
                        if (fixedBust < 1300) {
                            addon = 0.50;
                        } else if (fixedBust >= 1300 && fixedBust <= 2000) {
                            addon = 1;
                        } else if (fixedBust >= 2000 && fixedBust <= 5000) {
                            addon = 2;
                        } else if (fixedBust >= 5000 && fixedBust <= 20000) {
                            addon = 4;
                        } else if (fixedBust >= 20000 && fixedBust <= 49999) {
                            addon = 8;
                        } else if (fixedBust >= 50000 && fixedBust <= 100000) {
                            addon = 20;
                        } else if (fixedBust >= 100000 && fixedBust <= 200000) {
                            addon = 50;
                        } else {
                            addon = 200;
                        }
                        actualBust = actualBust + addon;
                        bust.emit('actualBust', actualBust);

                    }
                    if (actualBust == bustNumber || actualBust > bustNumber) {
                        bust.emit('bustDone', bustNumber);
                        console.log('' + (actualBust / 1000).toFixed(3) + '/' + (bustNumber / 1000).toFixed(3) + ' | ' + fixedBust + ' | Dodaje po : ' + addon + '');
                        reGeneratePatern();
                        clearInterval(countdown);
                        connection.query('UPDATE bust_history SET bust_number="' + (actualBust / 1000).toFixed(2) + '" WHERE id="' + currentBustID + '"', function (err, rows) {
                            if (err) {
                                console.log(err)
                            } else {
                                console.log(currentBustID);
                            }
                            request({
                                url: 'http://csgourban.com/createNewBust/G3MIFP27ejGeox5sUjkCbNcBalunF0JNCoCPJZMQkwNAmh96n3',
                                json: true
                            }, function (error, response, body) {
                                console.log('Bust Done');
                                getLastBusts();
                                started = false;
                                currentBustID = currentBustID + 1;
                            });
                        });
                    }
                }, 4);
            });

        }, 10000);
    } else {
        /*
         *
         * Aktualnie chodzi timer - co sobie tam nie wymyslisz mozesz dodac :)
         *
         */
    }
}
function getPlacedBets() {
    connection.query('SELECT * FROM bust_bets WHERE gameID="' + currentBustID + '" ORDER BY amount DESC', function (err, rows) {
        bust.emit('allBets', rows);
    });
}


