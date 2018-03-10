/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  sa17484
 * Created: Mar 6, 2018
 */

CREATE TABLE `renewals2` (
 `ID` int(18) NOT NULL AUTO_INCREMENT,
 `UserID` decimal(21,0) NOT NULL,
 `ShopID` decimal(21,0) NOT NULL,
 `ItemID` decimal(21,0) NOT NULL,
 `CategoryID` decimal(21,0) DEFAULT NULL,
 `State` varchar(50) DEFAULT NULL,
 `Title` varchar(255) NOT NULL,
 `ImageUrl` varchar(255) NOT NULL,
 `Quantity` mediumint(18) DEFAULT NULL,
 `Views` mediumint(18) DEFAULT NULL,
 `Likes` mediumint(18) DEFAULT NULL,
 `LastUpdatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `ExpiryDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `ScheduledDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `ScheduledDate` date NOT NULL,
 `ScheduledTime` time NOT NULL,
 `RenewalStatus` varchar(1) NOT NULL,
 `UpdatedTimeStamp` timestamp NULL DEFAULT NULL,
 `TargetDateTime` varchar(255) NOT NULL,
 `LocalDateTime` varchar(255) NOT NULL,
 `Unit` varchar(1) DEFAULT NULL,
 `Frequency` varchar(3) DEFAULT NULL,
 `RenewType` varchar(3) DEFAULT NULL,
 `StartDate` date NOT NULL,
 `EndDate` date NOT NULL,
 `StartTime` time NOT NULL,
 `EndTime` time NOT NULL,
 `NumberOfItems` smallint,
 `Sun` varchar(1),
 `Mon` varchar(1)
 `Tue` varchar(1),
 `Wed` varchar(1),
 `Thu` varchar(1),
 `Fri` varchar(1),
 `Sat` varchar(1),
 `TargetTZ` smallint,
 `LocalTZ` smallint,
 PRIMARY KEY (`ID`),
 KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=4316 DEFAULT CHARSET=latin1




