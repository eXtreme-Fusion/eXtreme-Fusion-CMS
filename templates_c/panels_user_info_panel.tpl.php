<?php ; if($this->data['is_logged_in']){  ?>
	<?php openside(__('User Panel')) ?>
		<div class="center">
			<ul>
				<li><a href="<?php echo $this->data['url_account']; ?>" class="side"><?php echo optLocale($this,"Edit profile"); ?></a></li>
				<li><a href="<?php echo $this->data['url_messages']; ?>" class="side"><?php echo optLocale($this,"Messages"); ?></a></li>
				<li><a href="<?php echo $this->data['url_users']; ?>" class="side"><?php echo optLocale($this,"Users"); ?></a></li>
				<?php ; if($this->data['is_admin']){  ?>
					<li><a href="<?php echo $this->data['ADDR_ADMIN']; ?>" class="side"><?php echo optLocale($this,"Admin Panel"); ?></a></li>
				<?php  }  ?>
				<li><a href="<?php echo $this->data['url_logout']; ?>" class="side"><?php echo optLocale($this,"Logout"); ?></a></li>
			</ul>
			<?php ; if($this->data['messages']){  ?>
				<p class="bold"><a href="<?php echo $this->data['url_messages']; ?>" class="side"><?php echo $this->data['messages']; ?></a></p>
			<?php  }  ?>
		</div>
	<?php closeside() ?>
<?php  }else{  ?>
	<?php openside(__('Login')) ?>
		<div style="text-align:center">
			<form method="post" action="<?php echo $this->data['URL_REQUEST']; ?>">
				<div>
					<label for="username"><?php echo optLocale($this,"Username:"); ?></label>
					<div><input type="text" name="username" id="username" class="textbox" style="width:100px" /></div>
				</div>
				<div>
					<label for="password"><?php echo optLocale($this,"Password:"); ?></label>
					<div><input type="password" name="password" id="password" class="textbox" style="width:100px" /></div>
				</div>
				<div>
					<input type="checkbox" name="remember_me" value="y" id="remember" />
					<label for="remember"><?php echo optLocale($this,"Remember me"); ?></label>
				</div>
				<div><input type="submit" name="login" value="<?php echo optLocale($this,"Login"); ?>" class="button" /></div>
			</form>
			<?php ; if($this->data['enable_reg']){  ?><div><a href="<?php echo $this->data['url_register']; ?>" class="side"><span><?php echo optLocale($this,"Register"); ?></span></a></div><?php  }  ?>
			<div><a href="<?php echo $this->data['url_password']; ?>" class="side"><span><?php echo optLocale($this,"Forgot password"); ?></span></a></div>
		</div>
	<?php closeside() ?>
<?php  }  ?>
