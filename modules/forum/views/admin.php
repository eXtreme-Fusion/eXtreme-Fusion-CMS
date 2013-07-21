<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><strong><?php echo __('Manage forum'); ?></strong></li>
</ul>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<?php if ($boards = $board->fetchAll()): ?>
		<?php foreach ($boards as $board): ?>
		<form action="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'admin', 'controller' => 'category', 'action' => 'add', $board['id'])); ?>" method="post">
			<table class="forum" id="board-<?php echo $board['id']; ?>">
				<thead>
					<tr>
						<th class="col-7 align-left"><?php echo $board['title']; ?></th>
						<th class="col-1 col-actions">
							<!--a href="#" title="<?php echo __('Edit board'); ?>"><?php echo __('Edit'); ?></a>
							<a href="#" title="<?php echo __('Remove board'); ?>"><?php echo __('Remove'); ?></a-->
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="category-form"><input type="text" name="title" class="col-8" placeholder="<?php echo __('Category title'); ?>&hellip;"></td>
						<td class="center"><input type="submit" value="<?php echo __('Add category'); ?>" class="button"></td>
					</tr>
					<?php if ($categories = $category->fetchByID($board['id'])): ?>
					<?php foreach ($categories as $category): ?>
					<tr>
						<td>
							<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'category', $category['id'])); ?>" class="text-title"><?php echo $category['title']; ?></a>
							<?php if ($category['is_locked']): ?><span class="category-locked"><?php echo __('Locked'); ?></span><?php endif; ?>
							<?php if ($description = $category['description']): ?><p class="text-small"><?php echo $description; ?></p><?php endif; ?>
						</td>
						<td class="center">
							<div class="button-group">
								<!--a href="#" class="button" title="<?php echo __('Edit category'); ?>"><?php echo __('Edit'); ?></a-->
								<a href="<?php echo $this->router->path(array('module' => 'forum', 'directory' => 'admin', 'controller' => 'category', 'action' => 'remove', $category['id'])); ?>" class="button" title="<?php echo __('Remove category'); ?>"><?php echo __('Remove'); ?></a>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</form>
		<?php endforeach; ?>
		<?php else: ?>
		<p class="center error bold"><?php echo __('This forum does not have any boards and categories'); ?>.</p>
		<?php endif; ?>
<?php $this->theme->middlePanel(); ?>