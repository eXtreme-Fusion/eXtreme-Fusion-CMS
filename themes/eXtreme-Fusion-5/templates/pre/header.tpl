<!DOCTYPE html>
<html>
<head>
	<title>{$Theme.Title}</title>
	<meta charset="{i18n('Charset')}">
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
	<link href="{$ADDR_CSS}grid.960.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="{$THEME_JS}html5shiv.min.js"></script>
	<![endif]-->
	<script src="{$ADDR_COMMON_JS}jquery.js"></script>
	<script src="{$THEME_JS}jquery.main.js"></script>
	<script src="{$THEME_JS}jquery.tools.min.js"></script>
	<script src="{$THEME_JS}jquery.uniform.min.js"></script>
	<script src="{$THEME_JS}jquery.qtip.min.js"></script>
    
	{$Theme.Tags}
</head>
<body>