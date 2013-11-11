{if $error}
    {php} $this->middlePanel(__('Download - Error')); {/php}
        {if $error === 'there_is_no_such_file'}
            <div class="error">{i18n('There is no such file')}</div>
        {/if}
        {if $error === 'no_file_was_found'}
            <div class="error">{i18n('No file was found')}</div>
        {/if}
        {if $error === 'could_not_access'}
            <div class="error">{i18n('Could not access')}</div>
        {/if}
    {php} $this->middlePanel(); {/php}
{elseif $result}
    {if $view}
        {php} $this->middlePanel(__('Szczegóły o pliku - :file', array(':file' => $this->data['view']['title']))); {/php}
        <div class='tbl-border' style='margin-bottom:10px; padding:3px;'>
            <div class='forum-caption' style='text-align:left;'>
                <a href='{$view.base_link}'></a>Download &gt; <a href='{$view.cat_link}'>{$view.cat_name}</a> &gt; <span class="bolder">{$view.title}</span>
            </div>
        </div>
            
        <table width='100%' cellpadding='0' cellspacing='1' class='tbl-border center'>
            <tr>
                <td class='tbl1' style='vertical-align:top;'>
                    {$view.description}
                </td>
                <td class='tbl1' style='width:20%;text-align:center;vertical-align:top;'>
                    <table width='100%' cellpadding='0' cellspacing='1' class='tbl-border center'>
			{if $view.homepage}
                        <tr>
                            <td class='tbl2' style='text-align:center;'>
                                <img src='{$ADDR_MODULES}/downloads/templates/images/dl_homepage.png' alt='Strona domowa' /><br />
                                <a href='{$view.homepage}' title={$view.homepage}'' target='_blank'>Strona domowa</a>
                            </td>
                        </tr>
                        {/if}
                        {if $view.screenshot_src}
			<tr>
                            <td class='tbl2' style='text-align:center;'>
                                <img src='{$ADDR_MODULES}/downloads/templates/images/dl_screenshot.png' alt='Zrzut ekranu' /><br />
                                <a class='tozoom' href='{$view.screenshot_src}'>Zrzut ekranu</a>
                            </td>
                        </tr>
                        {/if}
                        <tr>
                            <td class='tbl2' style='text-align:center;'>
                                <img src='{$ADDR_MODULES}/downloads/templates/images/dl_calendar.png' alt='Kalendarz' />
                                <br /><a href="{$view.author_link}" rel="author">{$view.author_name} </a>
                                <br />{$view.datestamp}
                            </td>
                        </tr>
                        <tr>
                            <td class='tbl2' style='text-align:center;'>
                                <img src='{$ADDR_MODULES}/downloads/templates/images/dl_downloads1.png' alt='Pobrań' /><br />{$view.count}
                            </td>
                        </tr>
                        {if $view.version !='' || $view.license !='' || $view.os !='' || $view.copyright !=''}
			<tr>
                            <td class='tbl2' style='text-align:center;'>
                                <img src='{$ADDR_MODULES}/downloads/templates/images/dl_info.png' alt='Informacje' /><br />
                                {if $view.version}Wersja: {$view.version}<br />{/if}
                                {if $view.license}Licencja: {$view.license}<br />{/if}
                                {if $view.os}O/S: {$view.os}<br />{/if}
                                {if $view.copyright}&copy; {$view.copyright} <br />{/if}
                            </td>
                        </tr>
                        {/if}
		</table>
                </td>
            </tr>
        <tr>
            <td class='tbl1' colspan='2' style='text-align:center;'><hr />
                <strong>Pobierz:</strong><br />
                <a href='{$view.download_link}' target='_blank'><img src='{$ADDR_MODULES}/downloads/templates/images/dl_download.png' alt='Download' /></a>
                <br />({$view.filesize})
                <hr />
            </td>
        </tr>
        </table>
        {php} $this->middlePanel(); {/php}

        {if $view.allow_comments}
            {$comments}
        {/if}
    {/if}
{else}           
    <form name='filter_form' method='get' action='{$URL_REQUEST}'>
        <table class='tbl' cellpadding='1' cellspacing='0' style='width:100%;'>
            <tr>
                <td class='tbl1' style='width:40%; text-align:left;'>Użyj filtrów po prawej, aby zawęzić zakres poszukiwań.</td>
                <td class='tbl1' style='width:60%; text-align:right;'>Szukaj
                    <select name='cat_id' class='textbox' onchange='this.form.submit();'>
                        <option value='all'>Wszystkie</option>
                        {section=category_list}
                            <option value="{$category_list.value}"{if $category_list.selected} selected="selected"{/if}>{$category_list.display}</option>
                        {/section}
                    </select>
                </td>
            </tr>
            <tr>
                <td class='tbl1' style='width:40%; text-align:left;'></td>
                <td class='tbl1' style='width:60%; text-align:right;'>
                Sortuj wg: <select name='orderby' class='textbox' onchange='this.form.submit();'>
                <option value='id'{if $order_by == 'id'} selected='selected'{/if}>ID</option>
                <option value='title'{if $order_by == 'title'} selected='selected'{/if}>Tytułu</option>
                <option value='user'{if $order_by == 'user'} selected='selected'{/if}>Autora</option>
                <option value='count'{if $order_by == 'count'} selected='selected'{/if}>Pobrań</option>
                <option value='datestamp'{if $order_by == 'datestamp'} selected='selected'{/if}>Daty</option>
                </select>
                <select name='sort' class='textbox' onchange='this.form.submit();'>
                <option value='ASC'{if $sort == 'ASC'} selected='selected'{/if}>Rosnąco</option>
                <option value='DESC'{if $sort == 'DESC'} selected='selected'{/if}>Malejąco</option>
                </select>
                </td>
            </tr>
            <tr>
                <td class='tbl1' style='width:40%; text-align:left;'></td>
                <td class='tbl1' style='width:60%; text-align:right;'>
                    <input id='filter_button' type='submit' class='button' value='Zastosuj' />
                </td>
            </tr>
        </table>
    </form>
    <hr />
    <div style='text-align:right;'>
        <form name='searchform' method='get' action='{$URL_REQUEST}'>
            <span class='small'>Przeszukaj download: </span>
            <input type='text' name='stext' class='textbox' style='width:150px' />
            <input type='submit' name='search' value='Szukaj' class='button' />
            <input type='hidden' name='stype' value='downloads' />
        </form>
    </div>
    <hr />

    {php} $this->middlePanel(__('Download')); {/php}
       <table class='tbl2'>
            {if $cat_list} 
                <thead>
                {section=cat_list}
                <tr class='tbl2'>
                    <td colspan='8' class='tbl1 bold left'>{$cat_list.name}</td>
                </tr>
                {if $cat_list.category_description}
                <tr>
                    <td colspan='8' class='quote tbl-border left'>{$cat_list.description}</td>
                </tr>
                {/if}
                <tr class='tbl1'>
                    <th class="grid_4 bold">Nazwa</th>
                    <th class="grid_2 bold">Data</th>
                    <th class="grid_2 bold">Autor</th>
                    <th class="grid_2 bold">Wersja</th>
                    <th class="grid_2 bold">Pobrań</th>
                    <th class="grid_2 bold">Komentarzy</th>
                </tr>
                </thead>
                <tbody>
                {if $cat_list.list}
                    {foreach=$cat_list.list; list}
                        <tr class='tbl2'>
                            <td>{if @list.new}[ NEW ]{/if}<a href='{@list.title_link}'>{@list.title}</a></td>
                            <td>{@list.datestamp}</td>
                            <td><a href='{@list.user_link}'>{@list.user_name}</a></td>
                            <td>{@list.version}</td>
                            <td>{@list.count}</td>
                            <td>{@list.comment_count}</td>
                        </tr>
                        <tr class='{@list.row}'>
                            <td colspan='8' class='tbl1 small'>
                            {if @list.screenshot}
                                    <img src='{@list.image_thumb}' style='float: left;margin:3px;' alt='{@list.title}' />
                            {/if}
                            {if @list.description_short}
                                    {@list.description_short}
                            {/if}
                            </td>
                        </tr>
                        {/foreach}
                {else}
                    <tr class='{$cat_list.row}'>
                        <td colspan='8' class='tbl2 center' style='text-align:center'>Brak udostępnionych plików do pobrania.</td>
                    </tr>
                {/if}
                </tbody>
                {/section}
            {/if}
            </table>
    {php} $this->middlePanel(); {/php}
{/if}

{php} $this->middlePanel(__('Statystyki')); {/php}
<table cellpadding='0' cellspacing='1' class='tbl-border' style='width:100%;'>
    <tr>
        <td class='tbl2' valign='middle'><img src='{$ADDR_MODULES}/downloads/templates/images/dl_stats.png' alt='Statistic' /></td>
        <td width='100%' align='left' class='tbl1'>
            <span class='small'>Łącznie plików: {$statistics.files}</span><br />
            <span class='small'>Łączna liczba pobrań: {$statistics.count}</span><br />
            {if $popular}
            <span class='small'>Najpopularniejszy plik:
                <a href='{$popular.link}' title='{$popular.title_long}' class='side'>{$popular.title_short}</a>
                [ {$popular.count} ]
            </span><br />
            {/if} 
            {if $latest}
            <span class='small'>Najnowszy plik:
                <a href='{$latest.link}' title='{$latest.title_long}' class='side'>{$latest.title_short}</a>
                [ {$latest.count} ]
            </span><br />
            {/if}
        </td>
    </tr>
</table>
{php} $this->middlePanel(); {/php}