{if $Begin}

			<section class="side_body">
				<header>
					<h3>{$Collapse}{$Title}</h3>
				</header>
				<div class="content">
			{if $State}{$State}{/if}
{else}
			{if $Collapse}</div>{/if}
	</div>
			</section>

{/if}