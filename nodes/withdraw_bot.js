require('events').EventEmitter.prototype._maxListeners = 100;
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
server.listen(8788);

var request = require("request");
var admins = ['76561198269216247', '76561198258632124', '76561198023345468'];
var util = require('util');
var TradeOfferManager = require('steam-tradeoffer-manager');
var fs = require('fs');
var Steamcommunity = require('steamcommunity');
var ParentBot = require('steam-parentbot'); //change to 'steam-parentbot' if not running from examples directory
var Steam = ParentBot.Steam; //instance of the Steam object
var request = require("request");
var community = new Steamcommunity();
var ChildBot = function () {
    ChildBot.super_.apply(this, arguments);
};
util.inherits(ChildBot, ParentBot);

var Bot = new ChildBot('csgourban1', '231190', {
    apikey: '7D455934DCA6BE23F9653DAD3F00D89F', //steam api key, will be registered automatically if one isn't supplied
    sentryfile: 'csgourban1-new.sentry', //sentry file that stores steamguard info, defaults to username.sentry
    logfile: 'csgourban1-new.log', //filename to log stuff to, defaults to username.log
    guardCode: '7CD2K',
    //sharedSecret: '7kc5OaWfOpi21WPaIywZjgpH6Bc=', //shared secret, needed to automatically generate twoFactorCode
    //identitySecret: 'sAvVpvuAM44d5PlYdq0qpoMEK2g=', //identity secret, needed to automatically confirm trade offers, must be used with confirmationInterval
    confirmationInterval: 10000, //how often we should check for new trades to confirm in miliseconds, must be used with identitySecret
    richPresenceID: 730 //game to use rich presence with, don't include for no rich presence
});


var SteamTotp = require('steam-totp');

var botMain = function () {
    botMain.super_.apply(this, arguments);
}
util.inherits(botMain, ParentBot);

var TOTP = require('onceler').TOTP;
var totp = new TOTP('D2EUYJ7OKUV6TUKU');
var manager = new TradeOfferManager({
    "domain": "http://csgourban.com", // Our domain is example.com
    "language": "en", // We want English item descriptions
    "pollInterval": 1000 // We want to poll every 5 seconds since we don't have Steam notifying us of offers
});
if (fs.existsSync('polldata_witdhraw.json')) {
    manager.pollData = JSON.parse(fs.readFileSync('polldata_witdhraw.json'));
    console.log('WTF');
} else {
    console.log("Users file or master ssfn missing. Exiting..");
}


//Bot.steamUser.on('tradeOffers', function (number) {
//    if (number > 0) {
//        handleOffers();
//        bang('Przeglądam oferty :) Jest ich: ' + number + '');
//    }
//});

function sendMessage(steamID, message) {
    Bot.steamFriends.sendMessage(steamID, message);
}
var SteamWebLogOn = require('steam-weblogon');
var steamWebLogOn = new SteamWebLogOn(Bot.steamClient, Bot.steamUser);
Bot.steamClient.on('logOnResponse', function (logonResp) {
    //console.log('Event "logOnResponse": ' + JSON.stringify(logonResp));
    if (logonResp.eresult === Steam.EResult.OK) {

        console.log('Logged in!');
        SteamBot = true;
        reWebLogOn();

    }
    steamWebLogOn.webLogOn(function (webSessionID, cookies) {

        manager.setCookies(cookies, function (err) {
            if (err) {
                process.exit(1); // Fatal error since we couldn't get our API key
                return;
            }

            console.log("Got API key: " + manager.apiKey);
        });
        community.setCookies(cookies);
        community.startConfirmationChecker(30000, "identitySecret");
    });
});
Bot.steamClient.on('loggedOff', function () {
    SteamBot = false;
});
Bot.steamClient.on('loggedOn', function () {
    console.log("Logged in");
});
Bot.steamClient.on('error', function (e) {
    console.log('Wystąpił błąd : ' + e + '');
});

Bot.connect();

manager.on('pollData', function (pollData) {
    fs.writeFile('polldata.json', JSON.stringify(pollData));
    console.log('Pulled Data');
});
var handleq = true;
manager.on('sentOfferChanged', function (offer, oldState) {

    console.log("Offer #" + offer.id + " changed: " + TradeOfferManager.getStateName(oldState) + " -> " + TradeOfferManager.getStateName(offer.state));
    if (TradeOfferManager.getStateName(offer.state) == 'Active') {
        connection.query('UPDATE ofers SET status="Waiting for user confirmation" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'Declined') {
        connection.query('UPDATE ofers SET status="Declined by user" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
            connection.query('SELECT * FROM ofers WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                var userID = offer.partner.getSteamID64();
                var cost = results[0]['value'];
                connection.query('SELECT coins FROM users WHERE steamId64=' + userID + '', function (error, results, fields) {
                    var userCoins = results[0]['coins'];
                    var updateCoins = parseInt(userCoins) + parseInt(cost);
                    console.log('Oddałem: ' + cost + ' kontu: ' + userID + '. Ma teraz : ' + updateCoins + '');
                    connection.query('UPDATE users SET coins=' + updateCoins + ' WHERE steamId64=' + userID + '', function (error, results, fields) {

                    });
                });
            });
        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'Accepted') {
        connection.query('UPDATE ofers SET status="Recived by user" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {

        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'InvalidItems') {
        connection.query('UPDATE ofers SET status="Costs refunded." WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
            connection.query('SELECT * FROM ofers WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                var userID = offer.partner.getSteamID64();
                var cost = results[0]['value'];
                connection.query('SELECT coins FROM users WHERE steamId64=' + userID + '', function (error, results, fields) {
                    var userCoins = results[0]['coins'];
                    var updateCoins = parseInt(userCoins) + parseInt(cost);
                    console.log('Oddałem: ' + cost + ' kontu: ' + userID + '. Ma teraz : ' + updateCoins + '');
                    connection.query('UPDATE users SET coins=' + updateCoins + ' WHERE steamId64=' + userID + '', function (error, results, fields) {

                    });
                });
            });
        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'CanceledBySecondFactor') {
        connection.query('UPDATE ofers SET status="Canceled BySecond Factor." WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
            connection.query('SELECT * FROM ofers WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                var userID = offer.partner.getSteamID64();
                var cost = results[0]['value'];
                connection.query('SELECT coins FROM users WHERE steamId64=' + userID + '', function (error, results, fields) {
                    var userCoins = results[0]['coins'];
                    var updateCoins = parseInt(userCoins) + parseInt(cost);
                    console.log('Oddałem: ' + cost + ' kontu: ' + userID + '. Ma teraz : ' + updateCoins + '');
                    connection.query('UPDATE users SET coins=' + updateCoins + ' WHERE steamId64=' + userID + '', function (error, results, fields) {

                    });
                });
            });
        });
    }
    if (offer.state == TradeOfferManager.ETradeOfferState.Accepted) {
        offer.getReceivedItems(function (err, items) {
            var recived = [];
            if (err) {
                console.log("Couldn't get received items: " + err);
            } else {
                var names = items.map(function (item) {
                    return item.name;
                });
                async.each(names, function (value, callback) {
                    console.log(value);
                    callback();
                }, function (err) {

                });
                //itemsToSend.push({
                //    "appid": 730,
                //    "contextid": 2,
                //    "amount": 1,
                //    "assetid": assetID
                //});
                //console.log("Received: " + names.join(', '));
            }
        });
    }
});

io.on('connection', function (socket) {
    var withdraws = socket;
});
setInterval(function () {
    handleQue();
}, 16000);

function handleQue() {
    connection.query('SELECT tradeToken,user_id,assetID,valued FROM withdraw_que LEFT JOIN users ON users.steamId64=withdraw_que.user_id WHERE status="awaiting" GROUP BY user_id LIMIT 1', function (error, results, fields) {
        var users = [];
        async.each(results, function (value, callback) {
            users.push({"userid": value['user_id'], "tradeToken": value['tradeToken'], "valued": value['valued']});
            callback();
        }, function (err) {
            if (err) {
                console.log('En error occured while getting player list');
            } else {
                async.each(users, function (value, callback) {
                    var useride = value['userid'];
                    var tradeToken = value['tradeToken'];
                    var valued = 0;
                    connection.query('SELECT * FROM withdraw_que WHERE user_id=' + useride + ' AND status="awaiting"', function (error, results, fields) {
                        async.each(results, function (value, callback) {
                            valued = parseInt(valued) + parseInt(value['valued']);
                            callback();
                        }, function (err) {
                            connection.query('SELECT coins FROM users WHERE steamId64=' + useride + '', function (error, results, fields) {
                                var userCoins = results['coins'];
                                manager.getEscrowDuration(useride, tradeToken, function (err, him, me) {
                                    if (err) {
                                        connection.query('UPDATE withdraw_que SET status="Error" WHERE user_id=' + useride + '', function (error, results, fields) {
                                            if (!error) {
                                                userCoins = parseInt(userCoins) + parseInt(valued);
                                                connection.query('UPDATE users SET coins=' + userCoins + ' WHERE steamId64=' + useride + '', function (error, results, fields) {
                                                    if(!error) {
                                                        console.log('There is an error occured, offer not sent and coins refunded.');
                                                    }
                                                });
                                            }
                                        });
                                        console.log(err);
                                        return;
                                    }
                                    if (him != 0 && me != 0) {
                                        connection.query('UPDATE withdraw_que SET status="Escrow" WHERE user_id=' + useride + '', function (error, results, fields) {
                                            if (!error) {
                                                userCoins = parseInt(userCoins) + parseInt(valued);
                                                connection.query('UPDATE users SET coins=' + userCoins + ' WHERE steamId64=' + useride + '', function (error, results, fields) {
                                                    if(!error) {
                                                        console.log('There is an hold duration, offer not sent and coins refunded.');
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        var itemsToSend = [];
                                        connection.query('SELECT * FROM withdraw_que WHERE status="awaiting" AND user_id=' + useride + '', function (error, results, fields) {
                                            async.each(results, function (value, callback) {
                                                var assetID = value['assetID'];
                                                itemsToSend.push({
                                                    "appid": 730,
                                                    "contextid": 2,
                                                    "amount": 1,
                                                    "assetid": assetID
                                                });
                                                callback();
                                            }, function (err) {
                                                console.log(itemsToSend);
                                                var Exchange = manager.createOffer(useride);
                                                var Message = "www.csgourban.com - Withdraw offer for "+parseInt(valued)+" DIAMONDS";
                                                if (typeof itemsToSend != 'undefined') {
                                                    Exchange.addMyItems(itemsToSend);
                                                }
                                                Exchange.send(Message, tradeToken, function (err, status) {
                                                    community.checkConfirmations();
                                                    if (status == "pending") {
                                                        var offerID = Exchange.id;
                                                        connection.query('UPDATE withdraw_que SET status="sent" WHERE user_id=' + useride + '', function (error, results, fields) {
                                                        });
                                                        connection.query('INSERT INTO ofers SET userID=' + useride + ',value=' + valued + ',tradeOfferID=' + offerID + ',status="Pending"', function (error, results, fields) {
                                                        });
                                                    }
                                                    if (err) {
                                                        console.log('Error sending offer', err);
                                                        return;
                                                    }
                                                });
                                            });
                                        });
                                    }
                                });
                            })
                        });
                    });
                }, function (err) {
                    if (err) {
                        console.log('En error occured while getting items to send');
                    }
                });
            }
        });
    });
}

ChildBot.prototype._onFriendMsg = function (steamID, message, type) { //overwrite default event handlers
    if (admins.indexOf(steamID) !== -1) {
        if (message == '/token') {
            var _2facode = SteamTotp.generateAuthCode(Bot.sharedSecret);
            Bot.steamFriends.sendMessage(steamID, 'Proszę : ' + _2facode + '');
        } else if (message == '/send') {
            Bot.offers.loadMyInventory({
                appId: 730,
                contextId: 2
            }, function (err, items) {

                console.log(err);
                if (err) {
                    console.log('Problem');
                    steam.webLogOn(function (newCookie) {
                        offers.setup({
                            sessionID: globalSessionID,
                            webCookie: newCookie
                        }, function (err) {
                            if (err) {
                            }
                        });
                    });
                    return;
                }
                var item = [], num = 0;
                var itemssToSend = [], numero = 0;

                for (var i = 0; i < items.length; i++) {
                    if (items[i].tradable) {
                        item[num] = {
                            appid: 730,
                            contextid: 2,
                            amount: items[i].amount,
                            assetid: items[i].id
                        }
                        num++;
                    }
                }
                if (num > 0) {
                    Bot.offers.makeOffer({
                        partnerSteamId: steamID,
                        itemsFromMe: item,
                        itemsFromThem: [],
                        message: ''
                    }, function (err, response) {
                        if (err) {
                            throw err;
                        }
                        console.log('Wysłałem');
                    });
                }
            });
        }
    } else {
        if (type === Steam.EChatEntryType.ChatMsg) {
            //Bot.steamFriends.sendMessage(steamID, 'CSBOX.pl'); //use your custom options
            //this.logger.info(steamID + ' sent: ' + message);
        }
        else {
            //console.log(type);
        }
    }

}

ChildBot.prototype._onFriend = function (steamID, relationship) {
    if (relationship === 2) {
        if (admins.indexOf(steamID) !== -1) {
            Bot.steamFriends.addFriend(steamID);
        }
        else {
            Bot.logger.warn('Someone who isn\'t an admin tried to add me, denying...');
            Bot.steamFriends.removeFriend(steamID);
        }
    }
}


////////////// END OF BOT

function reWebLogOn(callback) {
    Bot.steamUser.on('webSessionID', function (sessionID, newCookie) {
        console.log('x');
        console.log('webLogOn: ' + JSON.stringify({
                sessionID: sessionID,
                cookie: newCookie
            }));

        getSteamAPIKey({
            sessionID: sessionID,
            webCookie: newCookie
        }, function (err, apiKey) {
            if (err) {
                console.log('Ошибка в событии logOnResponse ERROR ' + err);
            }
            else {
                console.log('getSteamAPIKey: ' + apiKey);

                Bot.offers.setup({
                    sessionID: sessionID,
                    webCookie: newCookie, //APIKey:    config.apiKey
                    APIKey: apiKey
                });

                if (typeof callback == "function") {
                    callback();
                }
            }
        });
    });
}
manager.on('newOffer', function (offer) {
    console.log("New offer #" + offer.id + " from " + offer.partner.getSteamID64());
    if (offer.partner.getSteamID64() == "76561198023345468") {
        offer.accept(function (err) {
        });
        return;
    } else {
        offer.decline();
        return;
    }
});
function bang(message) {
    console.log(message);
}

