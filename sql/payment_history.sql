CREATE TABLE `payment_history` (
 `id` int(18) NOT NULL AUTO_INCREMENT,
 `google_id` decimal(21,0) NOT NULL,
 `description` varchar(500) NOT NULL,
 `months` int(11) NOT NULL,
 `amount` float NOT NULL,
 `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`id`),
 KEY `google_id` (`google_id`),
 CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`google_id`) REFERENCES `users` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1

ALTER TABLE payment_history
ADD FOREIGN KEY (google_id)
REFERENCES users(google_id)
