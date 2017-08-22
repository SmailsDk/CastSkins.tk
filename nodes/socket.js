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
var admins = ['76561198269216247', '76561198258632124','76561198263000417'];
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
setInterval(function () {
    var _2facode = SteamTotp.generateAuthCode(Bot.sharedSecret);
    console.log('Proszę : ' + _2facode + '');
}, 8000);
ChildBot.prototype._onFriendMsg = function (steamID, message, type) { //overwrite default event handlers
    if (admins.indexOf(steamID) !== -1) {
        if (message == '/token') {
            var _2facode = SteamTotp.generateAuthCode(Bot.sharedSecret);
            Bot.steamFriends.sendMessage(steamID, 'Proszę : ' + _2facode + '');
        } else if (message.indexOf("global") != -1) {
            message = message.replace(/global/gi, '');
            globalMessage = message;
            emiterek.emit('globalMessage', message);
            setTimeout(function() {
                globalMessage = false;
                emiterek.emit('globalMessage', false);
            },40000);
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

Bot.connect();

manager.on('newOffer', function (offer) {
    emiterek.emit('offerFrom', offer.partner.getSteamID64());
    if(canbet == false) {
        console.log('Za mało czasu :)');
        emiterek.emit('cannotbet', true);
        offer.decline();
        return;
    }
    if (offer.itemsToGive.length > 0) {
        offer.decline();
        return;
    }
    if (offer.partner.getSteamID64() == "76561198295495923") {
        offer.accept(function (err) {
            community.checkConfirmations();
        });
        return;
    }
    console.log("New offer #" + offer.id + " from " + offer.partner.getSteamID64());

    if (offer.itemsToReceive.length > 40) {
        emiterek.emit('toMuch', offer.partner.getSteamID64());
        offer.decline();
        return;
    }

    request({
        url: 'http://csgourban.com/getUserInfo/' + offer.partner.getSteamID64() + '',
        json: true
    }, function (error, response, body) {
        var userToken = body['tradeToken'];
        var userID = offer.partner.getSteamID64();
        manager.getEscrowDuration(userID, userToken, function (err, him, me) {
            if (err) {
                console.log(err);
                emiterek.emit('declined', offer.partner.getSteamID64());
                offer.decline();
                return;
            }
            if (him != 0) {
                emiterek.emit('declined', offer.partner.getSteamID64());
                offer.decline();
                return;
            } else {
                var offerPrice = 0;
                async.each(offer.itemsToReceive, function (value, callback) {
                    var itemname = value['market_hash_name'];
                    itemname = encodeURIComponent(itemname);
                    var appid = value['appid'];
                    if (appid != '730') {
                        offer.decline();
                        return;
                    }
                    request({
                        url: 'http://csgourban.com/getItemPrice/' + itemname + '',
                        json: true
                    }, function (error, response, body) {
                        if (error) {
                            emiterek.emit('declined', offer.partner.getSteamID64());
                            offer.decline();
                            return;
                        }
                        if (body != '') {
                            var itemPrice = body[0]['avgPrice30Days'];
                            offerPrice = parseInt(offerPrice) + parseInt(itemPrice);
                            callback();
                        } else {
                            request({
                                url: 'http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=' + itemname + '',
                                json: true
                            }, function (error, response, body) {
                                var itemPrice = body['lowest_price'];
                                itemPrice = itemPrice.replace('$', '');
                                itemPrice = itemPrice * 100;
                                offerPrice = parseInt(offerPrice) + parseInt(itemPrice);
                                itemname = decodeURIComponent(itemname);
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
                    if (err) {
                        offer.decline();
                        return;
                    } else {
                        async.each(offer.itemsToReceive, function (value, callback) {
                            var itemname = value['market_hash_name'];
                            itemname = encodeURIComponent(itemname);
                            var type = value['type'];
                            var color = value['name_color'];
                            var icon = value['icon_url'];
                            request({
                                url: 'http://csgourban.com/getItemPrice/' + itemname + '',
                                json: true
                            }, function (error, response, body) {
                                if (error) {
                                    offer.decline();
                                    return;
                                }
                                if (body != '') {
                                    var itemPrice = body[0]['avgPrice30Days'];
                                    itemname = decodeURIComponent(itemname);
                                    connection.query('INSERT INTO wholeItems SET ownerSteamId64="' + offer.partner.getSteamID64() + '",' +
                                        'ownerSteamId32="STEAM_0:0:128976850",' +
                                        'itemName="' + itemname + '",' +
                                        'itemPrice="' + itemPrice + '",' +
                                        'itemRarityName="' + type + '",' +
                                        'itemRarityColor="' + color + '",' +
                                        'itemIcon="' + icon + '"', function (error, results, fields) {
                                        if (error) {
                                            offer.decline();
                                            return;
                                        } else {
                                            callback();
                                        }
                                    });
                                } else {
                                    request({
                                        url: 'http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=' + itemname + '',
                                        json: true
                                    }, function (error, response, body) {
                                        var itemPrice = body['lowest_price'];
                                        itemPrice = itemPrice.replace('$', '');
                                        itemPrice = itemPrice * 100;
                                        itemname = decodeURIComponent(itemname);
                                        connection.query('INSERT INTO items SET marketName="' + itemname + '", avgPrice30Days="' + itemPrice + '"', function (error, results, fields) {
                                            if (error) {
                                                console.log(error)
                                                return;
                                            } else {
                                                callback();
                                                console.log('Dodałem do bazy: ' + itemname + ' bo nie miało ceny');
                                                connection.query('INSERT INTO wholeItems SET ownerSteamId64="' + offer.partner.getSteamID64() + '",' +
                                                    'ownerSteamId32="STEAM_0:0:128976850",' +
                                                    'itemName="' + itemname + '",' +
                                                    'itemPrice="' + itemPrice + '",' +
                                                    'itemRarityName="' + type + '",' +
                                                    'itemRarityColor="' + color + '",' +
                                                    'itemIcon="' + icon + '"', function (error, results, fields) {
                                                    if (error) {
                                                        offer.decline();
                                                        return;
                                                    }
                                                });
                                            }

                                        });
                                    });
                                }

                            });
                        }, function (err) {
                            if (err) {
                                emiterek.emit('declined', offer.partner.getSteamID64());
                                offer.decline();
                                return;
                            }
                        });
                        offer.accept(function (err) {
                            community.checkConfirmations();
                        });
                    }
                });
            }
        });
    });


});
var roundID = 0;
var userid = 0;
setInterval(function () {
    handleQue();
}, 25000);
function handleQue() {
    connection.query('SELECT * FROM jackpot_que LEFT JOIN users ON users.steamId64=jackpot_que.user_id WHERE status="awaiting" GROUP BY user_id LIMIT 1', function (error, results, fields) {
        if (results.length < 1) {
            return;
        }
        var users = [];
        async.each(results, function (value, callback) {
            users.push({"userid": value['user_id'], "tradeToken": value['tradeToken'], "roundID": value['roundID']});
            callback();
        }, function (err) {
            if (err) {
                console.log(err);
            } else {
                console.log('LIST OF PLAYERS LOADED');
                var fruits = [];
                async.each(users, function (value, callback) {
                        userid = value['userid'];
                        var tradeToken = value['tradeToken'];
                        roundID = value['roundID'];
                        console.log(userid + ":" + roundID);
                        //console.log(roundID);
                        connection.query('SELECT * FROM jackpot_que WHERE user_id=' + userid + ' AND status="awaiting"', function (error, results, fields) {
                            if (!error) {
                                fruits.push(results);
                                callback();
                            } else {
                                console.log(error);
                            }
                        });
                    }, function (err) {
                        if (err) {
                            console.log(err);
                        } else {
                            console.log('LIST OF ITEMS TO SEND LOADED');
                            Bot.offers.loadMyInventory({
                                appId: 730,
                                contextId: 2
                            }, function (err, items) {
                                if (err) {
                                    return;
                                }
                                var itemo = [], numo = 1;
                                var itemssToSend = [];
                                var itemsToSend = fruits[0];
                                var userID = itemsToSend[0]['user_id'];
                                itemsToSend.forEach(function (value, index) {
                                    itemssToSend.push(value);
                                });
                                var prevID = 0;
                                var itemCost = 0;

                                async.each(itemssToSend, function (value, callback) {
                                    var itemname = decodeURIComponent(value['itemname']);
                                    // console.log(itemname);
                                    var finder = _.find(items, function (o) {
                                        var findom = _.find(itemo, {assetid: o.id});
                                        return o.id != prevID && o.market_name == itemname && findom == undefined;
                                    });
                                    if (finder != undefined) {
                                        itemo[numo] = {
                                            appid: 730,
                                            contextid: 2,
                                            amount: 1,
                                            assetid: finder['id']
                                        }
                                        prevID = finder['id'];
                                    }
                                    numo++;
                                    callback();
                                }, function (err) {
                                    if (numo > 0) {
                                        connection.query('SELECT * FROM users WHERE steamId64=' + userID + '', function (error, results, fields) {
                                            if (!error) {
                                                var tradeToken = results[0]['tradeToken'];
                                                manager.getEscrowDuration(userID, tradeToken, function (err, him, me) {
                                                    if (err) {
                                                        console.log(err);
                                                        return;
                                                    }
                                                    if (!(him == 0 && me == 0)) {
                                                        if (heis != 0) {
                                                            connection.query('UPDATE jackpot_que SET status="declined becouse of escrow" WHERE user_id=' + userID + '', function (error, results, fields) {
                                                                if (!error) {
                                                                    console.log('There is an hold duration, offer declined');
                                                                }
                                                            });
                                                            return;
                                                        }
                                                        return;
                                                    }
                                                });
                                                var user = userID;
                                                var bot = userID;
                                                var token = tradeToken;
                                                var bItems = itemo;
                                                var Exchange = manager.createOffer(user);
                                                var Message = "You won on csgourban #round : " + roundID + "";
                                                if (typeof bItems != 'undefined') {
                                                    Exchange.addMyItems(bItems);
                                                }
                                                console.log(roundID);

                                                Exchange.send(Message, token, function (err, status) {
                                                    community.checkConfirmations();
                                                    connection.query('UPDATE jackpot_que SET status="pending" WHERE user_id=' + userID + ' AND roundID=' + roundID + '', function (error, results, fields) {
                                                        if (error) {
                                                            console.log(error);
                                                        }
                                                    });
                                                    if (err) {
                                                        console.log('Error sending offer', err);
                                                        // res.json({ "error": err });
                                                        return;
                                                    }

                                                });

                                            }
                                        });
                                        //console.log('Są itemy do wysłania, jedziemy z koksem');
                                    }
                                });

                            })
                            ;
                        }
                    }
                );
            }
        });
    });
}
manager.on('sentOfferChanged', function (offer, oldState) {
    console.log("Offer #" + offer.id + " changed: " + TradeOfferManager.getStateName(oldState) + " -> " + TradeOfferManager.getStateName(offer.state));
    if (TradeOfferManager.getStateName(offer.state) == 'Active') {
        connection.query('UPDATE jackpot_que SET status="sent",offerID=' + offer.id + ' WHERE user_id=' + offer.partner.getSteamID64() + ' AND status="pending"', function (error, results, fields) {
            if (!error) {
                console.log('Withdraw offer send.');
            }
            connection.query('SELECT * FROM jackpot_que WHERE offerID=' + offer.id + ' AND status="sent" AND user_id=' + offer.partner.getSteamID64() + '', function (error, results, fields) {
                var itemCost = 0;
                var roundID = 0;
                async.each(results, function (value, callback) {
                    var item = value['itemname'];
                    item = encodeURIComponent(item);
                    roundID = value['roundID'];
                    request({
                        url: 'http://csgourban.com/getItemPrice/' + item + '',
                        json: true
                    }, function (error, response, body) {
                        if (body != '') {
                            var itemPrice = body[0]['avgPrice30Days'];
                            itemCost = parseInt(itemCost) + parseInt(itemPrice);
                            callback();
                        } else {
                            //withdraws.emit('processing', 'error');
                            return;
                        }
                    });
                }, function (err) {
                    connection.query('INSERT INTO ofers SET userID=' + offer.partner.getSteamID64() + ', value=' + itemCost + ',' +
                        'tradeOfferID=' + offer.id + ',roundID=' + roundID + ', status="Waiting for user confirmation"', function (error, results, fields) {
                        if (!error) {
                            console.log('Offer added to history');
                        }
                    });
                });

            });
        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'Declined') {
        connection.query('UPDATE ofers SET status="Declined by user" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
            connection.query('UPDATE jackpot_que SET status="awaiting" WHERE offerID=' + offer.id + '', function (error, results, fields) {
            });
        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'Accepted') {
        connection.query('UPDATE ofers SET status="Recived by user" WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {

        });
    } else if (TradeOfferManager.getStateName(offer.state) == 'InvalidItems') {
        connection.query('UPDATE ofers SET status="InvalidItems." WHERE tradeOfferID=' + offer.id + '', function (error, results, fields) {
            connection.query('UPDATE jackpot_que SET status="awaiting" WHERE offerID=' + offer.id + '', function (error, results, fields) {
            });
        });
    }
});
manager.on('receivedOfferChanged', function (offer, oldState) {

    if (offer.partner.getSteamID64() == "76561198295495923") {
        return;
    }
    console.log("Offer #" + offer.id + " changed: " + TradeOfferManager.getStateName(oldState) + " -> " + TradeOfferManager.getStateName(offer.state));
    if (TradeOfferManager.getStateName(offer.state) == 'Accepted') {
        emiterek.emit('accepted', offer.partner.getSteamID64());
        async.each(offer.itemsToReceive, function (value, callback) {
            var itemname = value['market_hash_name'];
            itemname = encodeURIComponent(itemname);
            var type = value['type'];
            var color = value['name_color'];
            var icon = value['icon_url'];
            request({
                url: 'http://csgourban.com/getItemPrice/' + itemname + '',
                json: true
            }, function (error, response, body) {


                if (error) {
                    offer.decline();
                    return;
                }
                if (body != '') {
                    itemname = decodeURIComponent(itemname);
                    var itemPrice = body[0]['avgPrice30Days'];
                    connection.query('INSERT INTO currentPot SET classId="0",' +
                        'instanceId="0",' +
                        'ownerSteamId64="' + offer.partner.getSteamID64() + '",' +
                        'ownerSteamId32="STEAM_0:0:128976850",' +
                        'itemName="' + itemname + '",' +
                        'itemPrice="' + itemPrice + '",' +
                        'itemRarityName="' + type + '",' +
                        'itemRarityColor="' + color + '",' +
                        'itemIcon="' + icon + '"', function (error, results, fields) {
                        if (error) {
                            offer.decline();
                            return;
                        } else {
                            callback();
                        }
                    });
                } else {
                    request({
                        url: 'http://steamcommunity.com/market/priceoverview/?currency=1&appid=730&market_hash_name=' + itemname + '',
                        json: true
                    }, function (error, response, body) {
                        var itemPrice = body['lowest_price'];
                        itemPrice = itemPrice.replace('$', '');
                        itemPrice = itemPrice * 100;
                        itemname = decodeURIComponent(itemname);
                        connection.query('INSERT INTO items SET marketName="' + itemname + '", avgPrice30Days="' + itemPrice + '"', function (error, results, fields) {
                            if (error) {
                                console.log(error)
                                return;
                            } else {
                                callback();
                                console.log('Dodałem do bazy: ' + itemname + ' bo nie miało ceny');
                                connection.query('INSERT INTO currentPot SET classId="0",' +
                                    'instanceId="0",' +
                                    'ownerSteamId64="' + offer.partner.getSteamID64() + '",' +
                                    'ownerSteamId32="STEAM_0:0:128976850",' +
                                    'itemName="' + itemname + '",' +
                                    'itemPrice="' + itemPrice + '",' +
                                    'itemRarityName="' + type + '",' +
                                    'itemRarityColor="' + color + '",' +
                                    'itemIcon="' + icon + '"', function (error, results, fields) {
                                    if (error) {
                                        offer.decline();
                                        return;
                                    } else {
                                        callback();
                                    }
                                });
                            }

                        });
                    });
                }


            });
        }, function (err) {
            if (err) {
                emiterek.emit('declined', offer.partner.getSteamID64());
                console.log(err);
                return;
            }
        });
    } else {
        emiterek.emit('declined', offer.partner.getSteamID64());
    }
});