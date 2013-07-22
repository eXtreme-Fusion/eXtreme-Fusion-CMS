<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'acp')); ?>"><?php echo __('Manage forum'); ?></a></li>
	<li class="active"><?php echo $board['title']; ?></li>
</ul>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<form action="<?php echo URL_REQUEST; ?>" method="post">
			<div class="tbl1">
				<div class="formLabel col col-2"><label for="title"><?php echo __('Category title'); ?>:</label></div>
				<div class="formField col col-8">
					<input type="text" name="title" value="<?php echo $board['title']; ?>" id="title">
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel col col-2"><label for="order"><?php echo __('Category order'); ?>:</label></div>
				<div class="formField col col-1">
					<input type="text" name="order" value="<?php echo $board['order']; ?>" id="order">
				</div>
			</div>
			<div class="tbl center">
				<input type="submit" value="<?php echo __('Save changes'); ?>" class="button">
			</div>
		</form>
<?php $this->theme->middlePanel(); ?>