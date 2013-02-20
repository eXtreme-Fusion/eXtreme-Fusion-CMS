<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Market')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl">
		<div class="info">Most ten pozwala na połączenie strony postawionej na eXtreme-Fusion 5 z forum działającym pod systemem phpBB3.</div>
		<div class="status">Poniższy skrypt nie przeniesie aktualnych kont na forum, a jedynie pozwoli połączyć rejestracje systemu z forum.</div>
		<div class="info">Do prawidłowego działania potrzeba wpisać prefix bazy danych forum. Domyślnie ustawiony na `phpbb_`.</div>
	</div>
	<div class="tbl1">
		<div class="sep_1 grid_5 formLabel">{i18n('Włączyć most?')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="status" value="1"{if $status == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_4 formField"><label><input type="radio" name="status" value="0"{if $status == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_5"><label for="prefix">{i18n('Prefix bazy danych')}:<small>{i18n('Bez jego podania system będzie czytał domyślny prefix!')}</small></label></div>
		<div class="formField grid_5"><input type="text" id="prefix" name="prefix" class="num_100" value="{$prefix}" /></div>
	</div>
	
	<div class="tbl Buttons">
		<div class="grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>