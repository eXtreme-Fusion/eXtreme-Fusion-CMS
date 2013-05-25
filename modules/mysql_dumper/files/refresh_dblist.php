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
	
	// Konfigurationsdateien, die aktualisiert werden sollen
	// configurations to update
	// mehrere Dateien so angeben | enter more than one configurationsfile like this
	// $configurationfiles=array('mysqldumper','db2');
	/////////////////////////////////////////////////////////////////////////
	$configurationfiles=array(
							'mysqldumper'
	);

	define('APPLICATION_PATH',realpath(dirname(__FILE__)));
	chdir(APPLICATION_PATH);
	include_once ( APPLICATION_PATH . '/inc/functions.php' );
	$config['language']='en';
	$config['theme']="msd";
	$config['files']['iconpath']='css/' . $config['theme'] . '/icons/';

	foreach ($configurationfiles as $conf)
	{
		$config['config_file']=$conf;
		include ( $config['paths']['config'] . $conf . '.php' );
		GetLanguageArray();
		SetDefault();
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
?>