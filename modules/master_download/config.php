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
	'title' => 'Master Download',
	'description' => 'System zarządzania plikami udostępnionymi',
	'developer' => 'Bartłomiej \'M@ster\' Baron',
	'support' => 'http://bbproject.net/',
	'version' => '1.0',
	'dir' => 'master_download',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$new_table[1] = array(
	"master_download_files",
	"(
		`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`subcat` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
		`name` VARCHAR(100) NOT NULL DEFAULT '',
		`desc` TEXT NOT NULL DEFAULT '',
		`url` VARCHAR(200) NOT NULL DEFAULT '',
		`img` VARCHAR(200) NOT NULL DEFAULT '',
		`size` INT UNSIGNED NOT NULL DEFAULT '0',
		`count` INT UNSIGNED NOT NULL DEFAULT '0',
		`date` INT UNSIGNED NOT NULL DEFAULT '0',
		`mirror` TEXT NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"master_download_cats",
	"(
		`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(100) NOT NULL DEFAULT '',
		`desc` TEXT NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[3] = array(
	"master_download_subcats",
	"(
		`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`cat` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
		`name` VARCHAR(100) NOT NULL DEFAULT '',
		`desc` TEXT NOT NULL DEFAULT '',
		`viewaccess` VARCHAR(255) NOT NULL DEFAULT '3',
		`getaccess` VARCHAR(255) NOT NULL DEFAULT '2',
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "master_download_files";
$drop_table[2] = "master_download_cats";
$drop_table[3] = "master_download_subcats";

$admin_page[1] = array(
	'title' => 'Master Download Panel',
	'image' => 'images/mdp.gif',
	'page' => 'admin/master_download_panel.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);