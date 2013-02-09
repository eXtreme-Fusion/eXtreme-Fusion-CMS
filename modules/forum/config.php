<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
$mod_info = array(
	'title' => __('Forum'),
	'description' => __('Standardowa wersja forum dla systemu eXtreme-Fusion 5'),
	'developer' => 'eXtreme Crew',
	'support' => 'http://extreme-fusion.org',
	'version' => '1.0',
	'dir' => 'forum',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$admin_page[1] = array(
	'title' => __('Forum'),
	'image' => 'templates/images/forum.png',
	'page' => 'admin/forum.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'sett',
	'desc' => __('Uprawnienie umożliwiające zarządzanie ustawieniami forum.')
);

$perm[2] = array(
	'name' => 'admin',
	'desc' => __('Uprawnienie umożliwiające zarządanie kategoriami forum.')
);

$menu_link[1] = array(
	'title' => __('Forum'),
	'url' => 'forum',
	'visibility' => '3'
);

$new_table[1] = array(
	"forum_sett",
	"(
		`key` VARCHAR(100) NOT NULL DEFAULT '',
		`value` text NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"forum_forums",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`cat` MEDIUMINT UNSIGNED NOT NULL,
		`description` varchar(255) DEFAULT NULL,
		`moderators` mediumtext  DEFAULT NULL,
		`datestamp` INT UNSIGNED DEFAULT '0' NOT NULL,
		`order` SMALLINT UNSIGNED NOT NULL,
		`access` VARCHAR(255) NOT NULL DEFAULT '',
		`posting` tinyint(3) unsigned NOT NULL DEFAULT '250',
		`last_post` int(10) unsigned NOT NULL DEFAULT '0',
		`last_user` int(8) unsigned NOT NULL DEFAULT '0',
		`count_threads` int(10) NOT NULL DEFAULT '0',
		`count_posts` int(10) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[3] = array(
	"forum_cats",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description` varchar(255) DEFAULT NULL,
		`datestamp` INT UNSIGNED DEFAULT '0' NOT NULL,
		`order` SMALLINT UNSIGNED NOT NULL,
		`access` VARCHAR(255) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[4] = array(
	"forum_threads",
	"(
		`id` int(8) unsigned NOT NULL AUTO_INCREMENT,
		`forum_id` int(8) unsigned NOT NULL DEFAULT '0',
		`subject` varchar(100) NOT NULL DEFAULT '',
		`author` int(8) unsigned NOT NULL DEFAULT '0',
		`views` int(8) unsigned NOT NULL DEFAULT '0',
		`last_post` int(10) unsigned NOT NULL DEFAULT '0',
		`last_user` int(8) unsigned NOT NULL DEFAULT '0',
		`sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
		`locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
		`replies` int(10) NOT NULL DEFAULT '0',
		`last_post_id` int(10) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`),
		KEY `forum_id` (`forum_id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[5] = array(
	"forum_posts",
	"(
		`id` int(8) unsigned NOT NULL AUTO_INCREMENT,
		`forum_id` int(8) unsigned NOT NULL DEFAULT '0',
		`thread_id` int(8) unsigned NOT NULL DEFAULT '0',
		`subject` varchar(100) NOT NULL DEFAULT '',
		`message` text NOT NULL,
		`smileys` tinyint(1) unsigned NOT NULL DEFAULT '1',
		`author` int(8) unsigned NOT NULL DEFAULT '0',
		`datestamp` int(10) unsigned NOT NULL DEFAULT '0',
		`ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
		`edit_user` int(8) unsigned NOT NULL DEFAULT '0',
		`edit_time` int(10) unsigned NOT NULL DEFAULT '0',
		`user_agent` varchar(153) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`),
		KEY `forum_id` (`forum_id`),
		KEY `datestamp` (`datestamp`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[6] = array(
	"forum_drzewko",
	"(
		`id` int(8) NOT NULL AUTO_INCREMENT,
		`name` varchar(32) NOT NULL DEFAULT '',
		`left` int(8) NOT NULL DEFAULT '0',
		`right` int(8) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`),
		KEY `parent` (`left`),
		KEY `right` (`right`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_row[1] = array(
	"forum_sett", 
	"(`key`, `value`) VALUES ('forum_title', '')"
);

$new_row[2] = array(
	"forum_sett", 
	"(`key`, `value`) VALUES ('forum_description', '')"
);

$new_row[3] = array(
	"forum_sett", 
	"(`key`, `value`) VALUES ('forum_keywords', '')"
);

$new_row[4] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (1, 'Budynki', 1, 22)"
);

$new_row[5] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (2, 'Przemyslowe', 2, 5)"
);

$new_row[6] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (3, 'Publiczne', 6, 11)"
);

$new_row[7] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (4, 'Mieszkalne', 12, 21)"
);

$new_row[8] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (5, 'Fabryka', 3, 4)"
);

$new_row[9] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (6, 'Biblioteka', 7, 8)"
);

$new_row[10] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (7, 'Kosciol', 9, 10)"
);

$new_row[11] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (8, 'Domy', 13, 18)"
);

$new_row[12] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (9, 'Blok mieszkalny', 19, 20)"
);

$new_row[13] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (10, 'Dom jednorodzinny', 14, 15)"
);

$new_row[14] = array(
	"forum_drzewko", 
	"(`id`, `name`, `left`, `right`) VALUES (11, 'Dom wielorodzinny', 16, 17)"
);

$drop_table[1] = "forum_sett";
$drop_table[2] = "forum_forums";
$drop_table[3] = "forum_cats";
$drop_table[4] = "forum_threads";
$drop_table[5] = "forum_posts";
$drop_table[6] = "forum_drzewko";

$tag_supplement[1] = "FORUM_FORUMS";
$tag_supplement[2] = "FORUM_CATS";
$tag_supplement[3] = "FORUM_THREADS";
$tag_supplement[4] = "FORUM_POSTS";
