<?php $this->theme->middlePanel(__('Forum')); ?>
		<?php if ($boards = $board->fetchAll()): ?>
		<?php foreach ($boards as $board): ?>
		<table class="forum" id="board-<?php echo $board['id']; ?>">
			<thead>
				<tr>
					<th class="col-8 align-left"><?php echo $board['title']; ?></th>
					<th class="col-1"><?php echo __('Threads'); ?></th>
					<th class="col-1"><?php echo __('Entries'); ?></th>
					<th class="col-3"><?php echo __('Last entry'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($categories = $category->fetchByID($board['id'])): ?>
				<?php foreach ($categories as $category): ?>
				<tr>
					<td>
						<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'category', $category['id'])); ?>" class="text-title"><?php echo $category['title']; ?></a>
						<p class="text-small"><?php echo $category['description']; ?></p>
					</td>
					<td class="align-center"><?php echo $category['threads']; ?></td>
					<td class="align-center"><?php echo $category['entries']; ?></td>
					<td class="align-center">
						<?php if (isset($category['user'])): ?>
						<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', $category['thread'])); ?>#entry-<?php echo $category['entry']; ?>" class="text-link"><?php echo HELP::showDate('shortdate', $category['timestamp']); ?></a>
						<?php echo HELP::profileLink(NULL, $category['user']); ?>
						<?php else: ?>
						<?php echo __('None'); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="4"><p class="center error bold"><?php echo __('In this board has not been created any category'); ?>.</p></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php endforeach; ?>
		<?php else: ?>
		<p class="center error bold"><?php echo __('This forum does not have any boards and categories'); ?>.</p>
		<?php endif; ?>
<?php $this->theme->middlePanel(); ?>
<?php if ($this->is_admin): ?>
<nav class="forum-nav">
	<a href="<?php echo ADDR_ADMIN; ?>" class="button"><?php echo __('Admin Control Panel'); ?></a>
	<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'admin')); ?>" class="button"><?php echo __('Manage forum'); ?></a>
</nav>
<?php endif; ?>