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
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->load('settings_time');

	if ( ! $_user->hasPermission('admin.settings_time'))
	{
		throw new userException(__('Access denied'));
	}
	
	$_fav->setFavByLink('settings_time.php', $_user->get('id'));
	
	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
    {
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), array(
			'delete' => array('Data has been deleted.', 'Error! Data has not been deleted.'),
			'edit' => array('Data has been edited.', 'Error! Data has not been edited.')
		));

		$_tpl->assign('view', 'formats');
    }

	if ($_request->post('save_settings')->show())
	{
		$_sett->update(array(
			'shortdate' => $_request->post('shortdate')->strip(),
			'longdate' => $_request->post('longdate')->strip(),
			'timezone' => $_request->post('timezone')->strip(),
			'offset_timezone' => $_request->post('offset_timezone')->strip(),
			'user_custom_offset_timezone' => $_request->post('user_custom_offset_timezone')->isNum(TRUE)
		));
		
		$_system->clearCacheRecursive($_files);
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
		$_tpl->assign('view', 'settings');
	}
	else if ($_request->post('save_formats')->show() && $_request->get('id', FALSE)->show() === FALSE)
	{
		$_pdo->exec('INSERT INTO [time_formats] (value) VALUES (:value)', array(':value', $_request->post('new_time_format')->strip(), PDO::PARAM_STR));

		$_tpl->assign('view', 'formats');

		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}
	else if ($_request->post('save_formats')->show() && $_request->get('id')->show() && $_request->get('id')->isNum())
	{
		$_pdo->exec('UPDATE [time_formats] SET value = :value WHERE id = '.$_request->get('id')->show(), array(':value', $_request->post('new_time_format')->strip(), PDO::PARAM_STR));

		$_log->insertSuccess('edit', __('Data has been saved.'));

		$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
	}
	else if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
	{
		$_pdo->exec('DELETE FROM [time_formats] WHERE id = :id', array(':id', $_request->get('id')->show(), PDO::PARAM_INT));

		$_log->insertSuccess('edit', __('Data has been saved.'));

		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
	}
	else if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
	{
		$row = $_pdo->getRow('SELECT value FROM [time_formats] WHERE id = '.$_request->get('id')->show());

		$_tpl->assign('new_time_format', $row['value']);
		$_tpl->assign('view', 'formats');
	}

	$offset_opts = array(
		'-12.0'	    => '(GMT -12:00) Eniwetok, Kwajalein',
		'-11.0'	    => '(GMT -11:00) Midway Island, Samoa',
		'-10.0'	    => '(GMT -10:00) Hawaii',
		'-9.0'		=> '(GMT -9:00) Alaska',
		'-8.0'		=> '(GMT -8:00) Pacific Time (US &amp; Canada)',
		'-7.0'		=> '(GMT -7:00) Mountain Time (US &amp; Canada)',
		'-6.0'		=> '(GMT -6:00) Central Time (US &amp; Canada), Mexico City',
		'-5.0'		=> '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima',
		'-4.0'		=> '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz',
		'-3.5'		=> '(GMT -3:30) Newfoundland',
		'-3.0'		=> '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
		'-2.0'		=> '(GMT -2:00) Mid-Atlantic',
		'-1.0'		=> '(GMT -1:00 hour) Azores, Cape Verde Islands',
		'0.0'		=> '(GMT) Western Europe Time, London, Lisbon, Casablanca',
		'1.0'		=> '(GMT +1:00) Warsaw, Brussels, Copenhagen, Madrid, Paris',
		'2.0'		=> '(GMT +2:00) Kaliningrad, South Africa',
		'3.0'		=> '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
		'3.5'		=> '(GMT +3:30) Tehran',
		'4.0'		=> '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
		'4.5'		=> '(GMT +4:30) Kabul',
		'5.0'		=> '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
		'5.5'		=> '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi',
		'5.75'		=> '(GMT +5:45) Kathmandu',
		'6.0'		=> '(GMT +6:00) Almaty, Dhaka, Colombo',
		'7.0'		=> '(GMT +7:00) Bangkok, Hanoi, Jakarta',
		'8.0'		=> '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
		'9.0'		=> '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
		'9.5'		=> '(GMT +9:30) Adelaide, Darwin',
		'10.0'		=> '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
		'11.0'		=> '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
		'12.0'		=> '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka'
	);

	$strf_time = time()+($_sett->get('offset_timezone')*3600);

	$date_opts = array(
		'%m/%d/%Y' => HELP::strfTimeToUTF("%m/%d/%Y", $strf_time),
		'%d/%m/%Y' => HELP::strfTimeToUTF("%d/%m/%Y", $strf_time),
		'%d-%m-%Y' => HELP::strfTimeToUTF("%d-%m-%Y", $strf_time),
		'%d.%m.%Y' => HELP::strfTimeToUTF("%d.%m.%Y", $strf_time),
		'%m/%d/%Y %H:%M' => HELP::strfTimeToUTF("%m/%d/%Y %H:%M", $strf_time),
		'%d/%m/%Y %H:%M' => HELP::strfTimeToUTF("%d/%m/%Y %H:%M", $strf_time),
		'%d-%m-%Y %H:%M' => HELP::strfTimeToUTF("%d-%m-%Y %H:%M", $strf_time),
		'%d.%m.%Y %H:%M' => HELP::strfTimeToUTF("%d.%m.%Y %H:%M", $strf_time),
		'%m/%d/%Y %H:%M:%S' => HELP::strfTimeToUTF("%m/%d/%Y %H:%M:%S", $strf_time),
		'%d/%m/%Y %H:%M:%S' => HELP::strfTimeToUTF("%d/%m/%Y %H:%M:%S", $strf_time),
		'%d-%m-%Y %H:%M:%S' => HELP::strfTimeToUTF("%d-%m-%Y %H:%M:%S", $strf_time),
		'%d.%m.%Y %H:%M:%S' => HELP::strfTimeToUTF("%d.%m.%Y %H:%M:%S", $strf_time),
		'%B %d %Y' => HELP::strfTimeToUTF("%B %d %Y", $strf_time),
		'%d. %B %Y' => HELP::strfTimeToUTF("%d. %B %Y", $strf_time),
		'%d %B %Y' => HELP::strfTimeToUTF("%d %B %Y", $strf_time),
		'%B %d %Y %H:%M' => HELP::strfTimeToUTF("%B %d %Y %H:%M", $strf_time),
		'%d. %B %Y %H:%M' => HELP::strfTimeToUTF("%d. %B %Y %H:%M", $strf_time),
		'%d %B %Y %H:%M' => HELP::strfTimeToUTF("%d %B %Y %H:%M", $strf_time),
		'%B %d %Y %H:%M:%S' => HELP::strfTimeToUTF("%B %d %Y %H:%M:%S", $strf_time),
		'%d. %B %Y %H:%M:%S' => HELP::strfTimeToUTF("%d. %B %Y %H:%M:%S", $strf_time),
		'%d %B %Y %H:%M:%S' => HELP::strfTimeToUTF("%d %B %Y %H:%M:%S", $strf_time)
	);

	// Dołączanie niestandardowych formatów stworzonych przez użytkownika
	$db_formats = $_pdo->getData('SELECT id, value FROM [time_formats]');

	$i = 0; $format = array();
	foreach($db_formats as $row)
	{
		$preview = HELP::strfTimeToUTF($row['value'], $strf_time);
		$date_opts[$row['value']] = $preview;

		$format[] = array(
			'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'id' => $row['id'],
			'value' => $row['value'],
			'preview' => $preview
		);
		$i++;
	}

	$_tpl->assignGroup(array(
		'format' => $format,
		'offset_timezone' => $_tpl->createSelectOpts($offset_opts, $_sett->get('offset_timezone'), TRUE),
		'timezone' => $_sett->get('timezone'),
		'shortdate' => $_tpl->createSelectOpts($date_opts, $_sett->get('shortdate'), TRUE),
		'longdate' => $_tpl->createSelectOpts($date_opts, $_sett->get('longdate'), TRUE),
		'user_custom_offset_timezone' => $_sett->get('user_custom_offset_timezone')
	));

	$_tpl->template('settings_time');
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
