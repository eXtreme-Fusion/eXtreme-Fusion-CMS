<ul class="breadcrumbs">
	<li><a href="<?php echo $this->router->path(array('module' => 'forum')); ?>"><?php echo __('Forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'acp')); ?>"><?php echo __('Manage forum'); ?></a></li>
	<li><a href="<?php echo $this->router->path(array('module' => 'forum', 'controller' => 'acp')); ?>#board-<?php echo $board_id; ?>"><?php echo $board; ?></a></li>
	<li class="active"><?php echo isset($category) ? $category['title'] : __('Add category'); ?></li>
</ul>
<?php $this->theme->middlePanel(__('Forum')); ?>
		<form action="<?php echo URL_REQUEST; ?>" method="post">
			<div class="tbl">
				<div class="formLabel col col-2"><label for="type"><?php echo __('Category type'); ?>:</label></div>
				<div class="formField col col-8">
					<label onClick="document.getElementById('normal').style.display='block'; document.getElementById('url').disabled='disabled'; document.getElementById('link').style.display='none'; document.getElementById('is_locked').disabled=false"><input type="radio" name="type"<?php echo isset($category) && $category['url'] == NULL ? ' checked="checked"' : '' ?>> <?php echo __('Normal'); ?></label>

					<label onClick="document.getElementById('normal').style.display='none'; document.getElementById('url').disabled=false; document.getElementById('link').style.display='block'; document.getElementById('is_locked').disabled='disabled'"><input type="radio" name="type"<?php echo isset($category) && $category['url'] != NULL ? ' checked="checked"' : '' ?>> <?php echo __('URL'); ?></label>
				</div>
			</div>
			<div id="normal" style="<?php echo isset($category) && $category['url'] == NULL ? ' display: block;' : ' display: none;' ?>">
				<div class="tbl1">
					<div class="formLabel col col-2"><label for="title"><?php echo __('Category title'); ?>:</label></div>
					<div class="formField col col-8">
						<input type="text" name="title" value="<?php echo isset($category) ? $category['title'] : $title; ?>" id="title">
					</div>
				</div>
				<div class="tbl2">
					<div class="formLabel col col-2"><label for="description"><?php echo __('Category description'); ?>:</label></div>
					<div class="formField col col-8">
						<textarea name="description" rows="3" id="description"><?php echo isset($category) ? $category['description'] : NULL; ?></textarea>
					</div>
				</div>
				<div class="tbl1">
					<div class="formLabel col col-2"><label for="order"><?php echo __('Category order'); ?>: (<?php echo __('optional'); ?>)</label></div>
					<div class="formField col col-1">
						<input type="text" name="order" value="<?php echo isset($category) ? $category['order'] : NULL; ?>" id="order">
					</div>
				</div>
				<div class="tbl2">
					<div class="line center">
						<label><input type="checkbox" name="is_locked"<?php if (isset($category) && $category['is_locked']): ?> checked<?php endif; ?>> <?php echo __('Lock this category'); ?></label>
					</div>
				</div>
			</div>
			<div id="link" style="<?php echo isset($category) && $category['url'] != NULL ? ' display: block;' : ' display: none;' ?>">
				<div class="tbl1">
					<div class="formLabel col col-2"><label for="title"><?php echo __('Category title'); ?>:</label></div>
					<div class="formField col col-8">
						<input type="text" name="title" value="<?php echo isset($category) ? $category['title'] : $title; ?>" id="title">
					</div>
				</div>
				<div class="tbl2">
					<div class="formLabel col col-2"><label for="url"><?php echo __('Category URL'); ?>:</label></div>
					<div class="formField col col-8">
						<input type="text" name="url" value="<?php echo isset($category) ? $category['url'] : NULL; ?>" id="url" placeholder="http://">
					</div>
				</div>
				<div class="tbl1">
					<div class="formLabel col col-2"><label for="description"><?php echo __('Category description'); ?>:</label></div>
					<div class="formField col col-8">
						<textarea name="description" rows="3" id="description"><?php echo isset($category) ? $category['description'] : NULL; ?></textarea>
					</div>
				</div>
				<div class="tbl2">
					<div class="formLabel col col-2"><label for="order"><?php echo __('Category order'); ?>:</label> (<?php echo __('optional'); ?>)</div>
					<div class="formField col col-1">
						<input type="text" name="order" value="<?php echo isset($category) ? $category['order'] : NULL; ?>" id="order">
					</div>
				</div>
				
			</div>
			<div class="tbl center">
				<input type="submit" name="submit" value="<?php echo __(isset($category) ? 'Save changes' : 'Add category'); ?>" class="button">
			</div>
		</form>
<?php $this->theme->middlePanel(); ?>