{*
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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
| Co-Author: Daywalker
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
*}

<!DOCTYPE html>
<html>
	<head>
		<title>{$Theme.Title}</title>
		<meta charset="{i18n('html_charset')}">
		<meta name="description" content="{$Theme.Desc}">
		<meta name="keywords" content="{$Theme.Keys}">
		{literal}
			<script> 
				var addr_images = "{/literal}{$ADDR_IMAGES}{literal}";
				var addr_site = "{/literal}{$ADDR_SITE}{literal}";
			</script>
		{/literal}
		<link href="{$ADDR_FAVICON}" type="image/x-icon" rel="shortcut icon">
		<link href="{$ADDR_COMMON_CSS}grid.reset.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}grid.text.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}grid.960.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}jquery-ui.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}jquery.uniform.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}jquery.table.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}jquery.validationEngine.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}main.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_COMMON_CSS}facebox.css" media="screen" rel="stylesheet">
		<link href="{$THEME_CSS}styles.css" media="screen" rel="stylesheet">
		<script src="{$ADDR_COMMON_JS}jquery.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.uniform.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.tooltip.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.dataTables.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.tzineClock.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.passwordStrengthMeter.js"></script>
		<script src="{$ADDR_JS}admin-box.js"></script>
		<script src="{$ADDR_COMMON_JS}facebox.js"></script>
		<script src="{$ADDR_JS}main.js"></script>
		<script src="{$ADDR_COMMON_JS}common.js"></script>
		{literal}
		<script> 
			jQuery(function($){ 
				$('a[rel*=facebox]').facebox();
			});
		</script>
		{/literal}
		{$Theme.Tags}
	</head>
	<body>