<?php ; if($this->data['Begin']){  ?>
	<div class='side-body-bg'>
		<div class='scapmain'>
			<h4><?php echo $this->data['Collapse'];  echo $this->data['Title']; ?></h4>
		</div>
		<div class='side-body2 floatfix'>
			<?php ; if($this->data['State']){   echo $this->data['State'];   }  ?>
<?php  }else{  ?>
			<?php ; if($this->data['Collapse']){  ?></div><?php  }  ?>
		</div>
	</div>
<?php  }  ?>

<!--<?php echo $this->data['panel_id']; ?>-->