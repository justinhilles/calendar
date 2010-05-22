CREATE TABLE IF NOT EXISTS `wp_calendar` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL,
  `title` varchar(30) NOT NULL,
  `desc` text NOT NULL,
  `recur` char(1) DEFAULT NULL,
  `repeats` int(3) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;