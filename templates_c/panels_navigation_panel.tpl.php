<?php openside(__('Navigation Panel')) ?>
<?php ; if($this->data['nav']){  ?>
	<?php  if(is_array($this -> data['nav']) && ($__nav_cnt = sizeof($this -> data['nav'])) > 0){    foreach($this -> data['nav'] as $__nav_id => &$__nav_val){  ?>
		<?php ; if($this->data['navigation']['name']==1){  ?>
			<div class="side-label"><strong><?php echo $__nav_val['name']; ?></strong></div>
		<?php  }elseif($this->data['navigation']['name']==2){  ?>
			<div><hr class="side-hr" /></div>
		<?php  }else{  ?>
			<div><img src="<?php echo $__nav_val['bullet']; ?>">&nbsp;<a href="<?php echo $__nav_val['url']; ?>" <?php echo $__nav_val['link_target']; ?>><?php echo $__nav_val['name']; ?></a></div>
		<?php  }  ?>
	<?php  } }  ?>
<?php  }else{  ?>
	<div class="error center">No site links</div>
<?php  }  ?>
<?php closeside() ?>