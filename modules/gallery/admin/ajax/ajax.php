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
	require_once '../../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	if ($_user->isLoggedIn())
	{

		if ( ! $_user->hasPermission('module.gallery.admin'))
		{
			throw new userException(__('Access denied'));
		}
			
		/* DRAG & DROP w kategori galerii */
		if ($_request->post('ArrayOrderGalleryCats')->show() && $_request->post('UpdateOrderGalleryCats')->show() === 'Ok')
		{
			$i = 1;
			foreach ($_request->post('ArrayOrderGalleryCats')->show() as $id)
			{
				$_pdo->exec('UPDATE [gallery_cats] SET `order` = :order WHERE `id` = :id',
					array(
						array(':id', $id, PDO::PARAM_INT),
						array(':order', $i, PDO::PARAM_INT)
					)
				);
				$i++;
			}
			_e(__('Dane zostały pomyślnie zapisane'));
			
		}
		
		/* DRAG & DROP w albumach galerii */
		if ($_request->post('ArrayOrderGalleryAlbums')->show() && $_request->post('UpdateOrderGalleryAlbums')->show() === 'Ok')
		{
			$i = 1;
			foreach ($_request->post('ArrayOrderGalleryAlbums')->show() as $id)
			{
				$_pdo->exec('UPDATE [gallery_albums] SET `order` = :order WHERE `id` = :id',
					array(
						array(':id', $id, PDO::PARAM_INT),
						array(':order', $i, PDO::PARAM_INT)
					)
				);
				$i++;
			}
			_e(__('Dane zostały pomyślnie zapisane'));
			
		}
		
		/* DRAG & DROP w zdjęciach galerii */
		if ($_request->post('ArrayOrderGalleryPhotos')->show() && $_request->post('UpdateOrderGalleryPhotos')->show() === 'Ok')
		{
			$i = 1;
			foreach ($_request->post('ArrayOrderGalleryPhotos')->show() as $id)
			{
				$_pdo->exec('UPDATE [gallery_photos] SET `order` = :order WHERE `id` = :id',
					array(
						array(':id', $id, PDO::PARAM_INT),
						array(':order', $i, PDO::PARAM_INT)
					)
				);
				$i++;
			}
			_e(__('Dane zostały pomyślnie zapisane'));
			
		}
		
		if ($_request->get('term')->show())
		{
			$_tag = New Tag($_system, $_pdo);
			
			if ($array = $_tag->getAllTag())
			{
				$data = array(); $result = array();
				foreach($array as $row)
				{
					$data[] = $row['value'];
				}
				
				foreach(array_unique($data) as $key => $value) 
				{
					if(strlen($_request->get('term')->show()) === 1 || strpos(strtolower($value), strtolower($_request->get('term')->show())) !== false) 
					{
						$result[] = '{"id":"'.$key.'","label":"'.$value.'","value":"'.$value.'"}';
					}
				}
				
				_e('['.implode(',', $result).']');
			}
			
			_e('[]');
		}
		
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