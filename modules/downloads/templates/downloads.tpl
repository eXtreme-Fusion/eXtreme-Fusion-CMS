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
    {php} $this->middlePanel(__('Download')); {/php}
            <div class="info">{i18n('There are no files added.')}</div>
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