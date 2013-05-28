<?php defined('EF5_SYSTEM') || exit;

return array(
	'Charset' => 'UTF-8',
	'xml_lang' => 'en',
	'Setup complete' => '<div class="valid">Completed installation. Thank you for choosing eXtreme-Fusion 5 - Ninja Edition!</div>',
	// Permissions
	'Perm: admin login' => 'The user can log in to the Admin Panel.',
	'Perm: user login' => 'The user can log in to the website.',
	'Perm: comment' => 'The user can write comments.',
	'Perm: comment edit' => 'The user can edit own comments in a specified time interval.',
	'Perm: comment edit all' => 'The user can edit all comments.',
	'Perm: comment delete' => 'The user can delete own comments in a specified time interval.',
	'Perm: comment delete all' => 'The user can delete all comments.',
	'Group: admin' => 'Administrators are people who manage the website.',
	'Group: user' => 'This group has basic permissions like possibility to log in to the website.',
	'Group: guest' => 'This group has a limited right of use of the website.',
	'Rewrite info' => 'Your server is ready to work with rewrite module, but system does not have permission to change name of file. If you want to have SEO URLs, rename the file from "rewrite" to ".htaccess". Otherwise, just ignore this message.',
	'PHP Version Error' => 'Your server does not meet the system requirements: PHP interpreter is older than the version :php_required. <br />
	What can you do:
	<ul>
	<li>Use Server Management Panel and select "choose PHP interpreter," to use newer version - note: not every hosting provides such 		tool.</ li>
	<li>Install a newer version of PHP with the packages available on the manufacturer\'s website - for advanced.</ li>
	<li>Contact technical support for your server and get responses.</ li>
	</ ul>',
	'Extension error' => 'Required extension {$extension_error.name} has not been found. It has to be loaded by the appropriate server configuration.',
	'modRewrite warning' => 'The installer could not determine if your server supports modRewrite.<br />
	Check this box if you are sure that this module is available.<br />
	It is responsible for creating SEO Friendly links.',
	'FURL warning' => 'The installer recognized that you are using a different server than Apache.<br />
	To be able to use SEO Friendly links, the server must support PATH_INFO path.<br />
	After installation, the system will try to determine if they are available, but there is a risk of error.<br />
	To prevent this, check the box below if you are sure that your server supports PATH_INFO.',
	
	
	);