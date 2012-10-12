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
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
?>
<select class="input inputSearch" name="search_folder" id="search_folder">
	<?php 
	
					foreach(getFolderListing(CONFIG_SYS_ROOT_PATH) as $k=>$v)
					{
						?>
      <option value="<?php echo $v; ?>" ><?php echo shortenFileName($k, 30); ?></option>
      <?php 
					}
		
				?>            	
</select>
<?php 
}
catch(userException $exception)
{
    userErrorHandler($exception);
}
?>