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
	'title' => __('Feedback'),
	'description' => __('Wysyłanie przez użytkowników informacji i opinii co do strony www.'),
	'developer' => 'eXtreme Crew',
	'support' => 'http://extreme-fusion.org',
	'version' => '1.0',
	'dir' => 'feedback',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$admin_page[1] = array(
	'title' => __('Galeria zdjęć'),
	'image' => 'templates/images/gallery.png',
	'page' => 'admin/gallery.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'sett',
	'desc' => __('Uprawnienie umożliwiające zarządzanie ustawieniami w galerii.')
);

$perm[2] = array(
	'name' => 'admin',
	'desc' => __('Uprawnienie umożliwiające dodawanie zdjęc do galerii.')
);

$menu_link[1] = array(
	'title' => __('Geleria zdjęć'),
	'url' => 'gallery',
	'visibility' => '3'
);

$new_table[1] = array(
	"gallery_sett",
	"(
		`key` VARCHAR(100) NOT NULL DEFAULT '',
		`value` text NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"gallery_cats",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description` varchar(255) DEFAULT NULL,
		`file_name` VARCHAR(250) NOT NULL DEFAULT '',
		`datestamp` INT(10) UNSIGNED DEFAULT '0' NOT NULL,
		`order` SMALLINT(5) UNSIGNED NOT NULL,
		`access` VARCHAR(255) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[3] = array(
	"gallery_albums",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description` varchar(255) DEFAULT NULL,
		`file_name` VARCHAR(250) NOT NULL DEFAULT '',
		`cat` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
		`datestamp` INT(10) UNSIGNED DEFAULT '0' NOT NULL,
		`order` SMALLINT(5) UNSIGNED NOT NULL,
		`access` VARCHAR(255) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[4] = array(
	"gallery_photos",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` VARCHAR(250) NOT NULL DEFAULT '',
		`file_name` VARCHAR(250) NOT NULL DEFAULT '',
		`path_absolute` VARCHAR(250) NOT NULL DEFAULT '',
		`path_url` VARCHAR(250) NOT NULL DEFAULT '',
		`comment` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
		`rating` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
		`watermark` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
		`description` varchar(255) DEFAULT NULL,
		`album` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
		`user` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
		`datestamp` INT(10) UNSIGNED DEFAULT '0' NOT NULL,
		`order` SMALLINT(5) UNSIGNED NOT NULL,
		`access` VARCHAR(255) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_row[1] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('animation_speed', 'normal')"
);

$new_row[2] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('slideshow', '5000')"
);

$new_row[3] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('autoplay_slideshow', 'false')"
);

$new_row[4] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('opacity', '0.80')"
);

$new_row[5] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('show_title', 'true')"
);

$new_row[6] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('allow_resize', 'true')"
);

$new_row[7] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('default_width', '500')"
);

$new_row[8] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('default_hight', '344')"
);

$new_row[9] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('counter_separator_label', '/')"
);

$new_row[10] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('theme', 'dark_square')"
);

$new_row[11] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('horizontal_padding', '20')"
);

$new_row[12] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('hideflash', 'false')"
);

$new_row[13] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('wmode', 'opaque')"
);

$new_row[14] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('autoplay', 'true')"
);

$new_row[15] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('modal', 'false')"
);

$new_row[16] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('deeplinking', 'true')"
);

$new_row[17] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('overlay_gallery', 'true')"
);

$new_row[18] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('keyboard_shortcuts', 'true')"
);

$new_row[19] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('social_tools', 'false')"
);

$new_row[20] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('ie6_fallback', 'true')"
);

$new_row[21] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('allow_comment', 'true')"
);

$new_row[22] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('allow_rating', 'true')"
);

$new_row[23] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('thumb_compression', 'gd2')"
);

$new_row[24] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('thumbnail_width', '60')"
);

$new_row[25] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('thumbnail_hight', '60')"
);

$new_row[26] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('photo_max_width', '1600')"
);

$new_row[27] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('photo_max_hight', '1200')"
);

$new_row[28] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('watermark_logo', 'extreme-fusion-logo-light.png')"
);

$new_row[29] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('max_file_size', '1024000')"
);

$new_row[30] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('allow_ext', '.bmp, .jpeg')"
);

$new_row[31] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('cache_expire', '3600')"
);

$new_row[32] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('title', '".__('Galeria zdjęć portalu extreme-fusion.org')."')"
);
$new_row[33] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('description', '".__('')."')"
);
$new_row[34] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('cats_per_page', '20')"
);
$new_row[35] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('albums_per_page', '20')"
);
$new_row[36] = array(
	"gallery_sett", 
	"(`key`, `value`) VALUES ('photos_per_page', '20')"
);

$drop_table[1] = "gallery_sett";
$drop_table[2] = "gallery_cats";
$drop_table[3] = "gallery_albums";
$drop_table[4] = "gallery_photos";

$tag_supplement[1] = "GALLERY_CATS";
$tag_supplement[2] = "GALLERY_ALBUMS";
$tag_supplement[3] = "GALLERY_PHOTOS";
$tag_supplement[4] = "GALLERY_SETT";
