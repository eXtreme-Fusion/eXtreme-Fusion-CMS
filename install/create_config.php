<?php
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
**********************************************************/

$config = '<?php'.PHP_EOL;

$config .= '/*********************************************************'.PHP_EOL;
$config .= '| eXtreme-Fusion 5'.PHP_EOL;
$config .= '| Content Management System'.PHP_EOL;
$config .= '|'.PHP_EOL;
$config .= '| Copyright (c) 2005-'.date('Y').' eXtreme-Fusion Crew'.PHP_EOL;
$config .= '| http://extreme-fusion.org/'.PHP_EOL;
$config .= '|'.PHP_EOL;
$config .= '| This program is released as free software under the'.PHP_EOL;
$config .= '| Affero GPL license. You can redistribute it and/or'.PHP_EOL;
$config .= '| modify it under the terms of this license which you'.PHP_EOL;
$config .= '| can read by viewing the included agpl.txt or online'.PHP_EOL;
$config .= '| at www.gnu.org/licenses/agpl.html. Removal of this'.PHP_EOL;
$config .= '| copyright header is strictly prohibited without'.PHP_EOL;
$config .= '| written permission from the original author(s).'.PHP_EOL;
$config .= '| '.PHP_EOL;
$config .= '**********************************************************/'.PHP_EOL.PHP_EOL;
$config .= 'defined(\'DS\') || define(\'DS\', DIRECTORY_SEPARATOR);'.PHP_EOL.PHP_EOL;

$config .= '#Database'.PHP_EOL.PHP_EOL;

$config .= '$_dbconfig = array('.PHP_EOL;
$config .= '    \'host\' => \''.$db_host.'\','.PHP_EOL;
$config .= '    \'port\' => \''.$db_port.'\','.PHP_EOL;
$config .= '    \'user\' => \''.$db_user.'\','.PHP_EOL;
$config .= '    \'password\' => \''.$db_pass.'\','.PHP_EOL;
$config .= '    \'database\' => \''.$db_name.'\','.PHP_EOL;
$config .= '    \'prefix\' => \''.$db_prefix.'\','.PHP_EOL;
$config .= '    \'charset\' => \'utf8\','.PHP_EOL;
$config .= '    \'version\' => \'eXtreme-Fusion CMS - Ninja Edition '.SYSTEM_VERSION.'\''.PHP_EOL;
$config .= ');'.PHP_EOL.PHP_EOL;

$config .= '#Routing'.PHP_EOL.PHP_EOL;


$config .= '$_route = array('.PHP_EOL;
$config .= '	//Change this to TRUE if your server has been configured to work with $_SERVER[\'PATH_INFO\']'.PHP_EOL;
$config .= '    \'custom_furl\' => '.($custom_furl_choice ? 'TRUE' : 'FALSE').','.PHP_EOL;
$config .= '	//Change this to TRUE if your server has got configured modRewrite'.PHP_EOL;
$config .= '    \'custom_rewrite\' => '.($custom_rewrite_choice ? 'TRUE' : 'FALSE').','.PHP_EOL;
$config .= ');'.PHP_EOL.PHP_EOL;

$config .= '#Cookie && cache'.PHP_EOL.PHP_EOL;

$config .= 'defined(\'COOKIE_PREFIX\') || define(\'COOKIE_PREFIX\', \''.$cookie_prefix.'\');'.PHP_EOL;
$config .= 'defined(\'CACHE_PREFIX\') || define(\'CACHE_PREFIX\', \''.$cache_prefix.'\');'.PHP_EOL.PHP_EOL;

$config .= '#Main path && site address'.PHP_EOL.PHP_EOL;

$config .= 'defined(\'DIR_SITE\') || define(\'DIR_SITE\', dirname(__FILE__).DS);'.PHP_EOL;
$config .= 'defined(\'ADDR_SITE\') || define(\'ADDR_SITE\', \''.$site_url.'\');'.PHP_EOL.PHP_EOL;

$config .= '#Encryption'.PHP_EOL.PHP_EOL;

$config .= 'defined(\'CRYPT_KEY\') || define(\'CRYPT_KEY\', \''.md5(uniqid(time())).'\');'.PHP_EOL;
$config .= 'defined(\'CRYPT_IV\') || define(\'CRYPT_IV\', \''.substr(md5(uniqid(time())), 4, 8).'\');';
