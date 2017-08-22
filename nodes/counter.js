var antiSpam = require('socket-anti-spam');
var io = require('socket.io').listen(1148);
var mysql = require('mysql');
var _ = require('lodash');

var connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '231190',
    database: 'test'
});


antiSpam.init({
    banTime: 60,            // Ban time in minutes
    kickThreshold: 5,       // User gets kicked after this many spam score
    kickTimesBeforeBan: 3,  // User gets banned after this many kicks
    banning: true,          // Uses temp IP banning after kickTimesBeforeBan
    heartBeatStale: 40,     // Removes a heartbeat after this many seconds
    heartBeatCheck: 4,      // Checks a heartbeat per this many seconds
    io: io,          // Bind the socket.io variable
});

var request = require("request");
var connectedUsers = 0;
var cntOnline = io.on('connection', function (socket) {
    connectedUsers++;
    socket.on('disconnect', function () {
        connectedUsers--;
    });
});


setInterval(function () {
    cntOnline.emit('usersOnline', connectedUsers);
}, 500);