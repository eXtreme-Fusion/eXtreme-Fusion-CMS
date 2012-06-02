{if $nums}
        <div class="pag" id="newsPaging">
                {if $first}<a class="FirstLastPage" href="{$page},{$id},{$first}.html" rel="{$first}" title="{i18n('Idź do pierwszej podstrony')}">Pierwsza</a>{/if} 
                {if $prev}<a class="FirstLastPage1" href="{$page},{$id},{$prev}.html" rel="{$prev}" title="{i18n('Idź do poprzedniej podstrony')}">&laquo; Poprzednia</a>{/if}
                        {section=nums}
                                {if $nums == $current}<strong class="PageNum">{$nums}</strong>{else}<a class="PageNum" href="{$page},{$id},{$nums}.html" rel="{$nums}" title="{i18n('Idź do podstrony')}">{$nums}</a>{/if}
                        {/section}
                {if $next}<a class="FirstLastPage1" href="{$page},{$id},{$next}.html" rel="{$next}" title="{i18n('Idź do następnej podstrony')}">Następna &raquo;</a>{/if}
                {if $last}<a class="FirstLastPage" href="{$page},{$id},{$last}.html" rel="{$last}" title="{i18n('Idź do ostatniej podstrony')}">Ostatnia</a>{/if}
        </div>
{/if}