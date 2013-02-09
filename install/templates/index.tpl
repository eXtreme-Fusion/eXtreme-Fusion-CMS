<!DOCTYPE html>
<html>
	<head>
		<meta charset="{$charset}">
		<title>{$title}</title>
		<link rel="stylesheet" href="{$ADDR_SITE}templates/stylesheet/common/grid.reset.css">
		<link rel="stylesheet" href="{$ADDR_SITE}templates/stylesheet/common/grid.text.css">
		<link rel="stylesheet" href="{$ADDR_SITE}templates/stylesheet/common/grid.960.css">
		<link rel="stylesheet" href="{$ADDR_INSTALL}stylesheet/jquery.ui.css">
		<link rel="stylesheet" href="{$ADDR_INSTALL}stylesheet/jquery.uniform.css">
		<link rel="stylesheet" href="{$ADDR_INSTALL}stylesheet/jquery.table.css">
		<link rel="stylesheet" href="{$ADDR_INSTALL}stylesheet/main.css">
		<script src="{$ADDR_SITE}templates/javascripts/common/jquery.js"></script>
		<script src="{$ADDR_SITE}templates/javascripts/common/jquery.uniform.js"></script>
		<script src="{$ADDR_INSTALL}javascripts/passwordStrengthMeter.js"></script>
		<script src="{$ADDR_INSTALL}javascripts/main.js"></script>
	</head>
	<body>
		<div style="background: #121212; height: 60px; margin-bottom: 16px; padding: 10px 0;">
			<div class="container_12">
				<img src="{$ADDR_ADMIN}templates/images/shared/extreme-fusion-logo.png" alt="{$title}">
			</div>
		</div>

	<div id="Content">
		<div class='corner4px'><div class='ctl'><div class='ctr'><div class='ctc'></div></div></div><div class='cc'>
			<div id="IframeOPT" class="container_12">



				<div class="clear"></div>
				<h3 class="ui-corner-all">
					{$step_header}
				</h3>

				<ul id="InstalationSteps">
					{section=step_menu}
						<li class="{if $step_menu.done}done{elseif $step_menu.current}bold{/if}">{$step_menu.name}</li>
					{/section}
				</ul>

				<div id="MainBox">

					{if $step == 0}
						<p>{i18n('The installation was interrupted. The system can be unstable.')}</p>
						<p><a href={$ADDR_INSTALL} title={i18n('Start the installation again.')}>{i18n('Start the installation again.')}</a></p>
					{elseif $step == 1}
						<form action="index.php" method="post" id="This" autocomplete="off">

							<div class="tbl1">
								<div class="formLabel grid_4"><label for="01">{i18n('Please select the required locale (language):')}</label></div>
								<div class="formField grid_4">
									<select id="01" name='localeset'>{$languages}</select>
								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div><br />

							<!-- NIE USUWAĆ !!!-->
							<!-- <br/><div class="status"><?php //echo(__('Download more locales from <a href="http://www.extreme-fusion.org/">extreme-fusion.org</a>')) ?></div><br />-->
							<!-- NIE USUWAĆ !!!-->

							<div class="center">
								<label>
									<input type="checkbox" name="AcceptCC" value="yes">
									<a id="AcceptLink" href="http://extreme-fusion.org/ef5/license/" target="_blank">
									{i18n('I accept BSD License')}
									</a>
								</label>
							</div>

							<br /><hr /><br />
							<div class="center">
								<input type='hidden' name='step' value='1' />
								<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;display:none;">
									<strong class="o">
										<strong class="m">
											<strong>{i18n('Next')}</strong>
										</strong>
									</strong>
								</a>
							</div>
							<div class="clear"></div>

							<div class="tab-click" id="crew-list"><a href="javascript:void(0)">{i18n('Developers of eXtreme-Fusion v5.0')}</a></div>


							<div id="tab-crew-list" class="tab-cont">
								<div class="center">
									<div id="leaders">
										<div class="left"><span class="bold">Project founder:</span> Wojciech (zer0) Mycka</div>
										<div class="right"><span class="bold">Project leader:</span> Paweł (Inscure) Zegardło</div>
									</div>
									<div class="clear"></div>

									<div id="team">
										<div class="bold">Code Developers:</div>

										<p>Andrzej (Andrzejster) Sternal</p>
										<p>Dominik (Domon) Barylski</p>
										<p>Paweł (Inscure) Zegardło</p>
										<p>Piotr (piotrex41) Krzysztofik</p>
										<p>Rafał (Rafik89) Krupiński</p>
										<p>Wojciech (zer0) Mycka</p>

										<div class="bold">Design Developers:</div>

										<p>Andrzej (Andrzejster) Sternal </p>
										<p>Piotr (piotrex41) Krzysztofik</p>
										<p>Wojciech (zer0) Mycka </p>

										<div class="bold">Language Team:</div>

										<p>Marcin (Tymcio) Tymków - English language files</p>
										<p>Pavel (LynX) Laurenčík - Czech language files</p>

										<div class="bold">jQuery & Ajax Developers:</div>

										<p>Dominik (Domon) Barylski</p>
										<p>Paweł (Inscure) Zegardło </p>
										<p>Wojciech (zer0) Mycka </p>

										<div class="bold">Beta testers:</div>

										<p>Dariusz (Chomik) Markiewicz</p>
										<p>Mariusz (FoxNET) Bartoszewicz</p>

									</div>
								</div>
							</div>
						</form>
					{elseif $step == 2}
							{if $extension_error || $php_version_error}
								{if $php_version_error}
									<div class='grid_1'>&nbsp;</div>
									<div class='center grid_7'>
										<p style='color:red;'>
											{i18n('PHP Version Error', array(':php_required' => $php_required))}
										</p>
									</div>
									<div class='grid_1'>&nbsp;</div>
									<div class='clear'></div>
									<br />
								{else}
									{section=extension_error}
										<div class='grid_1'>&nbsp;</div>
										<div class='center grid_7'><span style='color:red;'>{i18n('Extension error', array(':extension_error' => $extension_error.name))}</span></div>
										<div class='grid_1'>&nbsp;</div>
										<div class='clear'></div>
										<br />
									{/section}
									<br />
								{/if}


								<hr /><br />
								<div class="center">
									<a id="refresh" class="SendButton" style="width:100px;margin:0 auto;">
										<strong class="o">
											<strong class="m">
												<strong>{i18n('Refresh')} &raquo;</strong>
											</strong>
										</strong>
									</a>
								</div>
							{else}
								<div class="center">
									<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
										<strong class="o">
											<strong class="m">
												<strong>{i18n('Next')}</strong>
											</strong>
										</strong>
									</a>
								</div>
							{/if}
							<a class="CancelButton" style="width:100px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong title={i18n('Stop the installation or start from the begining.')}>{i18n('Stop installation')}</strong>
									</strong>
								</strong>
							</a>
							<div class="clear"></div>
					{elseif $step == 3}

						<input type='hidden' name='step' value='3' />

						{if $config_error || $chmod_error}
							{if $config_error}
								<div class="info">{i18n('The names of the files listed below please change with the instructions.')}</div><br />

								<div class='grid_2'>&nbsp;</div>
								<div class='grid_3'>&raquo; sample.config.php => config.php</div>
								<div class='center grid_3'><span style='color:red;'>{i18n('Files has not been changed.')}</span></div>
								<div class='clear'></div>
							{/if}

							{if $chmod_error}
								<div class="info">{i18n('The folders and files listed below must be set writeable (chmod 777).')}</div><br />
								{section=chmod_error}
									<div class='grid_2'>&nbsp;</div>
									<div class='grid_3'>&raquo; {$chmod_error.name}</div>
									<div class='center grid_3'><span style='color:red;'>{if $chmod_error.status == 1}{i18n('Not found')}{else}{i18n('Unwriteable')}{/if}</span></div>
									<div class='clear'></div>
								{/section}
							{/if}

							<br />

							<hr /><br />
							<div class="center">
								<a id="refresh" class="SendButton" style="width:100px;margin:0 auto;">
									<strong class="o">
										<strong class="m">
											<strong>{i18n('Refresh')} &raquo;</strong>
										</strong>
									</strong>
								</a>
							</div>
						{else}
							<div class="center">
								<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
									<strong class="o">
										<strong class="m">
											<strong>{i18n('Next')}</strong>
										</strong>
									</strong>
								</a>
							</div>
						{/if}
						<a class="CancelButton" style="width:100px;margin:0 auto;">
							<strong class="o">
								<strong class="m">
									<strong title={i18n('Stop the installation or start from the begining.')}>{i18n('Stop installation')}</strong>
								</strong>
							</strong>
						</a>
						<div class="clear"></div>

					{elseif $step == 4}

						{if $empty_form_error}
							<div class='error'><strong>{i18n('Error:')}</strong> {i18n('There are empty fields left!')}</div><br />
							<div class='status'>{i18n('Please make sure you have filled out all the MySQL connection fields.')}</div>
						{else}
							{if $database_connection_error}
								<div class='error'><strong>{i18n('Error:')}</strong> {i18n('Unable to connect with MySQL database.')}</div><br />
								<div class='status'>{i18n('Sprawdź, czy podane dane są prawidłowe lub skontaktuj się z dostawcą hostingu.')}</div>
							{else}
								{if $table_prefix_error}
									<div class='error'><strong>{i18n('Error:')}</strong> {i18n('Table prefix error.')}</div><br />
									<div class='status'>{i18n('Tables prefix (Advanced settings) is already in use or prefix has not been written, and tables prefix exist in the database with the same name as that system is trying to create. Please enter a different prefix for tables.')}</div>
								{else}
									{if $database_permission_error}
										<div class='valid'>{i18n('Database connection established.')}</div><br />
										<div class='error'><strong>{i18n('Error:')}</strong> {i18n('Could not write or delete MySQL tables.')}</div><br />
										<div class='status'>{i18n('Please make sure your MySQL user has read, write and delete permission for the selected database.')}</div>
									{else}
										{if $config_write_error}
											<div class='valid'>{i18n('Database connection established.')}</div><br />
											<div class='error'><strong>{i18n('Error:')}</strong> {i18n('Unable to write config file.')}</div><br />
											<div class='status'>{i18n('Please ensure config.php is writable.')}</div>
										{elseif $table_creating_error}
											<div class='valid'>{i18n('Database connection established.')}</div><br />
											<div class='valid'>{i18n('Config file successfully written.')}</div><br />
											<div class='error'><strong>{i18n('Error:')}</strong> {i18n('Unable to create database tables.')}</div>
										{/if}
									{/if}
								{/if}
							{/if}
						{/if}

						<form action="index.php" method="post" id="This" autocomplete="off">

							<div class="info">{i18n('Please enter your MySQL database access settings.')}</div><br />

							<div class="tbl1">
								<div class="formLabel grid_4"><label for="04">{i18n('Database Name:')}*</label></div>
								<div class="formField grid_3"><input id="04" type='text' value='{$db_name}' name='db_name' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl2">
								<div class="formLabel grid_4"><label for="02">{i18n('Database Username:')}*</label></div>
								<div class="formField grid_3"><input id="02" type='text' value='{$db_user}' name='db_user' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl1">
								<div class="formLabel grid_4"><label for="03">{i18n('Password:')}</label></div>
								<div class="formField grid_3"><input id="03" type='password' name='db_pass' /></div>
								<div class="clear"></div>
							</div>

							<div class="tab-click" id="url"><a href="javascript:void(0)">{i18n('Advanced')}</a></div>

							<div id="tab-url" class="tab-cont">
								<p id="CustomStep3" class="red">
									{i18n('NOTE! If after installation you experience problems with links and URLs (error 404), you must reinstall the system without checking the box below, or change the $ _route in config.php.')}
								</p>
								{if $custom_rewrite}
									<div class="formWarning formMessage">
										<input type="checkbox" name="custom_rewrite" value="true" {if $custom_rewrite_choice}checked="checked" {/if}id="CustomRewrite" />
										<label for="CustomRewrite">
											{i18n('modRewrite warning')}
										</label>
									</div>
								{elseif $custom_furl}
									<div class="formWarning formMessage">
										<input type="checkbox" name="custom_furl" value="true" {if $custom_furl_choice}checked="checked" {/if}id="CustomFurl" />
										<label for="CustomFurl">
											{i18n('FURL warning')}
										</label>
									</div>
								{/if}

								<div class="tbl1">
									<div class="formLabel grid_4"><label for="01">{i18n('Database Hostname:')}*</label></div>
									<div class="formField grid_3"><input id="01" type='text' value='{$db_host}' name='db_host' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="01">{i18n('Database Port:')}*</label></div>
									<div class="formField grid_3"><input id="01" type='text' value='{$db_port}' name='db_port' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="05">{i18n('Table Prefix:')}</label></div>
									<div class="formField grid_3"><input id="05" type='text' value='{$db_prefix}' name='db_prefix' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="06">{i18n('Cookie Prefix:')}</label></div>
									<div class="formField grid_3"><input id="06" type='text' value='{$cookie_prefix}' name='cookie_prefix' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="06">{i18n('Cache Prefix:')}</label></div>
									<div class="formField grid_3"><input id="06" type='text' value='{$cache_prefix}' name='cache_prefix' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="06">{i18n('URL:')}*</label></div>
									<div class="formField grid_3"><input id="06" type='text' value='{$site_url}' name='site_url' /></div>
									<div class="clear"></div>
								</div>
							</div>

							<br /><hr /><br />
							<div class="center">
								<input type='hidden' name='step' value='4'>
								<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
									<strong class="o">
										<strong class="m">
											<strong>{i18n('Next')} &raquo;</strong>
										</strong>
									</strong>
								</a>
							</div>
							<div class="clear"></div>
						</form>
						<a class="CancelButton" style="width:100px;margin:0 auto;">
							<strong class="o">
								<strong class="m">
									<strong title={i18n('Stop the installation or start from the begining.')}>{i18n('Stop installation')}</strong>
								</strong>
							</strong>
						</a>
					{elseif $step == 5}

							{if $show_info}
								{*
								<div class='valid'>{i18n('Database connection established.')}</div><br />
								<div class='valid'>{i18n('Config file successfully written.')}</div><br />
								<div class='valid'>{i18n('Database tables created.')}</div>
								*}
							{/if}

							{if $empty_form_error}
								<div class='error'><strong>{i18n('Error:')}</strong> {i18n('Empty fields.')}</div><br />
							{else}
								{if $username_error}
									{i18n('Your login does not appear to be valid.')}
								{/if}

								{if $password_error}
									{i18n('Your two login passwords do not match.')}
								{/if}

								{if $email_error}
									{i18n('Your email address does not appear to be valid.')}
								{/if}

								{if $account_creating_error}
									Konto administratora nie zostało utworzone
								{/if}
							{/if}

							<form action="index.php" method="post" id="This" autocomplete="off">
								<div class="info">{i18n('Primary Super Admin login details')}</div><br />
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="username">{i18n('Username:')}*</label></div>
									<div class="formField grid_3"><input id="username" type='text' value='{$username}' name='username' maxlength='30' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="password1">{i18n('Login Password:')}*</label></div>
									<div class="formField grid_3">
										<input id="password1" type='password' name='password1' maxlength='20' />
										<div class="graybar" id="graybar"></div>
										<div class="colorbar" id="colorbar"></div>
									</div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="03">{i18n('Repeat Login password:')}*</label></div>
									<div class="formField grid_3"><input id="03" type='password' name='password2' maxlength='20' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="06">{i18n('Email address:')}*</label></div>
									<div class="formField grid_3">
										<input id="06" type='text' value='{$email}' name='email' maxlength='100' />
										<input type='hidden' name='site_url' value='{$ADDR_SITE}' /></div>
									<div class="clear"></div>
								</div>

								<br /><hr /><br />
								<div class="center">
									<input type='hidden' name='step' value='5' />
									<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
										<strong class="o">
											<strong class="m">
												<strong>{i18n('Next')} &raquo;</strong>
											</strong>
										</strong>
									</a>
								</div>
							</form>

							<a class="CancelButton" style="width:100px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong title={i18n('Stop the installation or start from the begining.')}>{i18n('Stop installation')}</strong>
									</strong>
								</strong>
							</a>
					{elseif $step == 6}

						<div class="center">{i18n('Setup complete')}</div><br />
						<form action="index.php" method="post" id="This" autocomplete="off">
							<input type='hidden' name='step' value='6' />
							<br /><hr /><br />
							<a id="SendForm_This" class="SendButton" style="width:110px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong>{i18n('Finish')} &raquo;</strong>
									</strong>
								</strong>
							</a>
						</form>
					{/if}
				</div>
				<div class="clear"></div>

			</div>
		</div>
		<div class='cfl'><div class='cfr'><div class='cfc'></div></div></div></div>
	</div>
</body>
</html>
