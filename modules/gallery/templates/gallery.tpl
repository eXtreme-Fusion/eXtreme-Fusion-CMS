<div class="tbl2 text-padding-h3">
	<span class="bold">Jesteś tutaj: </span>
	<a href="{$ADDR_SITE}{$Breadcrumb.index.id}">{$Breadcrumb.index.title}</a>
	{if $Breadcrumb.cat.title && $Breadcrumb.cat.id}
	 &raquo; <a href="{$Breadcrumb.cat.link}">{$Breadcrumb.cat.title}</a>
	{/if}
	{if $Breadcrumb.album.title && $Breadcrumb.album.id}
	 &raquo; <a href="{$Breadcrumb.album.link}">{$Breadcrumb.album.title}</a>
	{/if}
	{if $Breadcrumb.photo.title && $Breadcrumb.photo.id}
	 &raquo; <a href="{$Breadcrumb.photo.link}">{$Breadcrumb.photo.title}</a>
	{/if}
</div>
{if $page === 'cat'}
	{php} opentable(__('Albums list')) {/php}
	{if $album}
			<div class="floatfix container_10 center">
			{section=album}
				<a href="{$album.album_link}" title="
					Tytuł: {$album.title}<br />
					Opis: {$album.description}<br />
					Zdjęć: {$album.photos}<br />
					Komentarzy: {$album.comments}<br />
					Dostęp: {$album.role_name}<br />
					{i18n('Created:')} {date("d.m.Y", $album.datestamp)}"
				class="tip {$album.color}" style="float:left; height:20%; width:20%; display:block; padding:5px; margin:5px; text-decoration:none;"><img src="{$ADDR_SITE}modules/gallery/templates/images/upload/albums/thumbnail/_thumbnail_{$album.file_name}" alt="{$album.title}" /></a>
			
			{/section}
			</div>	
			{$page_nav}
		
	{else}
		<div class="tbl2">
			<div class="info">{i18n('There are no albums.')}</div>
		</div>
	{/if}
	{php} closetable() {/php}
{elseif $page === 'album'}
	{php} opentable(__('Images list')) {/php}
	{if $photo}
			<div class="floatfix container_10 center">
			{section=photo}
				<div style="float:left; height:20%; width:20%; padding:5px; margin:5px; text-decoration:none;">
					<a href="{$photo.path_url}{if $photo.watermark}watermark{else}original{/if}/{$photo.file_name}" rel="prettyPhoto[gallery-{$photo.album_id}]"  title="{$photo.title}<br />{$photo.description}" class="tip" style="float:center;"><img src="{$photo.path_url}{if $photo.watermark}watermark{else}original{/if}/{$photo.file_name}" alt="{$photo.title}" width="100" height="100"/></a>
					<p>
						<a href="{$photo.photo_link}" title="Skomentuj zdjęcie: <strong>{$photo.title}</strong>" class="tip" style="float:center;">
							<strong>{$photo.title}</strong>
						</a>
						<p style="margin:0px;padding:0px;">{$photo.description}</p>
						<span>Komentarzy: {$photo.comments}</span><br />
						<span>Autor: {$photo.user}</span><br />
						<span>Dostęp: {$photo.role_name}</span><br />
						<em style="font-style:normal;">{i18n('Created:')} {date("d.m.Y", $photo.datestamp)}</em>
					</p>
				</div>
			{/section}
			</div>	
			{$page_nav}
	{else}
		<div class="tbl2">
			<div class="info">{i18n('There are no images.')}</div>
		</div>
	{/if}
	{php} closetable() {/php}
{elseif $page === 'photo'}
	{if $photo}
		{php} opentable(__('Image: :photoTitle', array(':photoTitle' => $this->data['photo']['name']))) {/php}
			<div class="main-body floatfix container_10 center">
				<div class="tbl1 center">
					<div class="sep_1 center">Tytuł: {$photo.title}</div>
				</div>
				<div class="tbl2 center">
					<a href="{$photo.path_url}{if $photo.watermark}watermark{else}original{/if}/{$photo.file_name}" rel="prettyPhoto" title="Powiększ" class="tip"><img src="{$photo.path_url}{if $photo.watermark}watermark{else}original{/if}/{$photo.file_name}" alt="{$photo.title}" width="50%" height="50%"/></a>
				</div>
				<div class="tbl1 center">
					<div class="left">Opis: {$photo.description}</div>
				</div>
				<div class="tbl2 center">
					<div class="center">Autor: {$photo.user}</div>
				</div>
				<div class="tbl1 center">
					<div class="center">Data dodania: {date("d.m.Y", $photo.datestamp)}</div>
				</div>
				<div class="tbl2 center">
					<div class="center">Dostęp: {$photo.role_name}</div>
				</div>
			</div>
		{php} closetable() {/php}
		{$comment}
	{else}
		{php} opentable('Obraz nie istenieje') {/php}
		<div class="tbl2">
			<div class="info">{i18n('The image was not found.')}</div>
		</div>
		{php} closetable() {/php}
	{/if}
{else}
	{php} opentable(__('Categories list')) {/php}
	{if $cat}
			<div class="floatfix container_10 center">
			{section=cat}
				<a href="{$cat.cat_link}" title="
					Tytuł: {$cat.title}<br />
					Opis: {$cat.description}<br />
					Albumów: {$cat.albums}<br />
					Zdjęć: {$cat.photos}<br />
					Komentarzy: {$cat.comments}<br />
					Dostęp: {$cat.role_name}<br />
					{i18n('Created:')} {date("d.m.Y", $cat.datestamp)}"
				class="tip {$cat.color}" style="float:left; height:20%; width:20%; display:block; padding:5px; margin:5px; text-decoration:none;"><img src="{$ADDR_SITE}modules/gallery/templates/images/upload/cats/thumbnail/_thumbnail_{$cat.file_name}" alt="{$cat.title}" /></a>
			{/section}
			</div>	
			{$page_nav}
	{else}
		<div class="tbl2">
			<div class="info">{i18n('There are no categories.')}</div>
		</div>
	{/if}
	{php} closetable() {/php}
{/if}
