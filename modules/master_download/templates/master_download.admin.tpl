{if $config.development}<div class="error">{$config.developmentMessage}</div>{/if}

{if $md_download}
	{php} opentable(__('Master Download').' - '.__('Categories')) {/php}
		{section=cats}
			<div class="tbl2 bold">
				<div class="sep_1 grid_2">{$cats.name}</div>
				<div class="grid_8">{$cats.desc}</div>
			</div>
			{section=subcats}
				<div class="tbl1">
					<div class="sep_1 grid_10"><a href="{$subcats.url}"><strong>{$subcats.name}</strong></a><br />{$subcats.desc}</div>
				</div>
			{/section}
		{/section}
	{php} closetable() {/php}
{/if}

{if $md_subcat}
	{if $access}
		{php} opentable(__('Category')) {/php}
			<h4>{$cat.name}</h4>
			<div>{i18n('Description:')} {if $cat.desc}<small>{$cat.desc}</small>{else}<small>{i18n('No description')}</small>{/if}</div>
			<div class="right"><a href="{$ADDR_SITE}master_download.html">{i18n('<< Back to download')}</a></div>
		{php} closetable() {/php}
	
		{if $file}
			{php} opentable(__('Files')) {/php}
				{section=file}
					<div class="mdp {$file.row_color}">
						{if $file.img}
							<img src="{$file.img}" title="{$file.name}" class="tip">
						{else}
							<img src="{$ADDR_MDP}templates/images/brak.gif" title="{$file.name}" class="tip">
						{/if}
						<div class="mdp-title {$file.row_color2} bold">{$file.name}</div>
						<div class="mdp-desc {$file.row_color2}">{$file.desc}</div>
						<div class="mdp-info {$file.row_color2}">
							<a href="{$file.url}" class="button">{i18n('DOWNLOAD')}</a>
						</div>
						<div class="mdp-info {$file.row_color2}">
							<span class="button">{i18n('Downloads:')} {$file.count}</span>
						</div>
						<div class="mdp-info {$file.row_color2}">
							<span class="button">{i18n('Size:')} {$file.size}</span>
						</div>
						<div class="mdp-info {$file.row_color2}">
							<span class="button">{i18n('Added:')} {$file.date}</span>
						</div>
					</div>
				{/section}
				{$page_nav}
			{php} closetable() {/php}
		{else}
			{php} opentable(__('Files')) {/php}
				<div class="info">{i18n('There are no files in this category.')}</div>
			{php} closetable() {/php}
		{/if}
	{else}
		{php} opentable(__('Master Download').' - '.__('Error')) {/php}
			<div class="error">{i18n('No access.')}</div>
			<div><a href="{$ADDR_SITE}master_download.html">{i18n('<< Back to download')}</a></div>
		{php} closetable() {/php}
	{/if}
{/if}

{if $md_file}
	{if $file}
		{php} opentable(__('File')) {/php}
			<div><a href="{$file.subcat_url}">{i18n('<< Back to category')}</a></div>
			<div class="mdp {$file.row_color}">
				{if $file.img}
					<a href="{$file.img}"><img src="{$file.img}" title="{$file.name}" class="tip"></a>
				{else}
					<img src="{$ADDR_MDP}templates/images/brak.gif" title="{$file.name}" class="tip">
				{/if}
				<div class="mdp-title {$file.row_color2} bold">{$file.name}</div>
				<div class="mdp-desc {$file.row_color2}">{$file.desc}</div>
				{if $access_file}
					<div class="mdp-info {$file.row_color2}">
						<a href="{$file.url}" target="_blank" class="button">{i18n('DOWNLOAD')}</a>
					</div>
				{else}
					<div class="mdp-info {$file.row_color2}">
						<a href="{$ADDR_SITE}login.html" class="button">{i18n('Log in')}</a>
					</div>
				{/if}
				<div class="mdp-info {$file.row_color2}">
					<span class="button">{i18n('Downloads:')} {$file.count}</span>
				</div>
				<div class="mdp-info {$file.row_color2}">
					<span class="button">{i18n('Size:')} {$file.size}</span>
				</div>
				<div class="mdp-info {$file.row_color2}">
					<span class="button">{i18n('Added:')} {$file.date}</span>
				</div>
			</div>
			
			<div class="mdp-mirror {$file.row_color}">
				<h4>{i18n('Mirrors')}</h4>
				{section=mirror}
					<a href="{$mirror}" target="_blank">{$mirror}</a><br />
				{/section}
			</div>
		{php} closetable() {/php}
	{else}
		{php} opentable(__('File')) {/php}
			<div class="info">{i18n('There are no files in this category.')}</div>
		{php} closetable() {/php}
	{/if}
{/if}