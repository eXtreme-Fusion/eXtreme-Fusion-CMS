<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>#board-<?php echo $thread['board_id']; ?>"><?php echo $thread['board']; ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'category', $thread['category_id'])); ?>"><?php echo $thread['category']; ?></a></li>
	<li><strong><?php echo $thread['title']; ?></strong></li>
</ul>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<nav class="forum-nav">
			<?php if ($logged_in = $this->user->iUSER()): ?>
			<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', 'action' => 'reply', $thread['id'])); ?>" class="button"><?php echo __('Add reply'); ?></a>
			<?php endif; ?>
			<div class="button-group">
				<?php if (($logged_in && $user->isAuthor()) || ($logged_in && $this->user->iADMIN())): ?>
				<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', 'action' => 'edit', $thread['id'])); ?>" class="button"><?php echo __('Edit thread'); ?></a>
				<?php endif; ?>
				<?php if ($logged_in && $this->user->iADMIN()): ?>
				<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', 'action' => 'remove', $thread['id'])); ?>" class="button"><?php echo __('Remove thread'); ?></a>
				<?php endif; ?>
			</div>
		</nav>
		<table class="forum">
			<thead>
				<tr>
					<th class="col-2"><?php echo __('Author'); ?></th>
					<th class="col-10"><?php echo __('Entry content'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($entries as $entry): ?>
				<tr id="entry-<?php echo $entry['id']; ?>">
					<td>
						<div class="row">
							<div class="col col-5">
								<img src="<?php echo $this->user->getAvatarAddr($entry['user_id']); ?>" alt="<?php echo $entry['username']; ?>" class="avatar">
							</div>
							<ul class="entry-author col">
								<li class="text-title"><?php echo HELP::profileLink($entry['username'], $entry['user_id']); ?></li>
								<li><?php echo $this->user->getRoleName($entry['role']); ?></li>
								<li><?php echo __('Entries'); ?>: <strong><?php echo $entry['entries']; ?></strong></li>
							</ul>
						</div>
					</td>
					<td class="align-top">
						<p class="entry-info"><?php echo __('Created on'); ?>: <strong><?php echo HELP::showDate('longdate', $entry['timestamp']); ?></strong></p>
						<?php echo $this->bbcode->parseAllTags($entry['content']); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
<?php $this->theme->middlePanel(); ?>
<?php if ($this->user->iUSER()): $_user = $this->user; ?>
<?php $this->theme->middlePanel(__('Quick reply')); ?>
		<form action="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'thread', 'action' => 'reply', $thread['id'])); ?>" method="post">
			<table class="forum">
				<thead>
					<tr>
						<th class="col-2"><?php echo __('Author'); ?></th>
						<th class="col-10"><?php echo __('Reply content'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="align-top">
							<div class="row">
								<div class="col col-5">
									<img src="<?php echo $_user->getAvatarAddr(); ?>" alt="<?php echo $_user->get('username'); ?>" class="avatar">
								</div>
								<ul class="entry-author col">
									<li class="text-title"><?php echo HELP::profileLink($_user->get('username'), $_user->get('id')); ?></li>
									<li><?php echo $_user->getRoleName($_user->get('role')); ?></li>
									<li><?php echo __('Entries'); ?>: <strong><?php print_r($user->getCount()); ?></strong></li>
								</ul>
							</div>
						</td>
						<td class="align-top">
							<textarea name="content" rows="4"></textarea>
							<input type="submit" value="<?php echo __('Add reply'); ?>" class="button">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
<?php $this->theme->middlePanel(); ?>
<?php endif; ?>