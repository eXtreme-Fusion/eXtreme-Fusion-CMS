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
{else}
    {php} $this->middlePanel(__('Download')); {/php}
            <div class="info">{i18n('There are no files added.')}</div>
    {php} $this->middlePanel(); {/php}
    
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
{/if}