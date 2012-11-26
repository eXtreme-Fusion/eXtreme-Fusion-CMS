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
	echo getRealPath($_GET['path']);
	if(!empty($_GET['path']) && file_exists($_GET['path']) && is_file($_GET['path']))
	{
		
		include_once(CLASS_MANAGER);
		$manager = new manager($_GET['path'], false);
		$fileTypes = $manager->getFileType(basename($_GET['path']));
		if($fileTypes['preview'])
		{
			switch($fileTypes['fileType'])
			{
				case "image":
					$imageInfo = @getimagesize($_GET['path']);
					if(!empty($imageInfo[0]) && !empty($imageInfo[1]))
					{
						$thumInfo = getThumbWidthHeight($imageInfo[0], $imageInfo[1], 400, 135);
						printf("<img src=\"%s\" width=\"%s\" height=\"%s\" />", getFileUrl($_GET['path']), $thumInfo['width'], $thumInfo['height']);
													
					}else 
					{
						echo PREVIEW_IMAGE_LOAD_FAILED;
					}

					break;
				case "txt":
					if(($fp = @fopen($_GET['path'], 'r')))
					{
						echo @fread($fp, @filesize($_GET['path']));
						@fclose($fp);
					}else 
					{
						echo PREVIEW_OPEN_FAILED . ".";
					}
					break;
					
				case "video":
					break;
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