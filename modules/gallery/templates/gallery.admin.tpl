<h3>{$SystemVersion} - {i18n('Gallery')} || {i18n('Section')} - {i18n($page)}</h3>
{if $config.development}<div class="error">{i18n($config.developmentMessage)}</div>{/if}
{if $message}<div class="{$class}">{$message}</div>{/if}

<div class="tbl Buttons">
	<div class="center grid_2 button-l">
		{if $page === 'cats'}
			<span class="Cancels"><strong>{i18n('Categories')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=cats"><strong>{i18n('Categories')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-c">
		{if $page === 'albums'}
			<span class="Cancels"><strong>{i18n('Albums')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=albums"><strong>{i18n('Albums')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-c">
		{if $page === 'photos'}
			<span class="Cancels"><strong>{i18n('Images')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=photos"><strong>{i18n('Images')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-r">
		{if $page === 'sett'}
			<span class="Cancels"><strong>{i18n('Settings')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=sett"><strong>{i18n('Settings')}</strong></a></span>
		{/if}
	</div>
</div>
<hr />

{if $page === 'cats'}
	<script>
		{literal}
			$(document).ready(function() {
				
				function SlideoutGalleryCats(){
					setTimeout(function(){
						$('#ResponseGalleryCats').corner('8px').slideUp('slow', function () {
						});
					}, 2000);
				}

				$('#ResponseGalleryCats').hide();

				$(function() {
					$('#ListGalleryCats ul').sortable({ opacity: 0.6, cursor: 'move', update: function() {
						var order = $(this).sortable('serialize') + '&UpdateOrderGalleryCats=Ok';
						$.post('ajax/ajax.php', order, function(theResponse){
							$('#ResponseGalleryCats').html(theResponse);
							$('#ResponseGalleryCats').slideDown('slow').css('display','block');
							SlideoutGalleryCats();
						});
					}
					});
				});
			});
		{/literal}
	</script>

	<form id="This" action="{$URL_REQUEST}" class="tagform-full" method="post" enctype="multipart/form-data">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_2"><label for="title">{i18n('Name:')}</label></div>
			<div class="formField grid_6"><input type="text" name="title" value="{$title}" id="title" rows="1" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_2"><label for="description">{i18n('Description:')}</label></div>
			<div class="formField grid_6"><textarea name="description" id="description" rows="3" class="resize">{$description}</textarea></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_2"><label for="file">{i18n('Thumbnail:')}</label></div>
			<div class="formField grid_6"><input type="file" name="file" value="{$file}" id="file" rows="1" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_2"><label for="keyword">{i18n('Keywords:')}</label></div>
			<div class="formField grid_6">
				<p>
					{section=keyword}
						<input type="text" name="tag[]" id="keyword" value="{$keyword}" class="tag">
					{sectionelse}
						<input type="text" name="tag[]" id="keyword" value="" class="tag">
					{/section}
				</p>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_2"><label for="access">{i18n('Visible for:')}</label></div>
			<div class="formField grid_6">
				<select name="access[]" multiple id="access" class="select-multi" size="5">
					{section=access}
						<option value="{$access.value}"{if $access.selected} selected="selected"{/if}>{$access.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_2"><label for="order">{i18n('Order:')}</label></div>
			<div class="formField grid_1"><input type="text" name="order" value="{$order}" id="order" /></div>
		</div>
		<div class="tbl Buttons">
			<div class="sep_2 center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				{if $id}<input type="hidden" name="edit" value="{$id}" />{/if}
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
	<h4>{i18n('Existing categories')}</h4>
	{if $cats_list}
		<div id='ResponseGalleryCats' class='valid'></div>
		<div class="tbl2">
			<div class="sep_1 grid_2 bold">{i18n('Name:')}</div>
			<div class="grid_2 bold">{i18n('Description:')}</div>
			<div class="grid_2 bold">{i18n('Visible for:')}</div>
			<div class="grid_3 bold">{i18n('Date:')}</div>
			<div class="grid_2 bold">{i18n('Options:')}</div>
		</div>
		<div id='ListGalleryCats'>
			<ul>
				{section=cats_list}
					<li class='sort' id='ArrayOrderGalleryCats_{$cats_list.id}'>
						<div class="tbl2">
							<div class="sep_1 grid_2">
								{$cats_list.title}
							</div>
							<div class="grid_2">
								{$cats_list.description}
							</div>
							<div class="grid_2">
								{$cats_list.access}
							</div>	
							<div class="grid_3">
								{date("d.m.Y - H:m:s", $cats_list.datestamp)}
							</div>			
							<div class="grid_2">
								<a href="{$FILE_SELF}?page=cats&amp;action=edit&amp;id={$cats_list.id}" class="tip" title="{i18n('Edit')}">
									<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
								</a> 
								<a href="{$FILE_SELF}?page=cats&amp;action=delete&amp;id={$cats_list.id}" class="tip confirm_button" title="{i18n('Delete')}">
									<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
								</a>
							</div>
						</div>
					</li>
				{/section}
			</ul>
		</div>
	{else}
		<div class="tbl2">
			<div class="info">{i18n('There are no categories.')}</div>
		</div>
	{/if}
{/if}
{if $page === 'albums'}
	{if $cats}
		<script>
			{literal}
				$(document).ready(function() {
					
					function SlideoutGalleryAlbums(){
						setTimeout(function(){
							$('#ResponseGalleryAlbums').corner('8px').slideUp('slow', function () {
							});
						}, 2000);
					}

					$('#ResponseGalleryAlbums').hide();

					$(function() {
						$('#ListGalleryAlbums ul').sortable({ opacity: 0.6, cursor: 'move', update: function() {
							var order = $(this).sortable('serialize') + '&UpdateOrderGalleryAlbums=Ok';
							$.post('ajax/ajax.php', order, function(theResponse){
								$('#ResponseGalleryAlbums').html(theResponse);
								$('#ResponseGalleryAlbums').slideDown('slow').css('display','block');
								SlideoutGalleryAlbums();
							});
						}
						});
					});
				});
			{/literal}
		</script>

		<form id="This" action="{$URL_REQUEST}" class="tagform-full" method="post" enctype="multipart/form-data">
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="title">{i18n('Name:')}</label></div>
				<div class="formField grid_6"><input type="text" name="title" value="{$title}" id="title" rows="1" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="description">{i18n('Description:')}</label></div>
				<div class="formField grid_6"><textarea name="description" id="description" rows="3" class="resize">{$description}</textarea></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="file">{i18n('Thumbnail:')}</label></div>
				<div class="formField grid_6"><input type="file" name="file" value="{$file}" id="file" rows="1" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="keyword">{i18n('Keywords:')}</label></div>
				<div class="formField grid_6">
					<p>
						{section=keyword}
							<input type="text" name="tag[]" id="keyword" value="{$keyword}" class="tag">
						{sectionelse}
							<input type="text" name="tag[]" id="keyword" value="" class="tag">
						{/section}
					</p>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="cat">{i18n('Category:')}</label></div>
				<div class="formField grid_6">
					<select name="cat" id="cat">
						{section=cats}
							<option value="{$cats.id}"{if $cat == $cats.id} selected="selected"{/if}>{$cats.title}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="access">{i18n('Visible for:')}</label></div>
				<div class="formField grid_6">
					<select name="access[]" multiple id="access" class="select-multi" size="5">
					{section=access}
						<option value="{$access.value}"{if $access.selected} selected="selected"{/if}>{$access.display}</option>
					{/section}
				</select>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="order">{i18n('Order:')}</label></div>
				<div class="formField grid_1"><input type="text" name="order" value="{$order}" id="order" /></div>
			</div>
			<div class="tbl Buttons">
				<div class="sep_2 center grid_2 button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
				</div>
				<div class="center grid_2 button-r">
					<input type="hidden" name="save" value="yes" />
					{if $id}<input type="hidden" name="edit" value="{$id}" />{/if}
					<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
				</div>
			</div>
		</form>
		<h4>{i18n('Existing albums')}</h4>
		{if $albums_list}
			<div id='ResponseGalleryAlbums' class='valid'></div>
			<div class="tbl2">
				<div class="sep_1 grid_1 bold">{i18n('Name:')}</div>
				<div class="grid_1 bold">{i18n('Category:')}</div>
				<div class="grid_2 bold">{i18n('Description:')}</div>
				<div class="grid_2 bold">{i18n('Visible for:')}</div>
				<div class="grid_3 bold">{i18n('Date:')}</div>
				<div class="grid_1 bold">{i18n('Options:')}</div>
			</div>
			<div id='ListGalleryAlbums'>
				<ul>
					{section=albums_list}
						<li class='sort' id='ArrayOrderGalleryAlbums_{$albums_list.id}'>
							<div class="tbl2">
								<div class="sep_1 grid_1">
									{$albums_list.title}
								</div>
								<div class="grid_1">
									{$albums_list.cat_name}
								</div>
								<div class="grid_2">
									{$albums_list.description}
								</div>
								<div class="grid_2">
									{$albums_list.access}
								</div>	
								<div class="grid_3">
									{date("d.m.Y - H:m:s", $albums_list.datestamp)}
								</div>			
								<div class="grid_1">
									<a href="{$FILE_SELF}?page=albums&amp;action=edit&amp;id={$albums_list.id}" class="tip" title="{i18n('Edit')}">
										<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
									</a> 
									<a href="{$FILE_SELF}?page=albums&amp;action=delete&amp;id={$albums_list.id}" class="tip confirm_button" title="{i18n('Delete')}">
										<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
									</a>
								</div>
							</div>
						</li>
					{/section}
				</ul>
			</div>
		{else}
			<div class="tbl2">
				<div class="info">{i18n('There are no albums created.')}</div>
			</div>
		{/if}
	{else}
		<div class="tbl2">
			<div class="error">{i18n('There are no categories. Please add at least one category.')}</div>
		</div>
	{/if}
{/if}
{if $page === 'photos'}
	{if $albums}
		<script>
			{literal}
				$(document).ready(function() {
					
					function SlideoutGalleryPhotos(){
						setTimeout(function(){
							$('#ResponseGalleryPhotos').corner('8px').slideUp('slow', function () {
							});
						}, 2000);
					}

					$('#ResponseGalleryPhotos').hide();

					$(function() {
						$('#ListGalleryPhotos ul').sortable({ opacity: 0.6, cursor: 'move', update: function() {
							var order = $(this).sortable('serialize') + '&UpdateOrderGalleryPhotos=Ok';
							$.post('ajax/ajax.php', order, function(theResponse){
								$('#ResponseGalleryPhotos').html(theResponse);
								$('#ResponseGalleryPhotos').slideDown('slow').css('display','block');
								SlideoutGalleryPhotos();
							});
						}
						});
					});
				});
			{/literal}
		</script>

		<form id="This" action="{$URL_REQUEST}" method="post" class="tagform-full" enctype="multipart/form-data">
			<div class="{if $edit}tbl2{else}tbl1{/if}">
				<div class="formLabel sep_1 grid_2"><label for="title">{i18n('Name:')}</label></div>
				<div class="formField grid_6">
				<input type="text" name="title" value="{$title}" id="title" rows="1" />
				<small>{i18n('Nazwa pliku jest obowiązkowa!.')}</small>
				</div>
			</div>
			{if ! $edit}
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="file_name">{i18n('File name:')}</label></div>
				<div class="formField grid_6">
					<input type="text" name="file_name" value="{$file_name}" id="file_name" rows="1" />
					<small>{i18n('Leave this field empty if you want to keep original name or add your own file name.')}</small>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="file">{i18n('File:')}</label></div>
				<div class="formField grid_6"><input type="file" name="file" value="{$file}" id="file" rows="1" /></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="width">{i18n('Width:')}</label></div>
				<div class="formField grid_6"><input type="width" name="width" value="{$sett.photos_width}" id="width" rows="1" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="hight">{i18n('Height:')}</label></div>
				<div class="formField grid_6"><input type="hight" name="hight" value="{$sett.photos_hight}" id="hight" rows="1" /></div>
			</div>
			{/if}
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="watermark">{i18n('Watermark:')}</label></div>
				<div class="formField grid_6">
					<input type="checkbox" name="watermark" value="1" {if $watermark == 1}checked=checked{/if} id="watermark" />
					<small>{i18n('Add watermark to this file.')}</small>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="comment">{i18n('Comments:')}</label></div>
				<div class="formField grid_6">
					<input type="checkbox" name="comment" value="1" {if $comment == 1}checked=checked{/if} id="comment" />
					<small>{i18n('Allow comment.')}</small>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="rating">{i18n('Evaluations:')}</label></div>
				<div class="formField grid_6">
					<input type="checkbox" name="rating" value="1" {if $rating == 1}checked=checked{/if} id="rating" />
					<small>{i18n('Allow for evaluation.')}</small>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="description">{i18n('Description:')}</label></div>
				<div class="formField grid_6"><textarea name="description" id="description" rows="3" class="resize">{$description}</textarea></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="keyword">{i18n('Keywords:')}</label></div>
				<div class="formField grid_6">
					<p>
						{section=keyword}
							<input type="text" name="tag[]" id="keyword" value="{$keyword}" class="tag">
						{sectionelse}
							<input type="text" name="tag[]" id="keyword" value="" class="tag">
						{/section}
					</p>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="album">{i18n('Album:')}</label></div>
				<div class="formField grid_6">
					<select name="album" id="album">
						{foreach=$albums; cat}
							<optgroup label="{@cat.title}">
								{foreach=@cat.albums; album}
									<option value="{@album.id}"{if $album == @album.id} selected="selected"{/if}>{@album.title}</option>
								{/foreach}
							</optgroup>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="access">{i18n('Visible for:')}</label></div>
				<div class="formField grid_6">
					<select name="access[]" multiple id="access" class="select-multi" size="5">
					{section=access}
						<option value="{$access.value}"{if $access.selected} selected="selected"{/if}>{$access.display}</option>
					{/section}
				</select>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="order">{i18n('Order:')}</label></div>
				<div class="formField grid_1"><input type="text" name="order" value="{$order}" id="order" /></div>
			</div>
			<div class="tbl Buttons">
				<div class="sep_2 center grid_2 button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
				</div>
				<div class="center grid_2 button-r">
					<input type="hidden" name="save" value="yes" />
					{if $id}<input type="hidden" name="edit" value="{$id}" />{/if}
					<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
				</div>
			</div>
		</form>
		<h4>{i18n('Added images')}</h4>
		{if $photos_list}
			<div id='ResponseGalleryPhotos' class='valid'></div>
			<div class="tbl2">
				<div class="sep_1 grid_1 bold">{i18n('Name:')}</div>
				<div class="grid_1 bold">{i18n('Album:')}</div>
				<div class="grid_2 bold">{i18n('Description:')}</div>
				<div class="grid_2 bold">{i18n('Visible for:')}</div>
				<div class="grid_3 bold">{i18n('Date:')}</div>
				<div class="grid_2 bold">{i18n('Options:')}</div>
			</div>
			<div id='ListGalleryPhotos'>
				<ul>
					{section=photos_list}
						<li class='sort' id='ArrayOrderGalleryPhotos_{$photos_list.id}'>
							<div class="tbl2">
								<div class="sep_1 grid_1">
									{$photos_list.title}
								</div>
								<div class="grid_1">
									{$photos_list.album_name}
								</div>
								<div class="grid_1">
									{$photos_list.description}
								</div>
								<div class="grid_2">
									{$photos_list.access}
								</div>	
								<div class="grid_3">
									{date("d.m.Y - H:m:s", $photos_list.datestamp)}
								</div>			
								<div class="grid_1">
									<a href="{$FILE_SELF}?page=photos&amp;action=edit&amp;id={$photos_list.id}" class="tip" title="{i18n('Edit')}">
										<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
									</a> 
									<a href="{$FILE_SELF}?page=photos&amp;action=delete&amp;id={$photos_list.id}" class="tip confirm_button" title="{i18n('Delete')}">
										<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
									</a>
								</div>
							</div>
						</li>
					{/section}
				</ul>
			</div>
		{else}
			<div class="tbl2">
				<div class="info">{i18n('There are no images.')}</div>
			</div>
		{/if}
	{else}
		<div class="tbl2">
			<div class="error">{i18n('There are no albums. Add at least one album.')}</div>
		</div>
	{/if}
{/if}
{if $page === 'sett'}
	<form id="This" action="{$URL_REQUEST}" class="tagform-full" method="post">
		<h3>{i18n('Display settings')}</h3>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="animation_speed">{i18n('Animation speed:')}</label></div>
			<div class="formField grid_4">
				<select name="animation_speed" id="animation_speed">
					<option value="slow" {if $animation_speed == 'slow'}selected="selected"{/if}>{i18n('slow')}</option>
					<option value="normal" {if $animation_speed == 'normal'}selected="selected"{/if}>{i18n('normal')}</option>
					<option value="fast" {if $animation_speed == 'fast'}selected="selected"{/if}>{i18n('fast')}</option>
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="slideshow">{i18n('Slideshow:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="slideshow" name="slideshow" maxlength=10" class="num_10" value="{$slideshow}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="autoplay_slideshow">{i18n('Auto play slideshow:')}</label></div>
			<div class="formField grid_4">
				<select name="autoplay_slideshow" id="autoplay_slideshow">
					<option value="true" {if $autoplay_slideshow == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $autoplay_slideshow == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="opacity">{i18n('Opacity:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="opacity" name="opacity" maxlength=4" class="num_4" value="{$opacity}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="show_title">{i18n('Show title:')}</label></div>
			<div class="formField grid_4">
				<select name="show_title" id="show_title">
					<option value="true" {if $show_title == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $show_title == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="allow_resize">{i18n('Allow resize:')}</label></div>
			<div class="formField grid_4">
				<select name="allow_resize" id="allow_resize">
					<option value="true" {if $allow_resize == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $allow_resize == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="default_width">{i18n('Default width:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="default_width" name="default_width" maxlength=4" class="num_4" value="{$default_width}" />
			</div>
		</div>	
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="default_hight">{i18n('Default height:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="default_hight" name="default_hight" maxlength=4" class="num_4" value="{$default_hight}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="counter_separator_label">{i18n('Counter separator label:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="counter_separator_label" name="counter_separator_label" maxlength=1" class="num_1" value="{$counter_separator_label}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="theme">{i18n('Default theme:')}</label></div>
			<div class="formField grid_4">
				<select name="theme" id="theme">
					{section=theme}
						<option value="{$theme.value}" {if $theme.selected}selected="selected"{/if}>{$theme.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="horizontal_padding">{i18n('Horizontal padding:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="horizontal_padding" name="horizontal_padding" maxlength=3" class="num_3" value="{$horizontal_padding}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="hideflash">{i18n('Hide flash:')}</label></div>
			<div class="formField grid_4">
				<select name="hideflash" id="hideflash">
					<option value="true" {if $hideflash == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $hideflash == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>	
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="wmode">{i18n('Window mode options:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="wmode" name="wmode" maxlength=20" class="num_20" value="{$wmode}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="autoplay">{i18n('Auto play:')}</label></div>
			<div class="formField grid_4">
				<select name="autoplay" id="autoplay">
					<option value="true" {if $autoplay == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $autoplay == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="modal">{i18n('Modal:')}</label></div>
			<div class="formField grid_4">
				<select name="modal" id="modal">
					<option value="true" {if $modal == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $modal == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="deeplinking">{i18n('Deep linking:')}</label></div>
			<div class="formField grid_4">
				<select name="deeplinking" id="deeplinking">
					<option value="true" {if $deeplinking == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $deeplinking == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="deeplinking">{i18n('Overlay gallery:')}</label></div>
			<div class="formField grid_4">
				<select name="overlay_gallery" id="overlay_gallery">
					<option value="true" {if $overlay_gallery == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $overlay_gallery == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="keyboard_shortcuts">{i18n('Keyboard shortcuts:')}</label></div>
			<div class="formField grid_4">
				<select name="keyboard_shortcuts" id="keyboard_shortcuts">
					<option value="true" {if $keyboard_shortcuts == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $keyboard_shortcuts == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="social_tools">{i18n('Social tools:')}</label></div>
			<div class="formField grid_4">
				<textarea name="social_tools" id="social_tools" class="resize" rows="3">{$social_tools}</textarea>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="ie6_fallback">{i18n('IE6 fallback:')}</label></div>
			<div class="formField grid_4">
				<select name="ie6_fallback" id="ie6_fallback">
					<option value="true" {if $ie6_fallback == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $ie6_fallback == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		
		<h3>{i18n('Gallery settings')}</h3>
		
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="title">{i18n('Gallery title:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="title" name="title" maxlength="200" class="num_200" value="{$title}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="description">{i18n('Gallery description:')}</label></div>
			<div class="formField grid_4">
				<textarea id="description" name="description" rows="3" class="resize">{$description}</textarea>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="keyword">{i18n('Gallery keywords:')}</label></div>
			<div class="formField grid_4">
				<p>
					{section=keyword}
						<input type="text" name="tag[]" id="keyword" value="{$keyword}" class="tag">
					{sectionelse}
						<input type="text" name="tag[]" id="keyword" value="" class="tag">
					{/section}
				</p>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="allow_comment">{i18n('Comments:')}</label></div>
			<div class="formField grid_4">
				<select name="allow_comment" id="allow_comment">
					<option value="true" {if $allow_comment == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $allow_comment == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="allow_rating">{i18n('Evaluations:')}</label></div>
			<div class="formField grid_4">
				<select name="allow_rating" id="allow_rating">
					<option value="true" {if $allow_rating == 'true'}selected="selected"{/if}>{i18n('Yes')}</option>
					<option value="false" {if $allow_rating == 'false'}selected="selected"{/if}>{i18n('No')}</option>
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="thumb_compression">{i18n('Thumbnail compression:')}</label></div>
			<div class="formField grid_4">
				<select name="thumb_compression" id="thumb_compression">
					<option value="true" {if $thumb_compression == 'gd1'}selected="selected"{/if}>{i18n('GD1')}</option>
					<option value="false" {if $thumb_compression == 'gd2'}selected="selected"{/if}>{i18n('GD2')}</option>
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="thumbnail_width">{i18n('Thumbnail width:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="thumbnail_width" name="thumbnail_width" maxlength="4" class="num_4" value="{$thumbnail_width}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="thumbnail_hight">{i18n('Thumbnail height:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="thumbnail_hight" name="thumbnail_hight" maxlength="4" class="num_4" value="{$thumbnail_hight}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="photo_max_width">{i18n('Image max width:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="photo_max_width" name="photo_max_width" maxlength="5" class="num_5" value="{$photo_max_width}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="photo_max_hight">{i18n('Image max height:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="photo_max_hight" name="photo_max_hight" maxlength="5" class="num_5" value="{$photo_max_hight}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="watermark_logo">{i18n('Watermark image:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="watermark_logo" name="watermark_logo" maxlength="100" class="num_100" value="{$watermark_logo}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="cache_expire">{i18n('CACHE files storage:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="cache_expire" name="cache_expire" maxlength="100" class="num_100" value="{$cache_expire}" />
				<small>
					{i18n('Cache desc', array(':file' => $FILE_SELF))}
				</small>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="cats_per_page">{i18n('Kategorii na stronę:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="cats_per_page" name="cats_per_page" maxlength="3" class="num_3" value="{$cats_per_page}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="albums_per_page">{i18n('Albumów na stronę:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="albums_per_page" name="albums_per_page" maxlength="3" class="num_3" value="{$albums_per_page}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="photos_per_page">{i18n('Zdjęć na stronę:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="photos_per_page" name="photos_per_page" maxlength="3" class="num_3" value="{$photos_per_page}" />
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="allow_ext">{i18n('Allow extensions:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="allow_ext" name="allow_ext" maxlength="100" class="num_100" value="{$allow_ext}" />
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="max_file_size">{i18n('Maximum file size:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="max_file_size" name="max_file_size" maxlength="100" class="num_100" value="{$max_file_size}" />
				<small>
				{i18n('Max file desc', array(':kbsize' => $max_file_size_in_kb, ':uploadsize' => $upload_max_filesize, ':maxtime' => $max_execution_time))}
				</small>
			</div>
		</div>
		
		<div class="tb1 Buttons">
			<div class="sep_2 center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
{/if}

{i18n('Statistics')}
<div class="info">
	{i18n('Images:')} {$stats.photos} {i18n('Albums:')} {$stats.albums}	{i18n('Categories:')} {$stats.cats}
</div>
			
<script>
	{literal}
		$(document).ready(function() {
			$( ".tagform-full" ).find('input.tag').tagedit({
				autocompleteURL: 'ajax/ajax.php',
				texts: {
					removeLinkTitle: '{/literal}{i18n('Delete from list.')}{literal}',
					saveEditLinkTitle: '{/literal}{i18n('Save changes.')}{literal}',
					deleteLinkTitle: '{/literal}{i18n('Delete this entry from database.')}{literal}',
					deleteConfirmation: '{/literal}{i18n('Are you sure?')}{literal}',
					deletedElementTitle: '{/literal}{i18n('This element has been deleted.')}{literal}',
					breakEditLinkTitle: '{/literal}{i18n('Cancel')}{literal}'
				}
			});
		});
	{/literal}
</script>