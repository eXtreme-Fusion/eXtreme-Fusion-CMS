<?php defined('EF5_SYSTEM') || exit;

return array(
	'cats' => 'Categories',
	'albums' => 'Albums',
	'photos' => 'Images',
	'sett' => 'Settings',
	'Statistics' => '<h4>Statistics</h4>',
	'Cache desc' => 'Time of keeping CACHE files for Gallery module. The value given in seconds, default: 3600 sec (1h).<br />
					<a href=":file?page=sett&action=clear_cache">Delete CACHE manually.</a>',
	'Max file desc' => 'Maximum file size that is allowed by Gallery module :kbsize.
				<br />Maximum file size that is allowed by server :uploadsize.
				<br />Maximum execution time that is allowed by server :maxtime seconds.',
	'Update error' => 'Update error: Setting with key: Setting with key <strong>:key</strong> does not exist.',
	'Error while getting setting' => 'Error while getting setting: Setting with key <strong>:key</strong> does not exist.',
	'Not empty cat' => 'You can not delete category in which there are albums existing (<strong>:albums</strong>)!',
	'Not empty album' => 'You can not delete album in which there are images existing (<strong>:images</strong>)!',
	'Empty field' => 'Error: Field <strong>Title</strong> can not been empty!',
);