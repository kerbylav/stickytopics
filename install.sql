CREATE TABLE IF NOT EXISTS `prefix_stickytopics` (
  `sticky_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` bool DEFAULT TRUE,
  `blog_id` int(11) unsigned,
  `topic_id` int(11) NOT NULL,
  `date_start` datetime,
  `date_finish` datetime,
  `topic_order` int(11),
  PRIMARY KEY (`sticky_id`),
  KEY (`blog_id`),
  KEY `blog_topic` (`blog_id`,`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
