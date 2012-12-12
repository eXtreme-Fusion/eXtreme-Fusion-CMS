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
		<link href="{$ADDR_FAVICON}" type="image/x-icon" rel="shortcut icon">
		<link href="{$ADDR_CSS}grid.reset.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}grid.text.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}grid.960.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}jquery.ui.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}jquery.uniform.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}jquery.table.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}jquery.validationEngine.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}jquery.colorpicker.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}main.css" media="screen" rel="stylesheet">
		<link href="{$ADDR_CSS}facebox.css" media="screen" rel="stylesheet">
		<link href="{$THEME_CSS}styles.css" media="screen" rel="stylesheet">
		<script src="{$ADDR_COMMON_JS}jquery.js"></script>
		<script src="{$ADDR_JS}jquery.uniform.js"></script>
		<script src="{$ADDR_JS}jquery.tooltip.js"></script>
		<script src="{$ADDR_JS}jquery.dataTables.js"></script>
		<script src="{$ADDR_JS}jquery.tzineClock.js"></script>
		<script src="{$ADDR_JS}jquery.passwordStrengthMeter.js"></script>
		<script src="{$ADDR_JS}admin-box.js"></script>
		<script src="{$ADDR_JS}facebox.js"></script>
		<script src="{$ADDR_JS}main.js"></script>
		<script src="{$ADDR_JS}common.js"></script>
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