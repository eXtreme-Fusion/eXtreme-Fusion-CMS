<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>#board-<?php echo $thread['board_id']; ?>"><?php echo $thread['board']; ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'category', $thread['category_id'])); ?>"><?php echo $thread['category']; ?></a></li>
	<li><strong><?php echo $thread['title']; ?></strong></li>
</ul>
<?php opentable(__('Forum')); ?>
		<table class="forum">
			<thead>
				<tr>
					<th class="col-2">Autor</th>
					<th class="col-10">Treść wpisu</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($entries as $entry): ?>
				<tr>
					<td>
						<div class="row">
							<div class="col col-5">
								<img src="<?php echo $this->user->getAvatarAddr($entry['user_id']); ?>" alt="<?php echo $entry['username']; ?>" class="avatar">
							</div>
							<ul class="entry-author col">
								<li class="text-title"><?php echo HELP::profileLink($entry['username'], $entry['user_id']); ?></li>
								<li><?php echo $this->user->getRoleName($entry['role']); ?></li>
								<li>Wpisów: <strong><?php echo $entry['entries']; ?></strong></li>
							</ul>
						</div>
					</td>
					<td class="align-top">
						<p class="entry-info">Utworzone: <strong><?php echo HELP::showDate('longdate', $entry['timestamp']); ?></strong></p>
						<?php echo $entry['content']; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
<?php closetable(); ?>