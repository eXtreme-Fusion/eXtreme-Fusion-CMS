<?php
/**
 * Ajax image editor platform
 * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
 * @link www.phpletter.com
 * @since 22/May/2007
 *
 */
require_once '../../../config.php';
require_once DIR_SYSTEM.'admincore.php';
try
{
	if ( ! $_user->hasPermission('module.ajax_menager.admin'))
	{
		throw new userException(__('Access denied'));
	}
	include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");	
	if(!empty($_GET['path']) && file_exists($_GET['path']) && is_file($_GET['path']))
	{
		include_once(CLASS_IMAGE);
		$image = new Image(true);
		if($image->loadImage($_GET['path']))
		{
			if($image->resize(CONFIG_IMG_THUMBNAIL_MAX_X, CONFIG_IMG_THUMBNAIL_MAX_Y, true, true))
			{
				$image->showImage();
			}else 
			{
				echo PREVIEW_NOT_PREVIEW . ".";	
			}
		}else 
		{
			echo PREVIEW_NOT_PREVIEW . "..";			
		}

			
	}else 
	{
		echo PREVIEW_NOT_PREVIEW . "...";
	}
}
catch(userException $exception)
{
    userErrorHandler($exception);
}
?>
