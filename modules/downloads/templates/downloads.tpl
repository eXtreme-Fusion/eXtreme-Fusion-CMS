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
{/if}