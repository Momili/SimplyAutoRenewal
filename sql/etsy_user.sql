CREATE TABLE `etsy_users` (
 `id` int(18) NOT NULL AUTO_INCREMENT,
 `google_id` decimal(21,0) NOT NULL,
 `user_id` decimal(21,0) NOT NULL,
 `login_name` varchar(100) NOT NULL,
 `shop_id` int(11) NOT NULL,
 `shop_name` varchar(500) NOT NULL,
 `shop_url` varchar(500) NOT NULL,
 `title` varchar(500) NOT NULL,
 `access_token` varchar(500) NOT NULL,
 `access_token_secret` varchar(500) NOT NULL,
 `banner_url` varchar(500) DEFAULT NULL,
 `icon_url` varchar(500) DEFAULT NULL,
 `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `is_default` tinyint(4) DEFAULT NULL,
 `status` varchar(1) NOT NULL,
 `monthly_limit` float DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `google_id` (`google_id`),
 CONSTRAINT `etsy_users_ibfk_1` FOREIGN KEY (`google_id`) REFERENCES `users` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1

ALTER TABLE etsy_users
ADD FOREIGN KEY (google_id)
REFERENCES users(google_id)  