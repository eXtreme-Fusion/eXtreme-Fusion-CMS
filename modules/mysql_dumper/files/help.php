<?php
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	if ( ! $_user->hasPermission('module.mysql_dumper.admin'))
	{
		throw new userException(__('Access denied'));
	}
	
	if (!@ob_start("ob_gzhandler")) @ob_start();
	include ( './inc/header.php' );
	include ( MSD_PATH . 'language/' . $config['language'] . '/lang.php' );
	include ( MSD_PATH . 'language/' . $config['language'] . '/lang_help.php' );
	echo MSDHeader(0);
	echo headline($lang['L_CREDITS']);
	include ( MSD_PATH . 'language/' . $config['language'] . '/help.php' );
?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
echo MSDFooter();
ob_end_flush();
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