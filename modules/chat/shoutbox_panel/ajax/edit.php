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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
| Author: Marcus Gottschalk (MarcusG)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
try
{
	require_once '../../../../system/sitecore.php';
	if ($_user->iAdmin() && $_request->get('post')->isNum())
	{
	$msg = $_pdo->getField('SELECT content FROM [chat_messages] WHERE id = :id', array(
		array(':id', $_request->get('post')->show(), PDO::PARAM_INT)
	));

	?>
				<input type="hidden" value="<?php echo $_request->get('post')->show() ?>" name="post_edit_id" />
				<textarea class="InfoBoxInput" name="content"><?php echo $msg; ?></textarea>
				<input class="InfoBoxButton" type="submit" name="edit" value="Send" />
	<?php

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
