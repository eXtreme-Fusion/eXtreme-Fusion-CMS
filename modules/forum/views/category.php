<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>#board-<?php echo $category['board_id']; ?>"><?php echo $category['board']; ?></a></li>
	<li class="active"><?php echo $category['title']; ?></li>
</ul>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<?php if (($this->logged_in && ! $category['is_locked']) || $this->is_admin): ?>
		<nav class="forum-nav">
			<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', 'action' => 'create', $category['id'])); ?>" class="button"><?php echo __('Create a new thread'); ?></a>
		</nav>
		<?php endif; ?>
		<table class="forum">
			<thead>
				<tr>
					<th class="col-7 align-left"><?php echo __('Thread title'); ?></th>
					<th class="col-2"><?php echo __('Author'); ?></th>
					<th class="col-1"><?php echo __('Replies'); ?></th>
					<th class="col-2"><?php echo __('Last entry'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($threads): ?>
				<?php foreach ($threads as $thread): ?>
				<tr>
					<td>
						<?php if ($thread['is_pinned']): ?><em class="pinned">(<?php echo __('Pinned'); ?>)</em><?php endif; ?>
						<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', $thread['id'])); ?>" class="text-title"><?php echo $thread['title']; ?></a>
					</td>
					<td class="align-center"><?php echo HELP::profileLink($thread['username'], $thread['user_id']); ?></td>
					<td class="align-center"><?php echo $thread['entries']; ?></td>
					<td class="align-center">
						<?php if (isset($thread['entry_user'])): ?>
						<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', $thread['id'])); ?>#entry-<?php echo $thread['entry_id']; ?>" class="text-link"><?php echo HELP::showDate('shortdate', $thread['entry_timestamp']); ?></a>
						<?php echo HELP::profileLink(NULL, $thread['entry_user']); ?>
						<?php else: ?>
						<?php echo __('None'); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="4"><p class="center error bold"><?php echo __('In this category has not been created any thread'); ?>.</p></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php if ((($this->logged_in && ! $category['is_locked']) || $this->is_admin) && $threads): ?>
		<nav class="forum-nav">
			<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', 'action' => 'create', $category['id'])); ?>" class="button"><?php echo __('Create a new thread'); ?></a>
		</nav>
		<?php endif; ?>
<?php $this->theme->middlePanel(); ?>