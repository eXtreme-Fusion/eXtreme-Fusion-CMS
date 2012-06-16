<div id="MainWrap" class="box-center">
	<div class="floatfix">
		<div class="full-header floatfix">
			<a href="<?php echo $this->data['ADDR_SITE']; ?>" id="HomeLink" class="tip" title="<?php echo $this->data['Sitename']; ?>">
				<img src="<?php echo $this->data['THEME_IMAGES']; ?>logo.png" alt="<?php echo $this->data['Sitename']; ?>" />
			</a>
			<div id="RightHeaderBG">
				<a href="http://www.extreme-fusion.org/downloads.php?action=get&amp;id=530" rel="blank" class="tip" title="Download eXtreme-Fusion 4.17">
					Download eXtreme-Fusion 4.17
				</a>
			</div>
		</div>
		<div class="sub-header floatfix">
			<div class="left"><?php echo $this->data['Menu']; ?></div>
			<div id="fancyClock"></div>
		</div>
	</div>
	<?php ; if($this->data['LEFT']){  ?><div id="side-border-left"><?php echo $this->data['LEFT']; ?></div><?php  }  ?>
	<?php ; if($this->data['RIGHT']){  ?><div id="side-border-right"><?php echo $this->data['RIGHT']; ?></div><?php  }  ?>
	<div id="main-bg" class="clearfix">
		<div id="container"><?php echo $this->data['CONTENT']; ?></div>
	</div>
	<div class="bottom floatfix"><?php echo $this->data['primiarymenu']; ?></div>
	<div class="footer floatfix">
		<div class="center">
			<?php ; if($this->data['Copyright']){  ?>
				<p><?php echo $this->data['Copyright']; ?></p>
				<p><?php echo $this->data['License']; ?></p>
			<?php  }  ?>
			<p>
				Theme by <a href="http://nlds-group.com/" title="NLDS-Group.com"><img src="<?php echo $this->data['THEME_IMAGES']; ?>nlds.png" alt="NLDS-Group.com" /></a>
			</p>
			<?php ; if($this->data['AdminLinks']){  ?><p><?php echo $this->data['AdminLinks']; ?></p><?php  }  ?>
		</div>
		<!--<div class="right"><?php echo $this->data['Footer']; ?></div>-->
	</div>
</div>