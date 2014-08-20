<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>#board-<?php echo $category['board_id']; ?>"><?php echo $category['board']; ?></a></li>
	<li class="active"><?php echo $category['title']; ?></li>
</ul>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<table class="forum">
			<thead>
				<tr>
					<td colspan="4"><p class="center info bold"><?php echo __('In a moment, you will be redirected to the webpage :adress', array(':adress' => $category['url'])); ?></p>
					<p class="center"><a href="<?php echo $category['url']; ?>" class="button">Jeśli nie chcesz czekać, kliknij...</a></p></td>
				</tr>
			</thead>
		</table>
<?php $this->theme->middlePanel(); ?>