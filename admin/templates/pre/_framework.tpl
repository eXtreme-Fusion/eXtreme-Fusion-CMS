<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8" />
		<title>{$SystemVersion}</title>
		<link rel="shortcut icon" href="{$ADDR_FAVICON}" type="image/x-icon" />
		<link rel="stylesheet" href="{$ADDR_ADMIN_CSS}grid.reset.css" media="screen" />
		<link rel="stylesheet" href="{$ADDR_ADMIN_CSS}grid.text.css" media="screen" />
		<link rel="stylesheet" href="{$ADDR_ADMIN_CSS}grid.960.css" media="screen" />
		<link rel="stylesheet" href="{$ADDR_ADMIN_CSS}_framework.css" media="screen" />
		
		<script src="{$ADDR_COMMON_JS}jquery.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.ui.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.tooltip.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.tzineClock.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.layout.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.countdown.js"></script>
		<script src="{$ADDR_ADMIN_JS}jquery.autoGrowInput.js"></script>
		<script src="{$ADDR_ADMIN_JS}modules.js"></script>
		<script src="{$ADDR_ADMIN_JS}_framework.js"></script>
		<script>
			{literal}
				if (self != top) top.location.href = self.location.href;
				$(function() {
					$('#SessionExpire strong').countdown({until: '{/literal}{$SessionExpire}{literal}', onTick: highlightLast5, format: 'HMS', significant: 3});
			
					{/literal}{if $History}{literal}
						$('#mainFrame').attr('src', '{/literal}{$ADDR_SITE}{$History.Page}{literal}');
						$("#Navigation ul").removeClass("current");
						$("#Navigation .page-"+{/literal}{$History.Current}{literal}).addClass('current');
					{/literal}{/if}{literal}
				});
			{/literal}
		</script>
    </head>
	{if $Action == 'login'}
		<body id="login-bg">
			{if $message}<div class="{$class}">{$message}</div>{/if}
				<div id="IframeOPT" class="container_12">
					<form action="{$FILE_SELF}?action=login" method="post">
						<div id="login-holder">
							<div id="logo-login">
								<img src="{$ADMIN_TEMPLATE_IMAGES}shared/extreme-fusion-logo.png" alt="eXtreme-Fusion logo">
							</div>
							<div class="clear"></div>
							<div id="loginbox">
								<div id="login-inner">
									<div>
										<label for="User">{i18n('User')}:</label>
										<input type="text" id="User" name="user" autocomplete="off" class="login-inp">
									</div>
									<div>
										<label for="Pass">{i18n('Password:')}</label>
										<input type="password" id="Pass" name="pass" autocomplete="off" class="login-inp">
									</div>
									<div id="inp-sub">
										<input type="submit" class="submit-login" name="login" value="{i18n('Login')}">
									</div>
								</div>
								<div class="clear"></div>
								<div id="footer">
									2005-{$CurrentYear} &copy; <a href="http://www.extreme-fusion.org/" rel="blank">{$SystemVersion}</a>
								</div>
							</div>
						</div>
					</form>
				</div>
		</body>
	{else}
        <body>
			<div class="ui-layout-north">
				<div id="PageTopOuter">
					<div id="PageTop">
						<div id="FrameworkLogo">
							<img src="{$ADMIN_TEMPLATE_IMAGES}shared/extreme-fusion-logo.png" alt="eXtreme-Fusion logo">
						</div>
						<div id="fancyClock"></div>
						<div class="clear"></div>
					</div>
				</div>
				<div id="NavOuterRepeat">
					<div class="NavHolder">
						<div id="NavRight">
							<div class="divider">&nbsp;</div>
							<a href="{$ADDR_ADMIN}pages/settings_ef.php" target="mainFrame" id="SystemSettings">
								<img src="{$ADMIN_TEMPLATE_IMAGES}shared/nav/settings.gif" alt="Settings">
							</a>
							<div class="clear">&nbsp;</div>
						</div>
						<div id="Navigation">
							<div class="table">
								<ul class="select current">
									<li><a href="{$ADDR_ADMIN}pages/home.php" target="mainFrame"><strong><span>{i18n('Panel')}</span></strong></a></li>
								</ul>
								{if $Count.1 > 0}
									<ul class="select page-1">
										<li><a id="Menu-1" href="#"><strong><span>{i18n('Manage content')}</span></strong></a></li>
									</ul>
								{/if}
								{if $Count.2 > 0}
									<ul class="select page-2">
										<li><a id="Menu-2" href="#"><strong><span>{i18n('Manage users')}</span></strong></a></li>
									</ul>
								{/if}
								{if $Count.3 > 0}
									<ul class="select page-3">
										<li><a id="Menu-3" href="#"><strong><span>{i18n('Manage site')}</span></strong></a></li>
									</ul>
								{/if}
								{if $Count.4 > 0}
									<ul class="select page-4">
										<li><a id="Menu-4" href="#"><strong><span>{i18n('Settings')}</span></strong></a></li>
									</ul>
								{/if}
								<ul class="select page-5">
									<li><a id="Menu-5" href="#"><strong><span>{i18n('Modules')}</span></strong></a></li>
								</ul>
								<ul class="select">
									<li><a href="http://www.mindmeister.com/97887012/extreme-fusion-v-5-0" target="mainFrame"><strong><span>MindMeister</span></strong></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ui-layout-west">
				<ul id="menu">
					<li>
						<a href="{$ADDR_ADMIN}" class="sub-menu">{i18n('Refresh')} <img src="{$ADMIN_TEMPLATE_IMAGES}icons/refresh.png" alt="Refresh"></a>
					</li>
					<!--<li>
						<a href="{$ADDR_SITE}edit_profile.html" target="mainFrame" class="sub-menu">{i18n('Edit profile')} <img src="{$ADMIN_TEMPLATE_IMAGES}icons/useredit.png" alt="Edit profile"></a>
					</li>
					<li>
						<a href="{$ADDR_SITE}messages.html" target="mainFrame" id="GetMessages" class="sub-menu" name="UserID-{$UserID}">{if $Messages != 0}<strong>({$Messages})</strong>{/if} {i18n('Private messages')} <img src="{$ADMIN_TEMPLATE_IMAGES}icons/messages.png" alt="Private messages"></a>
					</li>-->
					<li>
						<a href="{$ADDR_ADMIN}pages/users.php" target="mainFrame" class="sub-menu">{i18n('Users')} <img src="{$ADMIN_TEMPLATE_IMAGES}icons/users.png" alt="Users"></a>
					</li>
					<li>
						<a href="{$FILE_SELF}?action=logout" class="sub-menu">{i18n('Logout')} <img src="{$ADMIN_TEMPLATE_IMAGES}icons/logout.png" alt="Logout"></a>
					</li>
					<li>
						<a href="{$ADDR_SITE}index.php" class="sub-menu">{i18n('Go to homepage')} <img src="{$ADMIN_TEMPLATE_IMAGES}icons/home.png" alt="Homepage"></a>
					</li>
				</ul>
			</div>
			<div id="SessionExpire" class="tip" title="Kliknij, by przedłużyć sesję">{i18n('Time to end of session')}: <strong></strong></div>
			<div id="Footer" class="ui-layout-south">
				<div id="FooterLeft">2005-{$CurrentYear} &copy; <a href="http://www.extreme-fusion.org/" rel="blank">{$SystemVersion}</a></div>
			</div>
			<iframe id='mainFrame' name='mainFrame' width='100%' height='600' class="ui-layout-center" frameborder='0' scrolling='auto' src='pages/home.php'></iframe>
		</body>
    {/if}
</html>