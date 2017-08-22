var mess_id = 0;
var q = "SELECT * FROM chat ORDER BY ID DESC LIMIT 1";
var q3 = "SELECT * FROM chat ORDER BY ID DESC LIMIT 15";
var q2 = "SELECT * FROM chat ORDER BY ID DESC LIMIT 1";
var antiSpam = require('socket-anti-spam');
var io = require('socket.io').listen(45334);
var mysql = require('mysql');
var _ = require('lodash');
var Promise = require('promise');
var bigInt = require("big-integer");

var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});


antiSpam.init({
    banTime: 30,            // Ban time in minutes
    kickThreshold: 10,       // User gets kicked after this many spam score
    kickTimesBeforeBan: 5,  // User gets banned after this many kicks
    banning: true,          // Uses temp IP banning after kickTimesBeforeBan
    heartBeatStale: 40,     // Removes a heartbeat after this many seconds
    heartBeatCheck: 4,      // Checks a heartbeat per this many seconds
    io: io,          // Bind the socket.io variable
});


var chato = io.on('connection', function (socket) {
    connection.query(q3, function (err, rows, fields) {
        if (rows.length > 0) {
            socket.emit('all-messages', true);
        } else {
            socket.emit('no-messages', '0');
        }
    });
});




setInterval(function () {
    connection.query(q2, function (err, rows, fields) {
        if (rows.length > 0) {
            var ThisMess_id = rows[0]['id'];
            if (ThisMess_id > mess_id) {
                mess_id = ThisMess_id;
                chato.emit('new_message', rows[0]);
            }
        }
    });
}, 1500);
chato.on('refreshChatrefreshChat', function (data) {
    connection.query(q3, function (err, rows, fields) {
        if (rows.length > 0) {
            chato.emit('all-messages', true);
        } else {
            chato.emit('no-messages', '0');
        }
    });
});

