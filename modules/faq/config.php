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
	'title' => 'FAQ',
	'description' => 'Umożliwia stworzenie listy najczęściej zadawanych pytań',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'faq',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$new_table[1] = array(
	"faq_settings",
	"(
		`key` VARCHAR(128) NOT NULL,
		`value` TEXT NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"faq_questions",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`question` TEXT NOT NULL,
		`answer` TEXT NOT NULL,
		`status` MEDIUMINT NOT NULL DEFAULT '0',
		`created` MEDIUMINT NOT NULL DEFAULT '0',
		`comment` MEDIUMINT NOT NULL DEFAULT '1',
		`sticky` MEDIUMINT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$menu_link[1] = array(
	'title' => __('FAQ'),
	'url' => 'faq',
	'visibility' => '3'
);

$new_row[1] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('title', '".serialize('Frequently Asked Questions')."')"
);

$new_row[2] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('description', '".serialize('')."')"
);

$new_row[3] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('display', '".serialize('3')."')"
);

$new_row[4] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('listing', '".serialize('0')."')"
);

$new_row[5] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('links', '".serialize('1')."')"
);

$new_row[6] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('back', '".serialize('Back to top')."')"
);

$new_row[7] = array(
	"faq_settings", 
	"(`key`, `value`) VALUES ('sorting', '".serialize('asc')."')"
);

$drop_table[1] = "faq_settings";
$drop_table[2] = "faq_questions";


$admin_page[1] = array(
	'title' => 'FAQ',
	'image' => 'images/faq.png',
	'page' => 'admin/faq.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);