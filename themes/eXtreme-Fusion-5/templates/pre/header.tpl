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
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
| Co-Author: Daywalker
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
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
	<link href="{$ADDR_FAVICON}" rel="shortcut icon">
	<link href="{$THEME_CSS}main.css" rel="stylesheet">
	<link href="{$THEME_CSS}styles.css" rel="stylesheet">
	<link href="{$THEME_CSS}jquery/jquery.uniform.min.css" rel="stylesheet">
	<link href="{$THEME_CSS}jquery/jquery.qtip.min.css" rel="stylesheet">
	<link href="{$ADDR_COMMON_CSS}grid.960.css" rel="stylesheet">
	<link href="{$ADDR_COMMON_CSS}jquery-ui.css" rel="stylesheet">
	<link href="{$ADDR_CSS}main.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="{$THEME_JS}html5shiv.min.js"></script>
	<![endif]-->
	<script src="{$ADDR_COMMON_JS}jquery.js"></script>
	<script src="{$ADDR_COMMON_JS}jquery-ui.js"></script>
	<script src="{$THEME_JS}jquery.main.js"></script>
	<script src="{$THEME_JS}jquery.tools.min.js"></script>
	<script src="{$THEME_JS}jquery.uniform.min.js"></script>
	<script src="{$THEME_JS}jquery.qtip.min.js"></script>
	<script src="{$ADDR_COMMON_JS}common.js"></script>
    
	{$Theme.Tags}
</head>
<body>