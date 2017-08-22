var antiSpam = require('socket-anti-spam');
var io = require('socket.io').listen(8799);
var mysql = require('mysql');
var _ = require('lodash');
var async = require("async");
var request = require("request");

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
var canbet = true;

var SteamBot = false;
var WebApi = false;
var request = require("request");
//var gameTime = 125;
var timeleft;
var winnerID = '';
var randomstring = require("randomstring");
var gameTime = 126000; //125 sekund + 2 (na polaczenie z klientem mijaja 1-2 sekundy)
var end_at = 0; // dont touch


connection.connect(function (err) {
    if (err) {
        console.error('error connecting: ' + err.stack);
        return;
        WebApi = false;
    }
    WebApi = true;
});
var emiterek = io
    .on('connection', function (socket) {
        socket.on('bet', function (data) {
            canbet = data;
        });
    });
////////////////// csgourban.com Bot v0.0.1
var globalMessage = false;
var admins = ['76561198269216247', '76561198258632124', '76561198263000417'];
var util = require('util');
var ParentBot = require('steam-parentbot'); //change to 'steam-parentbot' if not running from examples directory
var Steam = ParentBot.Steam; //instance of the Steam object
var PriceApiKey = 'fFlJtSeeJb7wzswQG5FYJyeytkVK6dE8';
var my = 0;
var their = 0;
var TradeOfferManager = require('steam-tradeoffer-manager');
var fs = require('fs');
var Steamcommunity = require('steamcommunity');
var community = new Steamcommunity();

var manager = new TradeOfferManager({
    "domain": "http://csgourban.com", // Our domain is example.com
    "language": "en", // We want English item descriptions
    "pollInterval": 1000 // We want to poll every 5 seconds since we don't have Steam notifying us of offers
});
if (fs.existsSync('polldataJackpot.json')) {
    manager.pollData = JSON.parse(fs.readFileSync('polldataJackpot.json'));
    console.log('WTF');
} else {
    console.log("Users file or master ssfn missing. Exiting..");
}
manager.on('pollData', function (pollData) {
    fs.writeFile('polldataJackpot.json', JSON.stringify(pollData));
    // console.log('Pulled Data');
});

var ChildBot = function () {
    ChildBot.super_.apply(this, arguments);
}

util.inherits(ChildBot, ParentBot);

var Bot = new ChildBot('rygielpds', 'C5kgXx99', {
    apikey: 'C146A31E1E6845C75E86728A1A488481', //steam api key, will be registered automatically if one isn't supplied
    sentryfile: 'rygielpds.sentry', //sentry file that stores steamguard info, defaults to username.sentry
    logfile: 'rygielpds.log', //filename to log stuff to, defaults to username.log
    sharedSecret: 'Z5v34dZ9GetrNdwkGgXgiXD6jxo=', //shared secret, needed to automatically generate twoFactorCode
    identitySecret: 'M0ux6n9RJ3NurQgdmcx08wyJGHk=', //identity secret, needed to automatically confirm trade offers, must be used with confirmationInterval
    confirmationInterval: 10000, //how often we should check for new trades to confirm in miliseconds, must be used with identitySecret
    richPresenceID: 730 //game to use rich presence with, don't include for no rich presence
});
var SteamTotp = require('steam-totp');
var util = require('util');
var botMain = function () {
    botMain.super_.apply(this, arguments);
}
util.inherits(botMain, ParentBot);

var TOTP = require('onceler').TOTP;
var totp = new TOTP('D2EUYJ7OKUV6TUKU');


Bot.steamUser.on('webSessionID', function (webSessionID) {
    console.log('Event "webSessionID" sessionID: ' + webSessionID);
    console.log('Event "webSessionID" SteamClient.sessionID: ' + SteamClient.sessionID);

    gSessionID = webSessionID;
    globalSessionID = webSessionID;

});
// setInterval(function () {
//     var _2facode = SteamTotp.generateAuthCode(Bot.sharedSecret);
//     console.log('Proszę : ' + _2facode + '');
// }, 8000);
ChildBot.prototype._onFriendMsg = function (steamID, message, type) { //overwrite default event handlers
    if (admins.indexOf(steamID) !== -1) {
        if (message == '/token') {
            var _2facode = SteamTotp.generateAuthCode(Bot.sharedSecret);
            Bot.steamFriends.sendMessage(steamID, 'Proszę : ' + _2facode + '');
        } else if (message.indexOf("global") != -1) {
            message = message.replace(/global/gi, '');
            globalMessage = message;
            emiterek.emit('globalMessage', message);
            setTimeout(function () {
                globalMessage = false;
                emiterek.emit('globalMessage', false);
            }, 40000);
        } else if (message == '/send') {
            Bot.offers.loadMyInventory({
                appId: 730,
                contextId: 2
            }, function (err, items) {

                //console.log(items);
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
            //Bot.steamFriends.sendMessage(steamID, 'csgourban.com'); //use your custom options
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


function sendMessage(steamID, message) {
    Bot.steamFriends.sendMessage(steamID, message);
}

function bang(message) {
    console.log(message);
}
var SteamWebLogOn = require('steam-weblogon');
var steamWebLogOn = new SteamWebLogOn(Bot.steamClient, Bot.steamUser);
Bot.steamClient.on('logOnResponse', function (logonResp) {
    //console.log('Event "logOnResponse": ' + JSON.stringify(logonResp));
    if (logonResp.eresult === Steam.EResult.OK) {
        console.log('Logged in!');
        //console.log(logonResp);
        SteamBot = true;
    }
    steamWebLogOn.webLogOn(function (webSessionID, cookies) {
        manager.setCookies(cookies, function (err) {
            if (err) {
                //console.log(err);
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
Bot.steamClient.on('error', function (e) {
    console.log('Wystąpił błąd : ' + e + '');
});
manager.on('pollData', function (pollData) {
    fs.writeFile('polldata.json', JSON.stringify(pollData));
    console.log('Pulled Data');
});
Bot.connect();
setInterval(function () {
    handleDepo();
}, 12000);

manager.on('sentOfferChanged', function (offer, oldState) {
    connection.query('SELECT * FROM deposit_ofers WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
        if (results.length > 0) {
            console.log('Is deposit');
            console.log("Offer #" + offer.id + " changed: " + TradeOfferManager.getStateName(oldState) + " -> " + TradeOfferManager.getStateName(offer.state));
            if (offer.state == 4) {
                offer.decline();
                connection.query('UPDATE deposit_ofers SET status="Stop doing this!" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                    if (error) {
                        console.log(error);
                        return;
                    }
                })
                return;
            }
            if (TradeOfferManager.getStateName(offer.state) == 'Declined') {
                connection.query('UPDATE deposit_ofers SET status="Declined by user" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                    if (error) {
                        console.log(error);
                        return;
                    }
                })
            } else if (TradeOfferManager.getStateName(offer.state) == 'Accepted') {
                connection.query('UPDATE deposit_ofers SET status="Accepted" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                    if (error) {
                        console.log(error);
                        return;
                    }

                });
            } else if (TradeOfferManager.getStateName(offer.state) == 'InvalidItems') {
                connection.query('UPDATE deposit_ofers SET status="InvalidItems" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
                    if (error) {
                        console.log(error);
                        return;
                    }
                });
            }
            if (offer.state == TradeOfferManager.ETradeOfferState.Accepted) {
                offer.getReceivedItems(function (err, items) {
                    if (err) {
                        console.log("Couldn't get received items: " + err);
                    } else {
                        var offerPrice = 0;
                        async.each(items, function (value, callback) {

                            var itemname = value['market_hash_name'];

                            itemname = encodeURIComponent(itemname);
                            request({
                                url: "http://csgourban.com/getItemPrice/" + itemname + "",
                                json: true
                            }, function (error, response, body) {
                                if (error) {
                                    console.log('Error przy pobieraniu ceny.');
                                    offer.decline();
                                    return;
                                }
                                if (body != '') {
                                    var itemPrice = body[0]['avgPrice30Days'];
                                    offerPrice = parseInt(offerPrice) + parseInt(itemPrice);
                                    itemname = decodeURIComponent(itemname);
                                    connection.query('INSERT INTO wholeItems SET ownerSteamId64="' + offer.partner.getSteamID64() + '",' +
                                        'ownerSteamId32="STEAM_0:0:128976850",' +
                                        'itemName="' + itemname + '",' +
                                        'itemPrice="' + itemPrice + '",' +
                                        'itemRarityName="x",' +
                                        'itemRarityColor="x",' +
                                        'itemIcon="x",' +
                                        'roulette="1"', function (error, results, fields) {
                                        if (error) {
                                            return;
                                        }
                                    });
                                    callback();
                                } else {
                                    request({
                                        url: 'http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=' + itemname + '',
                                        json: true
                                    }, function (error, response, body) {
                                        var itemPrice = body['lowest_price'];
                                        itemPrice = itemPrice.replace('$', '');
                                        itemPrice = itemPrice * 100;
                                        itemname = decodeURIComponent(itemname);
                                        offerPrice = parseInt(offerPrice) + parseInt(itemPrice);
                                        connection.query('INSERT INTO items SET marketName="' + itemname + '", avgPrice30Days="' + itemPrice + '"', function (error, results, fields) {
                                            if (error) {
                                                console.log(error)
                                                return;
                                            } else {
                                                callback();
                                                console.log('Dodałem do bazy: ' + itemname + ' bo nie miało ceny');
                                            }
                                        });
                                    });
                                }
                            });
                        }, function (err) {
                            offerPrice = offerPrice * 10;
                            var userID = offer.partner.getSteamID64();
                            var cost = parseInt(offerPrice);
                            var updateCoins = 0;
                            connection.query('SELECT coins FROM users WHERE steamId64=' + userID + '', function (error, results, fields) {
                                var userCoins = results[0]['coins'];
                                updateCoins = parseInt(userCoins) + parseInt(cost);
                                connection.query('UPDATE users SET coins=' + updateCoins + ' WHERE steamId64=' + userID + '', function (error, results, fields) {
                                    console.log('Dodałem: ' + cost + ' kontu: ' + userID + '. Ma teraz : ' + updateCoins + '');
                                });
                            });

                        });

                    }
                });
            }

            return;
        }
    });
});
function handleDepo() {
    var useride = 0;
    var tradeToken = 0;
    var valued = 0;
    connection.query('SELECT tradeToken,user_id,assetID,valued FROM deposit_que LEFT JOIN users ON users.steamId64=deposit_que.user_id WHERE status="0" GROUP BY user_id LIMIT 1', function (error, results, fields) {
        var users = [];
        async.each(results, function (value, callback) {
            users.push({"userid": value['user_id'], "tradeToken": value['tradeToken']});
            callback();
        }, function (err) {
            if (err) {
                console.log('En error occured while getting player list');
            } else {
                async.each(users, function (value, callback) {
                    useride = value['userid'];
                    tradeToken = value['tradeToken'];
                    manager.getEscrowDuration(useride, tradeToken, function (err, him, me) {
                        if (err) {
                            connection.query('DELETE FROM deposit_que WHERE user_id=' + useride + '', function (error, results, fields) {
                            });
                            return;
                        }
                        if (him != 0) {
                            connection.query('DELETE FROM deposit_que WHERE user_id=' + useride + '', function (error, results, fields) {
                            });
                            return;
                        } else {
                            connection.query('SELECT * FROM deposit_que WHERE status="0" AND user_id=' + useride + '', function (error, results, fields) {
                                var itemsToSend = [];
                                async.each(results, function (value, callback) {
                                    var assetID = value['assetID'];
                                    var itemname = value['itemname'];

                                    itemname = encodeURIComponent(itemname);
                                    request({
                                        url: "http://csgourban.com/getItemPrice/" + itemname + "",
                                        json: true
                                    }, function (error, response, body) {
                                        if (error) {
                                            console.log('Error przy pobieraniu ceny przy wysyłaniu depo.');
                                            connection.query('DELETE FROM deposit_que WHERE user_id=' + useride + '', function (error, results, fields) {
                                            });
                                            return;
                                        }
                                        if (body != '') {
                                            if (body[0]['avgPrice30Days'] > 39) {
                                                var itemPrice = body[0]['avgPrice30Days'];
                                                valued = parseInt(valued) + parseInt(itemPrice * 10);
                                                itemsToSend.push({
                                                    "appid": 730,
                                                    "contextid": 2,
                                                    "amount": 1,
                                                    "assetid": assetID
                                                });
                                            }
                                            callback();
                                        } else {
                                            request({
                                                url: 'http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=' + itemname + '',
                                                json: true
                                            }, function (error, response, body) {
                                                var itemPrice = body['lowest_price'];
                                                itemPrice = itemPrice.replace('$', '');
                                                itemPrice = itemPrice * 100;
                                                itemname = decodeURIComponent(itemname);
                                                valued = parseInt(valued) + parseInt(itemPrice * 10);
                                                connection.query('INSERT INTO items SET marketName="' + itemname + '", avgPrice30Days="' + itemPrice + '"', function (error, results, fields) {
                                                    if (error) {
                                                        console.log(error)
                                                        return;
                                                    } else {

                                                        console.log('Dodałem do bazy: ' + itemname + ' bo nie miało ceny');
                                                    }
                                                });
                                                callback();
                                            });
                                        }

                                    });

                                }, function (err) {
                                    console.log(itemsToSend);
                                    var Exchange = manager.createOffer(useride);
                                    var Message = "CSGOURBAN COM - Deposit for " + valued + " coins.";
                                    if (typeof itemsToSend != 'undefined') {
                                        Exchange.addTheirItems(itemsToSend);
                                    }
                                    Exchange.send(Message, tradeToken, function (err, status) {
                                        console.log('Status: ' + status + '. Deposit for ' + valued + '');
                                        var offerID = Exchange.id;
                                        if (err) {
                                            console.log('Error sending offer', err);
                                            connection.query('DELETE FROM deposit_que WHERE user_id=' + useride + '', function (error, results, fields) {
                                            });
                                            return;
                                        }
                                        community.checkConfirmations();

                                        if (status == "sent") {
                                            connection.query('UPDATE deposit_que SET status="2" WHERE user_id=' + useride + '', function (error, results, fields) {
                                            });
                                            connection.query('INSERT INTO deposit_ofers SET userID=' + useride + ',value=' + valued + ',tradeOfferID=' + offerID + ',status="Pending",isDeposit="1"', function (error, results, fields) {
                                            });
                                        }

                                    });
                                });
                            });
                        }
                    });

                    callback();
                }, function (err) {
                    return;
                });
            }
        });
    });
}
manager.on('newOffer', function (offer) {
    console.log('New offer');
    if (offer.partner.getSteamID64() == "76561198295495923") {
        offer.accept(function (err) {
            community.checkConfirmations();
        });
        return;
    } else {
        offer.decline();
        return;
    }


});