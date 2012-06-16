<?php ; if($this->data['action']){  ?>
	<?php ; if($this->data['rows']){  ?>
	<?php opentable(__('News preview')) ?>
		<div class="news-content">
			<div class="news">
				<h4><?php echo $this->data['news']['title_name']; ?> <span class="right small"><?php echo optLocale($this,'Date:'); ?> <?php echo $this->data['news']['date']; ?></span></h4>
				<?php ; if($this->data['access_edit']){  ?>
					<div class="clear"></div>
					<div class="right">
						<a href="javascript:void(0);" class="tip admin-box" rel="<?php echo $this->data['ADDR_ADMIN']; ?>pages/news.php?page=news&action=edit&id=<?php echo $this->data['news']['title_id']; ?>&fromPage=true" title="<?php echo optLocale($this,'Edit'); ?>">
						<img src="<?php echo $this->data['ADDR_ADMIN_ICONS']; ?>edit.png" alt="<?php echo optLocale($this,'Edit'); ?>" />
					</div>
				<?php  }  ?>
				<div class="left small">
					<div><?php ; if($this->data['news']['category_id']){   echo optLocale($this,'Category:'); ?> <a href="<?php echo $this->data['news']['category_link']; ?>"><?php echo $this->data['news']['category_name']; ?></a>,<?php  }  ?> <?php echo optLocale($this,'Author:'); ?> <a href="<?php echo $this->data['news']['author_link']; ?>"><?php echo $this->data['news']['author_name']; ?></a></div>
					<div>
						<?php ; if($this->data['news']['source']){  ?>
							<a href="<?php echo $this->data['news']['source']; ?>" target="_blank"><?php echo optLocale($this,'Source'); ?></a>,
						<?php  }  ?>
						<?php ; if($this->data['news']['keyword']){  ?>
							<?php echo optLocale($this,'Tags:'); ?>
							<?php  if(sizeof($this->data['news']['keyword']) > 0){  foreach($this->data['news']['keyword'] as $__f_0_val){ $this -> vars['value'] = &$__f_0_val;  ?>
								<a href="<?php echo $this->vars['value']['tag_url']; ?>"><?php echo $this->vars['value']['keyword_name']; ?></a>,
							<?php  } }  ?>
						<?php  }  ?>
					</div>
				</div>
				<div class="right small"><?php echo optLocale($this,'Reads:'); ?> <?php echo $this->data['news']['reads']; ?>, <?php echo optLocale($this,'Comments:'); ?> <?php echo $this->data['news']['num_comments']; ?></div>
			</div>
			<div class="Content">
				<?php ; if($this->data['news']['content']){  ?>
					<a name='content'></a>
					<?php echo $this->data['news']['content']; ?>
				<?php  }  ?>
				<?php ; if($this->data['news']['content_ext']){  ?>
					<p>
					<a name='content_extended'></a>
					<?php echo $this->data['news']['content_ext']; ?>
					</p>
				<?php  }  ?>
			</div>
			<div class="ContentFooter">
				<hr /><!--
				
					<div class="addthis_toolbox addthis_default_style ">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
					<a class="addthis_counter addthis_pill_style"></a>
					</div>
					<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e6dcb8a3de24922"></script>
				-->
			</div>
		</div>
		
	<?php closetable() ?>
	<a name='comments'></a>
	<?php echo $this->data['comments']; ?>
	<?php  }else{  ?>
	<?php opentable(__('Error')) ?>
		<div class="status"><?php echo optLocale($this,'No data!'); ?></div>
	<?php closetable() ?>
	<?php  }  ?>
<?php  }else{  ?>
	<?php opentable(__('News')); ?>
		<?php ; if($this->data['news']){  ?>
			<?php  if(is_array($this -> data['news']) && ($__news_cnt = sizeof($this -> data['news'])) > 0){    foreach($this -> data['news'] as $__news_id => &$__news_val){  ?>
				<div class="news-content">
					<div class="news">
						<h4><a href="<?php echo $__news_val['url']; ?>"><?php echo $__news_val['title_name']; ?></a> <span class="right small"><?php echo optLocale($this,'Date:'); ?> <?php echo $__news_val['date']; ?></span></h4>
						<div class="left small">
							<div><?php ; if($__news_val['category_id']){   echo optLocale($this,'Category:'); ?> <a href="<?php echo $__news_val['category_link']; ?>"><?php echo $__news_val['category_name']; ?></a>, <?php  }   echo optLocale($this,'Author:'); ?> <a href="<?php echo $__news_val['author_link']; ?>"><?php echo $__news_val['author_name']; ?></a> <?php echo optLocale($this,'Language:'); ?> <?php echo $__news_val['language']; ?></div>
							<div>
								<?php ; if($__news_val['source']){  ?>
									<a href="<?php echo $__news_val['source']; ?>" target="_blank"><?php echo optLocale($this,'Source'); ?></a>,
								<?php  }  ?>
								<?php ; if($__news_val['keyword']){  ?>
									<?php echo optLocale($this,'Tags:'); ?>
									<?php  if(sizeof($__news_val['keyword']) > 0){  foreach($__news_val['keyword'] as $__f_0_val){ $this -> vars['value'] = &$__f_0_val;  ?>
										<a href="<?php echo $this->vars['value']['tag_url']; ?>"><?php echo $this->vars['value']['keyword_name']; ?></a>,
									<?php  } }  ?>
								<?php  }  ?>
							</div>
						</div>
						<div class="right small"><?php echo optLocale($this,'Reads:'); ?> <?php echo $__news_val['reads']; ?>, <?php ; if($__news_val['allow_comments']){  ?> <a href="<?php echo $__news_val['url']; ?>#comments"><?php echo optLocale($this,'Comments:'); ?> <?php echo $__news_val['num_comments']; ?></a><?php  }  ?></div>
					</div>
						<?php echo $__news_val['content']; ?>
					<hr />
					<?php ; if($__news_val['content_ext']){  ?>
						<div class="right">
							<a href="<?php echo $__news_val['url'];  ; if($__news_val['content_ext']){  ?>#content_extended<?php  }else{  ?>#content<?php  }  ?>" class="tip" title="<?php echo optLocale($this,'Read more...'); ?>"><?php echo optLocale($this,'Read more...'); ?></a>
						</div>
					<?php  }  ?>
				</div>
			<?php  } }  ?>	
			<?php echo $this->data['page_nav']; ?>
		<?php  }else{  ?>
			<div class="status"><?php echo optLocale($this,'No News has been posted yet'); ?></div>
		<?php  }  ?>
	<?php closetable(); ?>
<?php  }  ?>