<?php defined('EF5_SYSTEM') || exit;

return array(
	//Error message
	'Incorrect password' => 'Incorrect password, please use only alphanumeric characters.<br />Password must contain at least 6 characters.',
	'Login in use' => 'This username ('. (isset ($_POST ['username'])? $_POST ['username']: '').') is already use.',
	'Email in use' => 'This e-mail ('.(isset($_POST['email']) ? $_POST['email'] : '').') is already use.',
	'Admin banned' => 'On this page there are displayed users, who have been banned by website administrators.<br />To unlock the user simply click <em>Unban</ em>.',
	'Not activated by link' => 'On this page there are displayed users, who have not yet clicked activation link sent them in e-mail.<br />To activate the user simply click <em>Activate</em>.',
	'Not activated by admin' => 'On this page there are displayed users, who have not yet been activated by administrator<br />To activate the user simply click <em>Activate</em>.',
	'Hidden users' => 'Users who have status <em>Hidden</em> will not be displayed in online users list.<br />To make user visible simply click <em>Restore</em>.',
	'Default roles' => '<small>Default <em>Guest</em> and <em>User</em>.</small>',
	'Recipients' => 'Recipients: <small>Type the name of the user to whom you want to send an e-mail.</small>',
	'Hide recipients?' => 'Hide recipients? <small>Recipients will not be able to see, who get the e-mail.</small>',
	// Create/Edit account
	'Avatar requirements' => 'Maximum requirements: :filesize KB, :width x :height px',
);
