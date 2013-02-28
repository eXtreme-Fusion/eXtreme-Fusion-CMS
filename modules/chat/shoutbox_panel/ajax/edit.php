<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
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
				<textarea class="InfoBoxInput" type="text" name="content" autocomplete="off"><?php echo $msg; ?></textarea>
				<input class="InfoBoxButton" type="submit" name="edit" value="Wyślij" />
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
