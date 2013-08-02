<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li class="active"><?php echo __('Manage forum'); ?></li>
</ul>
<?php if ($boards = $board->fetchAll()): ?>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<?php foreach ($boards as $board): ?>
		<form action="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'acp', 'controller' => 'category', 'action' => 'add', $board['id'])); ?>" method="post" class="forum-form">
			<table class="forum" id="board-<?php echo $board['id']; ?>">
				<thead>
					<tr>
						<th class="col-10 align-left"><?php echo $board['title']; ?></th>
						<th class="col-1 col-actions">
							<a href="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'acp', 'controller' => 'board', 'action' => 'edit', $board['id'])); ?>" title="<?php echo __('Edit board'); ?>"><?php echo __('Edit'); ?></a>
							<a href="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'acp', 'controller' => 'board', 'action' => 'remove', $board['id'])); ?>" title="<?php echo __('Remove board'); ?>"><?php echo __('Remove'); ?></a>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="category-form"><input type="text" name="title" class="col-8" placeholder="<?php echo __('Category title'); ?>&hellip;"></td>
						<td class="center"><input type="submit" value="<?php echo __('Add category'); ?>" class="button"></td>
					</tr>
					<?php if ($categories = $category->fetchByID($board['id'])): ?>
					<?php foreach ($categories as $_category): ?>
					<tr>
						<td>
							<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'category', $_category['id'])); ?>" class="text-title"><?php echo $_category['title']; ?></a>
							<?php if ($_category['is_locked']): ?><span class="category-locked"><?php echo __('Locked'); ?></span><?php endif; ?>
							<?php if ($description = $_category['description']): ?><p class="text-small"><?php echo $description; ?></p><?php endif; ?>
						</td>
						<td class="center">
							<div class="button-group">
								<a href="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'acp', 'controller' => 'category', 'action' => 'edit', $_category['id'])); ?>" class="button" title="<?php echo __('Edit category'); ?>"><?php echo __('Edit'); ?></a>
								<a href="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'acp', 'controller' => 'category', 'action' => 'remove', $_category['id'])); ?>" class="button" title="<?php echo __('Remove category'); ?>"><?php echo __('Remove'); ?></a>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</form>
		<?php endforeach; ?>
<?php $this->theme->middlePanel(); ?>
<?php endif; ?>
<?php $this->theme->middlePanel(__('Add a new board')); ?>
		<form action="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'acp', 'controller' => 'board', 'action' => 'add')); ?>" method="post">
			<div class="tbl1">
				<div class="formLabel col col-2"><label for="title"><?php echo __('Board title'); ?>:</label></div>
				<div class="formField col col-8">
					<input type="text" name="title" id="title">
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel col col-2"><label for="order"><?php echo __('Board order'); ?>:</label></div>
				<div class="formField col col-1">
					<input type="text" name="order" id="order">
				</div>
			</div>
			<div class="tbl center">
				<input type="submit" value="<?php echo __('Add board'); ?>" class="button">
			</div>
		</form>
<?php $this->theme->middlePanel(); ?>