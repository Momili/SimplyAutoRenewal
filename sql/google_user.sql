CREATE TABLE `users` (
 `google_id` decimal(21,0) NOT NULL,
 `google_name` varchar(255) NOT NULL,
 `google_email` varchar(255) NOT NULL,
 `google_link` varchar(255) NOT NULL,
 `google_picture_link` varchar(500) NOT NULL,
 `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

