<h3>{$SystemVersion} - {i18n('Forum')}</h3>
{if $config.development}<div class="error">{i18n($config.developmentMessage)}</div>{/if}
{if $message}<div class="{$class}">{$message}</div>{/if}

