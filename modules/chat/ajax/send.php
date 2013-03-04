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
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
| Author: Marcus Gottschalk (MarcusG)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
try
{
	require_once '../../../system/sitecore.php';
	if ($_request->post('send')->show() == 'send' && $_request->post('content')->show() != '' && $_user->iUser())
	{
		$result = $_pdo->exec('INSERT INTO [chat_messages] (`user_id`, `content`, `datestamp`) VALUES ('.$_user->get('id').', :content, '.time().')',array(
			array(':content', HELP::wordsProtect($_request->post('content')->filters('trim', 'strip')), PDO::PARAM_STR)
		));
	}
	
	if ($_request->post('send')->show() == 'edit' && $_request->post('content')->show() != '' && $_user->iAdmin())
	{
		$result = $_pdo->exec('UPDATE [chat_messages] SET `content` = :content WHERE `id` = :id', array(
			array(':content', HELP::wordsProtect($_request->post('content')->filters('trim', 'strip')), PDO::PARAM_STR),
			array(':id', $_request->post('post_id')->show(), PDO::PARAM_INT)
		));
	}
	
}
catch(optException $exception)
{
    optErrorHandler($exception);
}
catch(systemException $exception)
{
    systemErrorHandler($exception);
}
catch(userException $exception)
{
    userErrorHandler($exception);
}
catch(PDOException $exception)
{
    PDOErrorHandler($exception);
}