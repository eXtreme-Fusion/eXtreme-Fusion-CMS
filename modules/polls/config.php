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
	'title' => 'Ankiety',
	'description' => 'Moduł ankiet',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'polls',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$new_table[1] = array(
	"polls",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`question` VARCHAR(250) NOT NULL DEFAULT '',
		`response` TEXT NOT NULL DEFAULT '',
		`show_results` TINYINT UNSIGNED NOT NULL DEFAULT 1,
		`date_start` INT(10) NOT NULL DEFAULT 0,
		`date_end` INT(10) NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"polls_vote",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`poll_id` MEDIUMINT(8) UNSIGNED NOT NULL,
		`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
		`response` MEDIUMINT(8) NOT NULL,
		`date` INT(10) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "polls";
$drop_table[2] = "polls_vote";

$admin_page[1] = array(
	'title' => 'Ankiety',
	'image' => 'images/polls.png',
	'page' => 'admin/polls.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);