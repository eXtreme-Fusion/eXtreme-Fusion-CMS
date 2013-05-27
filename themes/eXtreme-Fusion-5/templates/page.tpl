{if $Logo}
<header id="site_top">
	<h1>{$Logo}</h1>
</header>
{/if}

{if $Menu}
<nav id="main_nav">
	<ul>
	{section=Menu}
		<li{if $Menu.selected} class="selected"{/if}>{$Menu.sep}<a href="{$Menu.link}"{if $Menu.target} target="_blank"{/if}>{$Menu.name}</a></li>
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

{if $RenderFooter}
<footer id="site_bot">
	{if $Copyright}
		<address>{$Copyright}</address>
		<p>{$License}</p>
	{/if}
	<p>{$Footer}</p>
	<address>Theme by <a href="http://nlds-group.com/" title="NLDS-Group.com"><img src="{$THEME_IMAGES}nlds_logo.png" alt="NLDS-Group.com"></a></address>
	{if $AdminLinks}<p>{$AdminLinks}</p>{/if}
	{if $VisitsCount}<p>Unikalnych wizyt: {$VisitsCount}</p>{/if}
</footer>
{/if}