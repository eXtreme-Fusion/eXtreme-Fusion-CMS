{section=tree}
	{if $tree.new}
		<ul>
	{elseif $tree.end}
		</ul>
	{/if}
	<li>{$tree.name}</li>
{/section}