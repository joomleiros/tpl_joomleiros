/**
* @package  EasyBlog
* @copyright Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license  GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

CREATE TABLE IF NOT EXISTS `#__easyblog_post` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `created_by` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NULL default '0000-00-00 00:00:00',
  `title` text NULL,
  `permalink` text NOT NULL,
  `content` longtext NOT NULL,
  `intro` longtext NOT NULL,
  `excerpt` text NULL,
  `category_id` bigint(20) unsigned NOT NULL,  
  `published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `publish_up` datetime NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NULL default '0000-00-00 00:00:00',
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `vote` int(11) unsigned NOT NULL default 0,
  `hits` int(11) unsigned NOT NULL default 0,
  `private` int(11) unsigned NOT NULL default 0,
  `allowcomment` tinyint unsigned NOT NULL default 1,
  `subscription` tinyint unsigned NOT NULL default 1,
  `frontpage` tinyint unsigned NOT NULL default 0,
  `isnew` tinyint unsigned NULL DEFAULT 0 COMMENT 'To indicate whether the post is new created or already been edited',
  `ispending` TINYINT(1) DEFAULT 0 NULL,
  `issitewide` TINYINT(1) DEFAULT 1 NULL,
  `blogpassword` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `easyblog_post_catid` (`category_id`),
  KEY `easyblog_post_published` (`published`),
  KEY `easyblog_post_created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_comment` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `post_id` bigint(20) unsigned NOT NULL,
  `comment` text NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) NULL DEFAULT '',
  `url` varchar(255) NULL DEFAULT '',
  `ip` varchar(255) NULL DEFAULT '',  
  `created_by` bigint(20) unsigned NULL DEFAULT 0,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NULL default '0000-00-00 00:00:00',  
  `published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `publish_up` datetime NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NULL default '0000-00-00 00:00:00',
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `vote` int(11) unsigned NOT NULL default 0,
  `hits` int(11) unsigned NOT NULL default 0,
  `sent` TINYINT(1) DEFAULT 1 NULL,
  `parent_id` int(11) unsigned NULL default 0,
  `lft` int(11) unsigned NOT NULL default 0,
  `rgt` int(11) unsigned NOT NULL default 0,
  PRIMARY KEY  (`id`),
  KEY `easyblog_comment_postid` (`post_id`),
  KEY `easyblog_comment_parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__easyblog_trackback_sent` (
  `id` bigint(20) unsigned NOT NULL auto_increment,  
  `post_id` bigint(20) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,  
  `sent` tinyint(1) DEFAULT '1',
  PRIMARY KEY  (`id`),  
  KEY `easyblog_tb_sent_post_id` (`post_id`),
  KEY `easyblog_tb_sent_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_trackback` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `post_id` bigint(20) unsigned NOT NULL default '0',
  `ip` varchar(25) NOT NULL default '',  
  `title` text NOT NULL,
  `excerpt` text NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `blog_name` text NOT NULL,
  `charset` varchar(45) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) unsigned NOT NULL default '0',
  `option` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `easyblog_tb_post_id` (`post_id`),
  KEY `easyblog_tb_url` (`url`),
  KEY `easyblog_tb_ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_category` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NULL,
  `avatar` varchar(255) NULL,
  `parent_id` int(11) NULL default 0,
  `private` int(11) unsigned NOT NULL default 0,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`),
  KEY `easyblog_cat_published` (`published`),
  KEY `easyblog_cat_parentid` ( `parent_id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `#__easyblog_tag` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NULL,    
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_post_tag` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `tag_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,  
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `easyblog_post_tag_tag_id` (`tag_id`),
  KEY `easyblog_post_tag_post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__easyblog_users` (
  `id` bigint(20) unsigned NOT NULL,
  `nickname` varchar(255) NULL,  
  `avatar` varchar(255) NULL,
  `description` text NULL,
  `url` varchar(255) NULL,
  `params` text NULL,
  `published` TINYINT(1) NOT NULL default 1,
  `title` VARCHAR( 255 ) NOT NULL default '',
  `biography` TEXT NOT NULL default '',
  `permalink` varchar(255) NULL,
  PRIMARY KEY  (`id`),
  KEY `easyblog_users_permalink` (`permalink`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_configs` (
  `name` varchar(255) NOT NULL,
  `params` TEXT NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT = 'Store any configuration in key => params maps';

CREATE TABLE IF NOT EXISTS `#__easyblog_adsense` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `display` varchar(255) NOT NULL DEFAULT 'both',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__easyblog_migrate_content` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `component` varchar(255) NOT NULL DEFAULT 'com_content',
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `post_id` (`post_id`),
  KEY `session_id` (`session_id`),
  KEY `component_content` (`content_id`, `component`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT = 'Store migrated joomla content id and map with eblog post id.';

CREATE TABLE IF NOT EXISTS `#__easyblog_post_subscription` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `post_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NULL DEFAULT '0',
  `fullname` varchar(255) NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `easyblog_post_subscription_post_id` (`post_id`),
  KEY `easyblog_post_subscription_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_blogger_subscription` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `blogger_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NULL DEFAULT '0',
  `fullname` varchar(255) NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `easyblog_blogger_subscription_blogger_id` (`blogger_id`),
  KEY `easyblog_blogger_subscription_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_category_subscription` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `category_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NULL DEFAULT '0',
  `fullname` varchar(255) NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `easyblog_category_subscription_category_id` (`category_id`),
  KEY `easyblog_category_subscription_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_site_subscription` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` bigint(20) unsigned NULL DEFAULT '0',
  `fullname` varchar(255) NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `easyblog_site_subscription_user_id` (`user_id`),
  KEY `easyblog_site_subscription_email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_team_subscription` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT '0',
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `easyblog_team_subscription_team_id` (`team_id`),
  KEY `easyblog_team_subscription_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_acl` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `action` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL default '1',
  `description` text NOT NULL,
  `published` tinyint(1) unsigned NOT NULL default '1',
  `ordering` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `easyblog_post_acl_action` (`action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_acl_group` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `content_id` bigint(20) unsigned NOT NULL,
  `acl_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `easyblog_post_acl_content_type` (`content_id`,`type`),
  KEY `easyblog_post_acl` (`acl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_team` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `alias` varchar(255) NULL,
  `description` TEXT NOT NULL,
  `avatar` varchar(255) NULL,
  `access` tinyint(1) NULL DEFAULT 1,
  `published` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__easyblog_team_users` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `isadmin` tinyint(1) NULL DEFAULT 0,
  KEY `easyblog_team_id` (`team_id`),
  KEY `easyblog_team_userid` (`user_id`),
  KEY `easyblog_team_isadmin` (`team_id`, `user_id`, `isadmin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__easyblog_team_post` (
  `team_id` int(11) NOT NULL,
  `post_id` bigint(11) NOT NULL,
  KEY `easyblog_teampost_tid` (`team_id`),
  KEY `easyblog_teampost_pid` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_team_request` (
  `id` int(11) NOT NULL auto_increment,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ispending` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `easyblog_team_request_teamid` (`team_id`),
  KEY `easyblog_team_request_userid` (`user_id`),
  KEY `easyblog_team_request_pending` (`ispending`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__easyblog_mailq` (
  `id` int(11) NOT NULL auto_increment,
  `mailfrom` varchar(255) NULL,
  `fromname` varchar(255) NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `easyblog_mailq_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__easyblog_featured` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `content_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `easyblog_featured_content_type` (`content_id`,`type`),
  KEY `easyblog_content` (`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_meta` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`type` VARCHAR( 20 ) NOT NULL ,
	`content_id` INT( 11 ) NOT NULL ,
	`keywords` TEXT NULL ,
	`description` TEXT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_likes` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`type` VARCHAR( 20 ) NOT NULL ,
	`content_id` INT( 11 ) NOT NULL ,
  	`created_by` bigint(20) unsigned NULL DEFAULT 0,
  	`created` datetime NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY  (`id`),
	KEY `easyblog_content_type` (`type`, `content_id`),
	KEY `easyblog_contentid` (`content_id`),
	KEY `easyblog_createdby` (`created_by`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_feedburner` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_oauth` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `auto` tinyint(1) NOT NULL,
  `request_token` text NOT NULL,
  `access_token` text NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL,
  `private` tinyint(4) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `easyblog_oauth_user_type` (`user_id`, `type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_oauth_posts` (
  `id` INT( 11 ) NOT NULL auto_increment,
  `oauth_id` INT( 11 ) NOT NULL ,
  `post_id` INT( 11 ) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `sent` DATETIME NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `easyblog_oauth_posts_ids` (`oauth_id`, `post_id`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__easyblog_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `sessionid` varchar(200) NOT NULL,
  `value` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `published` tinyint(3) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`uid`),
  KEY `created_by` (`created_by`),
  KEY `rating` (`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__easyblog_captcha` (
  `id` int(11) NOT NULL auto_increment,
  `response` varchar(5) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `#__easyblog_drafts` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `entry_id` bigint(20) NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default '0000-00-00 00:00:00',
  `title` text,
  `permalink` text NOT NULL,
  `content` longtext NOT NULL,
  `intro` longtext NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `published` tinyint(1) unsigned NOT NULL default '0',
  `publish_up` datetime default '0000-00-00 00:00:00',
  `publish_down` datetime default '0000-00-00 00:00:00',
  `ordering` tinyint(1) unsigned NOT NULL default '0',
  `vote` int(11) unsigned NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0',
  `private` int(11) unsigned NOT NULL default '0',
  `allowcomment` tinyint(3) unsigned NOT NULL default '1',
  `subscription` tinyint(3) unsigned NOT NULL default '1',
  `frontpage` tinyint(3) unsigned NOT NULL default '0',
  `isnew` tinyint(3) unsigned default '0' COMMENT 'To indicate whether the post is new created or already been edited',
  `ispending` tinyint(1) default '0',
  `issitewide` tinyint(1) default '1',
  `blogpassword` varchar(255) NOT NULL,
  `tags` text NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `trackbacks` text NOT NULL,
  `blog_contribute` tinyint(1) NOT NULL,
  `autopost` text NOT NULL,
  `pending_approval` tinyint(3) default '0',
  PRIMARY KEY  (`id`),
  KEY `easyblog_post_catid` (`category_id`),
  KEY `easyblog_post_published` (`published`),
  KEY `easyblog_post_created_by` (`created_by`),
  KEY `easyblog_post_pending_approval` (`pending_approval`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__easyblog_autoarticle_map` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `post_id` bigint(20) unsigned NOT NULL,
  `content_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `autoarticle_map_post_id` (`post_id`),
  KEY `autoarticle_map_content_id` (`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__easyblog_post_rejected` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `draft_id` bigint(20) unsigned NOT NULL,
  `created_by` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `draft_id` (`draft_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__easyblog_hashkeys` (
  `id` bigint(11) NOT NULL auto_increment,
  `uid` bigint(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 1,'view','1','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 2,'view','2','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 3,'view','3','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 4,'view','4','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 5,'view','5','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 6,'view','6','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 7,'view','7','','') ON DUPLICATE KEY UPDATE `type` = 'view';
INSERT INTO `#__easyblog_meta`(`id`,`type`,`content_id`,`keywords`,`description`) VALUES ( 30,'view','30','','') ON DUPLICATE KEY UPDATE `type` = 'view';

