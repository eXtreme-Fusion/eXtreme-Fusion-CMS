<?php $this->theme->middlePanel(__('Manage forum')); ?>
		<?php if ($boards = $board->fetchAll()): ?>
		<?php foreach ($boards as $board): ?>
		<table class="forum" id="board-<?php echo $board['id']; ?>">
			<thead>
				<tr>
					<th class="col-7 align-left"><?php echo $board['title']; ?></th>
					<th class="col-1 col-actions">
						<a href="#" title="<?php echo __('Edit board'); ?>"><?php echo __('Edit'); ?></a>
						<a href="#" title="<?php echo __('Remove board'); ?>"><?php echo __('Remove'); ?></a>
					</th>
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
					<td class="center">
						<div class="button-group">
							<a href="#" class="button" title="<?php echo __('Edit category'); ?>"><?php echo __('Edit'); ?></a>
							<a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'admin', 'action' => 'category', 'remove', $category['id'])); ?>" class="button" title="<?php echo __('Remove category'); ?>"><?php echo __('Remove'); ?></a>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="2"><p class="center error bold"><?php echo __('In this board has not been created any category'); ?>.</p></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php endforeach; ?>
		<?php else: ?>
		<p class="center error bold"><?php echo __('This forum does not have any boards and categories'); ?>.</p>
		<?php endif; ?>
<?php $this->theme->middlePanel(); ?>