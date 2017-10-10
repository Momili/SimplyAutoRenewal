CREATE TABLE `renewals` (
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
 PRIMARY KEY (`ID`),
 KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=4316 DEFAULT CHARSET=latin1



ALTER TABLE etsy_users
ADD FOREIGN KEY (google_id)
REFERENCES Users(google_id)  

ALTER TABLE renewals ADD Unit VARCHAR(1) NULL;
ALTER TABLE renewals ADD Frequency VARCHAR(3) NULL;
ALTER TABLE renewals ADD RenewType VARCHAR(3) NULL;





