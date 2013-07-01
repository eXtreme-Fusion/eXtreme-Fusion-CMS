{if $logo}
<header id="site_top">
	<h1>{$logo}</h1>
</header>
{/if}

{if $menu}
<nav id="main_nav">
	<ul>
	{section=menu}
		<li{if $menu.selected} class="selected"{/if}>{$menu.sep}<a href="{$menu.link}"{if $menu.target} target="_blank"{/if}>{$menu.name}</a></li>
	{/section}
	</ul>
</nav>
{/if}

<section id="site_mid" >
	{if $LEFT}<aside id="s_left">{$LEFT}</aside>{/if}
	{if $RIGHT}<aside id="s_right">{$RIGHT}</aside>{/if}
	<section id="{if $LEFT && $RIGHT}s_center{/if}{if !$LEFT && !$RIGHT}no_both{/if}{if !$LEFT && $RIGHT}no_left{/if}{if !$RIGHT && $LEFT}no_right{/if}">
		{if $getTryLogin}
			<div class="error bold">
				Logowanie nie powiodło się. Sprawdź poprawność wprowadzanych danych i spróbuje jeszcze raz.
			</div>
		{/if}
		{$CONTENT}
	</section>
</section>

{if $footer}
<footer id="site_bot">
	{if $copyright}
		<address>{$copyright}</address>
	{/if}
	<p>{$footer}</p>
	<address>Theme by <a href="http://nlds-group.com/" title="NLDS-Group.com"><img src="{$THEME_IMAGES}nlds_logo.png" alt="NLDS-Group.com"></a></address>
	{if $admin_panel_link}<p>{$admin_panel_link}</p>{/if}
	{if $visits_count}<p>Unikalnych wizyt: {$visits_count}</p>{/if}
</footer>
{/if}