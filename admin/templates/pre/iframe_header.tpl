<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="{i18n('Charset')}" />
		<link rel="shortcut icon" href="{$ADDR_FAVICON}" type="image/x-icon" />
		<link href="{$ADDR_COMMON_CSS}grid.reset.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}grid.text.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}grid.960.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}jquery-ui.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}jquery.uniform.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}jquery.table.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}jquery.validationEngine.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}jquery.tipTip.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_COMMON_CSS}jquery.colorpicker.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_ADMIN_CSS}jquery.tagedit.css" media="screen" rel="stylesheet" />
		<link href="{$ADDR_ADMIN_CSS}main.css" media="screen" rel="stylesheet" />

		<script src="{$ADDR_COMMON_JS}jquery.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery-ui.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.corner.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.jeditable.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.tooltip.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.tipTip.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.dataTables.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.validationEngine.js"></script>
		<script src="{$ADDR_COMMON_JS}jquery.uniform.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.tagedit.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.autoGrowInput.js"></script>
		<script src="{$ADDR_ADMIN_JS}main.functions.js"></script>
		<script src="{$ADDR_ADMIN_JS}modules.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.Alert.js"></script>
		<script src="{$ADDR_ADMIN_JS}main.js"></script>
		<script src="{$ADDR_ADMIN_JS}ckeditor/ckeditor.js"></script>
		<script src="{$ADDR_COMMON_JS}common.js"></script>
		{literal}
			<script>
				var addr_site = '{/literal}{$ADDR_SITE}{literal}';

				$(function() {
					{/literal}{if $HereIsPage}{literal}
						$('.Cancel').click(function(){
							parent.window.location.reload();
						});
						var yScroll = document.getElementById('PageScroll').scrollHeight;
						if (yScroll > 500) {
							$('html').css('overflow-y', 'scroll');
						} else {
							$('html').css('overflow-y', 'hidden');
						}
					{/literal}{/if}{literal}
				});
				$(document).ready( function() {
					$(".confirm_button").live('click', function() {
						var href = $(this).attr('href');
						jConfirm('{/literal}{i18n('Yes')}{literal}','{/literal}{i18n('No')}{literal}','{/literal}{i18n('Usunąć ten element?')}{literal}', '{/literal}{i18n('Okno operacji')}{literal}', function(r) {
								if(r == true) {
									document.location = href;
								}
						});
						return false;
					});
				});

				$(document).ready(function() {
					$("#Notes").accordion({
						autoHeight: false,
						collapsible: true,
						active: false,
					});

					$('.edit').editable('{/literal}{$ADDR_ADMIN}{literal}pages/notes.php', {
						 indicator : '<img src="{/literal}{$ADDR_ADMIN_ICONS}{literal}loading.gif">',
						 tooltip   : '{/literal}{i18n('Click to edit...')}{literal}',
						 event     : 'dblclick.editable',
						 id 	   : 'note_id',
						 name 	   : 'note_title',
						 onblur	   : 'submit'
					});

					$('.edit_area').editable('{/literal}{$ADDR_ADMIN}{literal}pages/notes.php', {
						 type      : 'textarea',
						 indicator : '<img src="{/literal}{$ADDR_ADMIN_ICONS}{literal}loading.gif">',
						 tooltip   : '{/literal}{i18n('Click to edit...')}{literal}',
						 rows	   : '4',
						 id 	   : 'note_id',
						 name 	   : 'note_note',
						 onblur	   : 'submit'
					});
				});
			</script>
		{/literal}
		{if $HereIsPage}<style type="text/css">{literal}body {padding:0} #Content{margin-bottom:1px;} h3 {border-radius:5px;}{/literal}</style>{/if}
	</head>
	<body id="PageScroll">
		<div id="Content">
			<div class="corner4px">
				<div class="ctl">
					<div class="ctr">
						<div class="ctc"></div>
					</div>
				</div>
				<div class="cc">
					<div id="IframeOPT" class="container_12">