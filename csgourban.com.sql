/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


CREATE TABLE IF NOT EXISTS `acces_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `archivements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arch_name` text COLLATE utf8_unicode_ci,
  `arch_desc` text COLLATE utf8_unicode_ci,
  `arch_points` int(10) DEFAULT '0',
  `arch_icon` text CHARACTER SET latin1,
  `arch_color_class` text CHARACTER SET latin1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `bad_links` (
  `link` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `bust_bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashed_out` varchar(50) NOT NULL,
  `profit` varchar(50) NOT NULL DEFAULT '0',
  `userID64` bigint(20) NOT NULL,
  `amount` int(50) NOT NULL,
  `gameID` int(11) NOT NULL,
  `avatar` longtext,
  `url` longtext,
  `nick` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ammount` (`amount`),
  KEY `userID64` (`userID64`),
  KEY `gameID` (`gameID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `bust_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bust_number` varchar(50) DEFAULT '0',
  `started` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `secret` text,
  `hash` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamUserID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `room` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `steamUserID` (`steamUserID`(250)),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `coinflip_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(50) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '0',
  `left_side` varchar(50) NOT NULL DEFAULT '0',
  `left` text NOT NULL,
  `right` text NOT NULL,
  `right_side` varchar(50) NOT NULL DEFAULT '0',
  `ammount` varchar(50) NOT NULL DEFAULT '0',
  `won` varchar(50) NOT NULL DEFAULT '0',
  `ended` timestamp NULL DEFAULT NULL,
  `left_active` int(11) NOT NULL DEFAULT '0',
  `right_active` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `currentPot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classId` bigint(20) NOT NULL,
  `instanceId` bigint(20) NOT NULL,
  `ownerSteamId64` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ownerSteamId32` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `itemName` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `itemPrice` int(11) NOT NULL,
  `itemRarityName` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `itemRarityColor` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `itemIcon` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `deposit_ofers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` varchar(50) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL DEFAULT '0',
  `tradeOfferID` varchar(50) NOT NULL DEFAULT '0',
  `roundID` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `isDeposit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `value` (`value`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `deposit_que` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `assetID` text NOT NULL,
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `itemname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `offerID` varchar(50) NOT NULL,
  `valued` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `donations` (
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `g2a` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` varchar(50) DEFAULT NULL,
  `buyer` varchar(50) NOT NULL DEFAULT '0',
  `coins` varchar(50) NOT NULL DEFAULT '0',
  `cost` varchar(50) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `giveaway_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(50) DEFAULT NULL,
  `reward` int(11) DEFAULT NULL,
  `steamId64` bigint(20) DEFAULT NULL,
  `redeem_date` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roundHash` longtext COLLATE utf8mb4_unicode_ci,
  `ticketwon` longtext COLLATE utf8mb4_unicode_ci,
  `ticketwinner` text COLLATE utf8mb4_unicode_ci,
  `endTime` bigint(20) NOT NULL,
  `winnerSteamId32` text COLLATE utf8mb4_unicode_ci,
  `winnerSteamId64` bigint(20) DEFAULT NULL,
  `userPutInPrice` int(11) NOT NULL DEFAULT '0',
  `potPrice` int(11) NOT NULL DEFAULT '0',
  `allItemsJson` longtext COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL DEFAULT '1999-01-01',
  `time` timestamp NULL default '0000-00-00 00:00:00',
  `usersInPot` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `endTime` (`endTime`),
  FULLTEXT KEY `roundHash` (`roundHash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `homepay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL DEFAULT '0',
  `prsid` varchar(50) NOT NULL DEFAULT '0',
  `text` varchar(50) NOT NULL DEFAULT '0',
  `num` varchar(50) NOT NULL DEFAULT '0',
  `coins` bigint(20) NOT NULL DEFAULT '0',
  `userid` varchar(50) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketName` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avgPrice30Days` int(11) NOT NULL,
  `buyOrders` int(11) DEFAULT NULL,
  `sellOrders` int(11) NOT NULL,
  `highestBuyOrder` int(11) DEFAULT NULL,
  `lowestSellOrder` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `marketName` (`marketName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `jackpot_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) DEFAULT '0',
  `value` varchar(50) DEFAULT '0',
  `status` varchar(50) DEFAULT 'Waiting to accept',
  `date` timestamp NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `jackpot_que` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `assetID` text NOT NULL,
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `itemname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'awaiting',
  `roundID` int(11) DEFAULT NULL,
  `offerID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `matches` (
  `id` int(11) DEFAULT NULL,
  `matchname` longtext,
  `teamLogo0` longtext,
  `teamLogo1` longtext,
  `team0name` text,
  `team1name` text,
  `team0percentage` int(11) DEFAULT NULL,
  `team1percentage` int(11) DEFAULT NULL,
  `team0odds` double DEFAULT NULL,
  `team1odds` double DEFAULT NULL,
  `time` text,
  `timestamp` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `winner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `match_bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamId64` text,
  `name` text,
  `matchid` int(11) DEFAULT NULL,
  `team` int(11) DEFAULT NULL,
  `ammount` int(11) DEFAULT NULL,
  `won` int(11) DEFAULT NULL,
  `ended` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `ofers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` varchar(50) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL DEFAULT '0',
  `tradeOfferID` varchar(50) NOT NULL DEFAULT '0',
  `roundID` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `isDeposit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `value` (`value`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `placed_bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` mediumtext NOT NULL,
  `userID64` bigint(20) NOT NULL,
  `ammount` int(50) NOT NULL,
  `gameID` int(11) NOT NULL,
  `avatar` longtext,
  `url` longtext,
  `nick` varchar(50) DEFAULT NULL,
  `isStreamer` int(11) DEFAULT '0',
  `streamLink` varchar(50) DEFAULT '0',
  `created_at` timestamp NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ammount` (`ammount`),
  KEY `userID64` (`userID64`),
  KEY `gameID` (`gameID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `possible_withdraws` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `reflinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `reflink_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` text NOT NULL,
  `tradeURL` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `roulette_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `endTime` bigint(20) NOT NULL DEFAULT '0',
  `colorWon` text,
  `date` timestamp NULL default '0000-00-00 00:00:00',
  `numberWon` int(11) DEFAULT '99',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `numberWon` (`numberWon`),
  KEY `endTime` (`endTime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `SETTHIS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `SIMPAY` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer` varchar(50) NOT NULL DEFAULT '0',
  `coins` varchar(50) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `siteProfit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteProfit` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `slots_winnings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamId64` varchar(50) DEFAULT '0',
  `placed` varchar(50) DEFAULT NULL,
  `xwhat` varchar(50) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.



CREATE TABLE IF NOT EXISTS `SMSCODES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numbo` int(11) NOT NULL DEFAULT '0',
  `cost` varchar(50) NOT NULL DEFAULT '0',
  `coins` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamid` bigint(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `ticket_subject` varchar(50) DEFAULT NULL,
  `ticket_category` varchar(50) DEFAULT NULL,
  `ticket_mess` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

CREATE TABLE IF NOT EXISTS `trade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamId64` varchar(50) NOT NULL,
  `offerID` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `trade_que` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamId64` varchar(50) NOT NULL,
  `assetID` text NOT NULL,
  `date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `itemname` text NOT NULL,
  `offerID` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `transferHistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(50) DEFAULT '0',
  `to` varchar(50) DEFAULT '0',
  `from_id` varchar(50) DEFAULT NULL,
  `to_id` varchar(50) DEFAULT NULL,
  `coins` varchar(50) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamId32` bigint(20) DEFAULT NULL,
  `steamId64` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tradeToken` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tradeURL` longtext COLLATE utf8mb4_unicode_ci,
  `chat_blocked` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `chat_timeout` timestamp NULL DEFAULT NULL,
  `chat_blocked_reason` text COLLATE utf8mb4_unicode_ci,
  `reflink_points` int(11) NOT NULL DEFAULT '0',
  `reflink` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `nick` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `username` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isAdmin` int(1) DEFAULT '0',
  `isMod` int(1) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `remember_token` text COLLATE utf8mb4_unicode_ci,
  `coins` bigint(20) DEFAULT '0',
  `havecsgo` int(11) DEFAULT '0',
  `reedem_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `refcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'steam',
  `isStreamer` int(11) DEFAULT '0',
  `steamLink` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `email_confirm` int(11) DEFAULT '0',
  `global_banned` int(11) DEFAULT '0',
  `global_banned_reason` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isPartner` int(11) DEFAULT '0',
  `lastdailygift` datetime DEFAULT NULL,
  `dailygiftinrow` int(11) DEFAULT '0',
  `agreeterms` int(11) DEFAULT '0',
  `fb_group_joined` int(11) DEFAULT '0',
  `steam_group_joined` int(11) DEFAULT '0',
  `namerewardcollected` datetime DEFAULT NULL,
  `steamgrouprewardcollected` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `nick` (`nick`),
  FULLTEXT KEY `avatar` (`avatar`),
  FULLTEXT KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `wholeItems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerSteamId64` text NOT NULL,
  `ownerSteamId32` text NOT NULL,
  `itemName` text NOT NULL,
  `itemPrice` int(11) NOT NULL,
  `itemRarityName` text NOT NULL,
  `itemRarityColor` varchar(6) NOT NULL,
  `itemIcon` text NOT NULL,
  `roulette` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `withdraws_hist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `assetID` text,
  `date` timestamp NULL default '0000-00-00 00:00:00',
  `itemname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `withdraw_fitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `price` bigint(20) DEFAULT NULL,
  `img` text NOT NULL,
  `assetid` int(11) DEFAULT NULL,
  `classid` int(11) DEFAULT NULL,
  `instanceid` int(11) DEFAULT NULL,
  `color` text,
  `inspect` text,
  UNIQUE KEY `assetid` (`assetid`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


CREATE TABLE IF NOT EXISTS `withdraw_que` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `assetID` bigint(20) DEFAULT NULL,
  `itemname` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `valued` bigint(20) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'awaiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;