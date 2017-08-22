var _ = require('lodash');
var server = require('http').Server();
var io = require('socket.io')(server);
var mysql = require('mysql');
var async = require("async");
var request = require("request");
var randtoken = require('rand-token');

var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});

server.listen(2222);

connection.connect(function (err) {
    if (err) {
        console.error('error connecting database: ' + err.stack);
        process.exit(1);
        return;
    }
});


var winnerNumber = 99; // Aktualnie useless raczej
var gameTime = 42000; //45 sekund + 2 (na polaczenie z klientem mijaja 1-2 sekundy)
var end_at = 0; // dont touch

setInterval(CheckTimer, 1000);



var emiterek = io
    .on('connection', function (socket) {
        getLatestColors(socket);
        //emitujemy timer do nowo polaczonych graczy
        socket.emit('startTimer', (new Date).getTime(), end_at);
    });
/*
 wrazie co mozesz uzyc:
 emiterek.emit('counter', 45);
 emiterek.emit('xxxxx');
 */
var freeze = false;
function CheckTimer() {
    // aktualnie brak timera
    if (end_at <= (new Date).getTime() && freeze != true) {

        // pobieramy uzytkownikow z aktualnej gry
        connection.query('SELECT count(*) as cnt,(SELECT id FROM roulette_history ORDER BY id DESC LIMIT 1) as gameid, (SELECT endTime FROM roulette_history ORDER BY id DESC LIMIT 1) as endTime FROM placed_bets WHERE gameID = (SELECT id FROM roulette_history ORDER BY id DESC LIMIT 1)', function (err, rows) {
            var users = rows[0]['cnt'];
            var endTime = rows[0]['endTime'];
            var roundId = rows[0]['gameid'];
            if (users > 0 && endTime == 0) {
                var unixTime = parseInt(new Date().getTime() / 1000, 10);
                connection.query('UPDATE roulette_history SET endTime="' + unixTime + '" WHERE id= ' + roundId, function (err, rows) {
                    if (err) {
                        console.log('Wystąpił błąd');
                        console.log(err);
                    } else {
                        //puszczamy nowy timer
                        end_at = (new Date).getTime() + 32000;
                        console.log('Starting timer...');
                        emiterek.emit('startTimer', (new Date).getTime(), end_at);
                        setTimeout(function() {
                            freeze = true;
                        },28000);
                        setTimeout(proceedWinners, 32000, roundId);

                    }
                });
            }
        });

    } else {
        /*
         *
         * Aktualnie chodzi timer - co sobie tam nie wymyslisz mozesz dodac :)
         *
         */
    }
}
setInterval(function(){
    emiterek.emit('freeze', freeze);
},500);
function getLatestColors(socket) {
    connection.query("SELECT * FROM roulette_history WHERE numberWon != '99' ORDER BY id DESC LIMIT 10", function (err, rows) {
        socket.emit('lastColors', rows);
    });
};
// var token = randtoken.generate(50);
// connection.query('UPDATE acces_tokens SET token="'+token+'" WHERE id = "1"', function (error, results, fields) {});
function proceedWinners(roundID) {
    console.log('Updating winners...');
    request({
        url: 'http://csgourban.com/getWinningColor/G3MIFP27ejGeox5sUjkCbNcBalunF0JNCoCPJZMQkwNAmh96n3/'+roundID+'',
        json: true
    }, function (error, response, body) {
        console.log(body['number']);
        winnerNumber = body['number'];
        emiterek.emit('playsound', winnerNumber);
        emiterek.emit('clearGame', winnerNumber);
        setTimeout(function () {
            emiterek.emit('clearWinner', true);
        }, 1000);
        setTimeout(function () {
            freeze = false;
        }, 11000);
        getLatestColors(emiterek);
        // var token = randtoken.generate(50);
        // connection.query('UPDATE acces_tokens SET token="'+token+'" WHERE id = "1"', function (error, results, fields) {});
    });
    //proceeded = false;
}


// VVVVV NIE MAM POJECIA CO TO :D  NIE RUSZALEM ALE TEZ DO REFAKTORYZACJI


