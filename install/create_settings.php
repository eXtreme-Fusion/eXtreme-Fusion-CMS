<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This product is licensed under the BSD License.
| http://extreme-fusion.org/ef5/license/
***********************************************************/

date_default_timezone_set('UTC');
mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER SET utf8');

/*
* Main Settings
*/

insert($_dbconfig['prefix'].'settings', array('key' => 'site_name', 'value' => 'eXtreme-Fusion CMS - Ninja Edition'));
insert($_dbconfig['prefix'].'settings', array('key' => 'description', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'keywords', 'value' => ''));

insert($_dbconfig['prefix'].'settings', array('key' => 'contact_email', 'value' => $email));
insert($_dbconfig['prefix'].'settings', array('key' => 'site_username', 'value' => $username));
insert($_dbconfig['prefix'].'settings', array('key' => 'site_intro', 'value' => '<div style="text-align:center">'.__('Welcome to your site').'</div>'));
insert($_dbconfig['prefix'].'settings', array('key' => 'footer', 'value' => 'Copyright &copy; 2005 - '.date('Y').' by the eXtreme-Fusion Crew'));
insert($_dbconfig['prefix'].'settings', array('key' => 'opening_page', 'value' => 'news'));
insert($_dbconfig['prefix'].'settings', array('key' => 'theme', 'value' => 'eXtreme-Fusion-5'));
insert($_dbconfig['prefix'].'settings', array('key' => 'locale', 'value' => $localeset));

insert($_dbconfig['prefix'].'settings', array('key' => 'site_banner', 'value' => 'images/extreme-fusion-logo-light.png'));
insert($_dbconfig['prefix'].'settings', array('key' => 'site_banner1', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'site_banner2', 'value' => ''));

insert($_dbconfig['prefix'].'settings', array('key' => 'avatar_width', 'value' => 100));
insert($_dbconfig['prefix'].'settings', array('key' => 'avatar_height', 'value' => 100));
insert($_dbconfig['prefix'].'settings', array('key' => 'avatar_filesize', 'value' => 15000));
insert($_dbconfig['prefix'].'settings', array('key' => 'avatar_ratio', 'value' => 0));

insert($_dbconfig['prefix'].'settings', array('key' => 'smtp_host', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'smtp_port', 'value' => 587));
insert($_dbconfig['prefix'].'settings', array('key' => 'smtp_username', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'smtp_password', 'value' => ''));

insert($_dbconfig['prefix'].'settings', array('key' => 'news_photo_w', 'value' => '400'));
insert($_dbconfig['prefix'].'settings', array('key' => 'news_photo_h', 'value' => '300'));
insert($_dbconfig['prefix'].'settings', array('key' => 'news_image_frontpage', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'news_image_readmore', 'value' => 0));

insert($_dbconfig['prefix'].'settings', array('key' => 'exclude_left', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'exclude_upper', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'exclude_lower', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'exclude_right', 'value' => ''));

insert($_dbconfig['prefix'].'settings', array('key' => 'cookie_secure', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'cookie_domain', 'value' => $_SERVER['HTTP_HOST']));
insert($_dbconfig['prefix'].'settings', array('key' => 'cookie_patch', 'value' => '/'));

insert($_dbconfig['prefix'].'settings', array('key' => 'cronjob_day', 'value' => time()));
insert($_dbconfig['prefix'].'settings', array('key' => 'cronjob_hour', 'value' => time()));
insert($_dbconfig['prefix'].'settings', array('key' => 'cronjob_templates_clean', 'value' => time()));

insert($_dbconfig['prefix'].'settings', array('key' => 'license_agreement', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'license_lastupdate', 'value' => 0));

insert($_dbconfig['prefix'].'settings', array('key' => 'logger_active', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'logger_optimize_active', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'logger_expire_days', 'value' => 50));
insert($_dbconfig['prefix'].'settings', array('key' => 'logger_save_removal_action', 'value' => 1));

insert($_dbconfig['prefix'].'settings', array('key' => 'cache_active', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'cache_expire', 'value' => 86400));

insert($_dbconfig['prefix'].'settings', array('key' => 'comments_enabled', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'comment_edit', 'value' => 1));

insert($_dbconfig['prefix'].'settings', array('key' => 'bad_words_enabled', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'bad_words', 'value' => ''));
insert($_dbconfig['prefix'].'settings', array('key' => 'bad_word_replace', 'value' => '****'));

insert($_dbconfig['prefix'].'settings', array('key' => 'shortdate', 'value' => '%d/%m/%Y %H:%M'));
insert($_dbconfig['prefix'].'settings', array('key' => 'longdate', 'value' => '%B %d %Y %H:%M:%S'));
insert($_dbconfig['prefix'].'settings', array('key' => 'timezone', 'value' => 'Europe/London'));
insert($_dbconfig['prefix'].'settings', array('key' => 'offset_timezone', 'value' => '1.0'));
insert($_dbconfig['prefix'].'settings', array('key' => 'user_custom_offset_timezone', 'value' => '0'));

insert($_dbconfig['prefix'].'settings', array('key' => 'enable_registration', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'enable_deactivation', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'login_method', 'value' => 'sessions'));

insert($_dbconfig['prefix'].'settings', array('key' => 'enable_terms', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'tinymce_enabled', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'ratings_enabled', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'visitorcounter_enabled', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'email_verification', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'admin_activation', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'validation', 'value' => 'a:1:{s:8:"register";s:1:"0";}'));
insert($_dbconfig['prefix'].'settings', array('key' => 'hide_userprofiles', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'userthemes', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'counter', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'flood_autoban', 'value' => 1));
insert($_dbconfig['prefix'].'settings', array('key' => 'deactivation_action', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'change_name', 'value' => 0));

insert($_dbconfig['prefix'].'settings', array('key' => 'maintenance', 'value' => 0));
insert($_dbconfig['prefix'].'settings', array('key' => 'maintenance_message', 'value' => __('Welcome to eXtreme-Fusion CMS. Thank for using our CMS, Please turn off the maintenance mode in security, onces you have finished configuring your site.')));
insert($_dbconfig['prefix'].'settings', array('key' => 'maintenance_level', 'value' => '1'));

insert($_dbconfig['prefix'].'settings', array('key' => 'default_search', 'value' => 'all'));
insert($_dbconfig['prefix'].'settings', array('key' => 'deactivation_period', 'value' => 365));
insert($_dbconfig['prefix'].'settings', array('key' => 'deactivation_response', 'value' => 14));
insert($_dbconfig['prefix'].'settings', array('key' => 'news_cats_per_page', 'value' => 25));
insert($_dbconfig['prefix'].'settings', array('key' => 'news_cats_iteam_per_page', 'value' => 10));
insert($_dbconfig['prefix'].'settings', array('key' => 'news_per_page', 'value' => 11));
insert($_dbconfig['prefix'].'settings', array('key' => 'notes_per_page', 'value' => 4));
insert($_dbconfig['prefix'].'settings', array('key' => 'users_per_page', 'value' => 10));
insert($_dbconfig['prefix'].'settings', array('key' => 'flood_interval', 'value' => 15));
insert($_dbconfig['prefix'].'settings', array('key' => 'version', 'value' => 5.0));
insert($_dbconfig['prefix'].'settings', array('key' => 'notes', 'value' => __('')));
insert($_dbconfig['prefix'].'settings', array('key' => 'loging', 'value' => serialize(array(
	'site_normal_loging_time' => 60*60*5,			// 5h
	'site_remember_loging_time' => 60*60*24*21,		// 21 days
	'admin_loging_time' => 60*30,					// 30min
	'user_active_time' => 60*5						// 5min
))));
insert($_dbconfig['prefix'].'settings', array('key' => 'routing', 'value' => serialize(array(
	'param_sep' => '/',
	'main_sep' => '/',
	'url_ext' => '.html',
	'tpl_ext' => '.tpl',
	'logic_ext' => '.php',
	'ext_allowed' => '1'
))));
insert($_dbconfig['prefix'].'settings', array('key' => 'cache', 'value' => serialize(array(
	//'expire_contact' => '3600',
	'expire_news' => '3600',
	'expire_news_cats' => '3600',
	'expire_pages' => '3600',
	'expire_profile' => '3600',
	//'expire_rules' => '3600',
	'expire_tags' => '3600',
	'expire_team' => '3600',
	'expire_users' => '3600',
))));

/** Nie usuwac/Do not remove
if ($_system->apachemoduleLoaded('mod_rewrite'))
{
	insert($_dbconfig['prefix'].'settings', array('key' => 'rewrite_module', 'value' => 1));
}
else
{
	insert($_dbconfig['prefix'].'settings', array('key' => 'rewrite_module', 'value' => 0));
}
**/
/*
* Admin Panel Settings
*/

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.bbcodes', 'bbcodes.png', 'BBCodes', 'bbcodes.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.blacklist', 'blacklist.png', 'Blacklist', 'blacklist.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.comments', 'comments.png', 'Comments', 'comments.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.groups', 'groups.png', 'Groups', 'groups.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.pages', 'pages.png', 'Pages', 'pages.php', 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.logs', 'logs.png', 'Logs', 'logs.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.urls', 'urls.png', 'URLs Generator', 'urls.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.modules', 'modules.png', 'Modules', 'modules.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.news', 'news.png', 'News', 'news.php', 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.panels', 'panels.png', 'Panels', 'panels.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.panels', 'panels.png', 'Panel Editor', 'panel_editor.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.permissions', 'permissions.png', 'Permissions', 'permissions.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.phpinfo', 'phpinfo.png', 'PHP Info', 'phpinfo.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.security', 'security.png', 'Security Politics', 'settings_security.php', '4')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings', 'settings.png', 'Main', 'settings_main.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_banners', 'settings_banners.png', 'Banners', 'settings_banners.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_cache', 'settings_cache.png', 'Cache', 'settings_cache.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_time', 'settings_time.png', 'Time and Date', 'settings_time.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_registration', 'registration.png', 'Registration', 'settings_registration.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_misc', 'settings_misc.png', 'Miscellaneous', 'settings_misc.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_users', 'settings_users.png', 'User Management', 'settings_users.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_ipp', 'settings_ipp.png', 'Item per Page', 'settings_ipp.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_logs', 'logs.png', 'Logs', 'settings_logs.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_login', 'login.png', 'Login', 'settings_login.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_routing', 'router.png', 'Router', 'settings_routing.php', 4)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.navigations', 'navigations.png', 'Site Links', 'navigations.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.smileys', 'smileys.png', 'Smileys', 'smileys.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.submissions', 'submissions.png', 'Submissions', 'submissions.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.upgrade', 'upgrade.png', 'Upgrade', 'upgrade.php', 3)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.user_fields', 'user_fields.png', 'User Fields', 'user_fields.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.user_fields_cats', 'user_fields_cats.png', 'User Field Categories', 'user_field_cats.php', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."admin (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.users', 'users.png', 'Users', 'users.php', 2)");

/**
 * Sha512 function
 * @return  string(512)
 */
function sha512($in) {
	return hash('sha512',$in);
}
//Salt is the 1st five characters of a random SHA512 hash.
$salt = substr(sha512(uniqid(rand(), true)), 0, 5);
//Store hash of salt plus a random symbol plus original password.
$hashedpwd = sha512($salt.'^'.$password1);

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."users (`username`, `password`, `salt`,  `link`, `email`, `hide_email`, `valid`, `offset`, `avatar`, `joined`, `lastvisit`, `ip`, `status`, `theme`, `roles`, `role`, `lang`) VALUES ('".$username."', '".($hashedpwd)."', '".($salt)."', '".HELP::Title2Link($username)."', '".$email."', '1', '1', '0', '', '".time()."', '0', '0.0.0.0', '0', 'Default', '".serialize(array(1, 2, 3))."', '1', '".$language."')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('b', '1')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('i', '2')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('u', '3')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('url', '4')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('mail', '5')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('img', '6')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('center', '7')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('small', '8')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('code', '9')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('quote', '10')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."bbcodes (`name`, `order`) VALUES ('spo', '11')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':)', 'smile.png', 'Smile')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (';)', 'wink.png', 'Wink')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':(', 'sad.png', 'Sad')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (';(', 'cry.png', 'Cry')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':|', 'frown.png', 'Frown')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':o', 'shock.png', 'Shock')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES ('Oo', 'blink.png', 'Blink')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':P', 'pfft.png', 'Pfft')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES ('B)', 'cool.png', 'Cool')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (';/', 'annoyed.png', 'Annoyed')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':D', 'grin.png', 'Grin')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':@', 'angry.png', 'Angry')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES ('^^', 'joyful.png', 'Joyful')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES ('-.-', 'pinch.png', 'Pinch')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."smileys (`code`, `image`, `text`) VALUES (':extreme:', '../favicon.ico', 'eXtreme-Fusion')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Bugs')."', '".HELP::Title2Link(__('Bugs'))."', 'bugs.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Downloads')."', '".HELP::Title2Link(__('Downloads'))."', 'downloads.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('eXtreme-Fusion')."', '".HELP::Title2Link(__('eXtreme-Fusion'))."', 'eXtreme-fusion.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Games')."', '".HELP::Title2Link(__('Games'))."', 'games.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Graphics')."', '".HELP::Title2Link(__('Graphics'))."', 'graphics.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Hardware')."', '".HELP::Title2Link(__('Hardware'))."', 'hardware.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Journal')."', '".HELP::Title2Link(__('Journal'))."', 'journal.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Users')."', '".HELP::Title2Link(__('Users'))."', 'users.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Mods')."', '".HELP::Title2Link(__('Mods'))."', 'mods.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Movies')."', '".HELP::Title2Link(__('Movies'))."', 'movies.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Network')."', '".HELP::Title2Link(__('Network'))."', 'network.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('News')."', '".HELP::Title2Link(__('News'))."', 'news.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Security')."', '".HELP::Title2Link(__('Security'))."', 'security.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Software')."', '".HELP::Title2Link(__('Software'))."', 'software.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Themes')."', '".HELP::Title2Link(__('Themes'))."', 'themes.png')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news_cats (`name`, `link`, `image`) VALUES ('".__('Windows')."', '".HELP::Title2Link(__('Windows'))."', 'windows.png')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."news  (`id`, `title`, `link`, `category`, `content`, `author`, `source`, `breaks`, `description`, `datestamp`, `access`, `reads`, `draft`, `sticky`, `allow_comments`, `allow_ratings`, `language`) VALUES (NULL, '".__('Example news title')."', '".__('Example news url')."', '2', '".__('Example news content')."', '1', 'http://extreme-fusion.org', '0', '".__('Example news description')."', '".time()."', '3', '1', '0', '0', '1', '1', '".$language."')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."tags  (`id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access`) VALUES (1, 'NEWS', 1, 'eXtreme-Fusion', 'extreme_fusion', '1')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."tags  (`id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access`) VALUES (2, 'NEWS', 1, 'eXtreme-Fusion 5', 'extreme_fusion_5', '1')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."tags  (`id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access`) VALUES (3, 'NEWS', 1, 'CMS', 'cms', '1')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."panels (`name`, `filename`, `content`, `side`, `order`, `type`, `access`, `display`, `status`) VALUES ('".__('Navigation')."', 'navigation_panel', '', '1', '1', 'file', '3', '0', '1')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."panels (`name`, `filename`, `content`, `side`, `order`, `type`, `access`, `display`, `status`) VALUES ('".__('Online Users')."', 'online_users_panel', '', '1', '2', 'file', '3', '0', '1')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."panels (`name`, `filename`, `content`, `side`, `order`, `type`, `access`, `display`, `status`) VALUES ('".__('Welcome Message')."', 'welcome_message_panel', '', '2', '1', 'file', '3', '0', '0')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."panels (`name`, `filename`, `content`, `side`, `order`, `type`, `access`, `display`, `status`) VALUES ('".__('User Panel')."', 'user_info_panel', '', '4', 1, 'file', '3', '0', '1')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."navigation (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('".__('Home')."', '', '3', '3', '0', '1')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."navigation (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('".__('News Cats')."', 'news_cats.html', '3', '3', '0', '2')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."navigation (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('".__('Users')."', 'users.html', '3', '3', '0', '3')");
//$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."navigation (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('".__('Login')."', 'login', '3', '1', '0', '4')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."navigation (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('".__('Materiały i wpisy')."', 'pages.html', '3', '3', '0', '4')");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."navigation (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('".__('Search')."', 'search.html', '3', '3', '0', '5')");


$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_field_cats (`name`, `order`) VALUES ('".__('Information')."', 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_field_cats (`name`, `order`) VALUES ('".__('Contact Information')."', 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_field_cats (`name`, `order`) VALUES ('".__('Miscellaneous')."', 3)");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('First name')."', 'name', 1, 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('Date of birth')."', 'old', 1, 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('Gadu-Gadu')."', 'gg', 2, 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('Skype')."', 'skype', 2, 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('Website')."', 'www', 2, 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('Living place')."', 'location', 2, 1)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`) VALUES ('".__('Signature')."', 'sig', 3, 2)");
$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."user_fields (`name`, `index`, `cat`, `type`, `option`, `register`) VALUES ('".__('Language:')."', 'lang', 1, 3, '".serialize(array('Czech', 'English', 'Polish'))."', '1')");

//$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."settings_inf (`name`, `value`, `inf`) VALUES ('version', '5.0', '(Beta)')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."pages_categories (`name`, `description`, `submitting_groups`, `is_system`) VALUES ('".__('Bez kategorii')."', '".__('Kategoria dla materiałów nieprzypisanych do żadnej kategorii')."', 0, 1)");


$result = dbquery("INSERT INTO `".$_dbconfig['prefix']."permissions` (`name`, `section`, `description`, `is_system`) VALUES
	('admin.login', 1, '".__('Perm: admin login')."', 1),
	('site.login', 2, '".__('Perm: user login')."', 1),
	('site.comment.add', 2, '".__('Perm: comment')."', 1),
	('site.comment.edit', 2, '".__('Perm: comment edit')."', 1),
	('site.comment.edit.all', 2, '".__('Perm: comment edit all')."', 1),
	('site.comment.delete', 2, '".__('Perm: comment delete')."', 1),
	('site.comment.delete.all', 2, '".__('Perm: comment delete all')."', 1),
	('admin.bbcodes', 1, '".__('Perm: admin bbcodes')."', 1),
	('admin.blacklist', 1, '".__('Perm: admin blacklist')."', 1),
	('admin.comments', 1, '".__('Perm: admin comments')."', 1),
	('admin.logs', 1, '".__('Perm: admin logs')."', 1),
	('admin.urls', 1, '".__('Perm: admin urls')."', 1),
	('admin.news_cats', 1, '".__('Perm: admin news_cats')."', 1),
	('admin.news', 1, '".__('Perm: admin news')."', 1),
	('admin.panels', 1, '".__('Perm: admin panels')."', 1),
	('admin.permissions', 1, '".__('Perm: admin permissions')."', 1),
	('admin.phpinfo', 1, '".__('Perm: admin phpinfo')."', 1),
	('admin.groups', 1, '".__('Perm: admin groups')."', 1),
	('admin.security', 1, '".__('Perm: admin security')."', 1),
	('admin.settings', 1, '".__('Perm: admin settings')."', 1),
	('admin.settings_banners', 1, '".__('Perm: admin settings_banners')."', 1),
	('admin.settings_cache', 1, '".__('Perm: admin settings_cache')."', 1),
	('admin.settings_time', 1, '".__('Perm: admin settings_time')."', 1),
	('admin.settings_registration', 1, '".__('Perm: admin settings_registration')."', 1),
	('admin.settings_misc', 1, '".__('Perm: admin settings_misc')."', 1),
	('admin.settings_users', 1, '".__('Perm: admin settings_users')."', 1),
	('admin.settings_ipp', 1, '".__('Perm: admin settings_ipp')."', 1),
	('admin.settings_logs', 1, '".__('Perm: admin settings_logs')."', 1),
	('admin.navigations', 1, '".__('Perm: admin navigations')."', 1),
	('admin.smileys', 1, '".__('Perm: admin smileys')."', 1),
	('admin.submissions', 1, '".__('Perm: admin submissions')."', 1),
	('admin.upgrade', 1, '".__('Perm: admin upgrade')."', 1),
	('admin.user_fields', 1, '".__('Perm: admin user_fields')."', 1),
	('admin.user_fields_cats', 1, '".__('Perm: admin user_fields_cats')."', 1),
	('admin.users', 1, '".__('Perm: admin users')."', 1)
");
$result = dbquery("INSERT INTO `".$_dbconfig['prefix']."permissions_sections` (`name`, `description`, `is_system`) VALUES ('admin', '".__('Administration')."', 1), ('site', '".__('Site')."', 1)");
$result = dbquery("INSERT INTO `".$_dbconfig['prefix']."groups` (`title`, `description`, `format`, `permissions`) VALUES ('".__('Admin')."', '".__('Group: admin')."', '<span style=\"color:#99bb00\">{username}</span>', '".serialize(array('*'))."'), ('".__('User')."', '".__('Group: user')."', '{username}', '".serialize(array('site.login', 'site.comment', 'site.comment.add', 'site.comment.edit'))."'), ('".__('Guest')."', '".__('Group: guest')."', '{username}', '".serialize(array())."')");

$result = dbquery("INSERT INTO ".$_dbconfig['prefix']."users_data (`user_id`) VALUES ('1')");