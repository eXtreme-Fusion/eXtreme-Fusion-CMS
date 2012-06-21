<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
error_reporting(E_ALL | E_STRICT);

/*
define('FUSION_SELF', basename($_SERVER['PHP_SELF']));
define('VERSION', '5.0');
*/

if( ! DEFINED('DS')) 
{
	define('DS', DIRECTORY_SEPARATOR);
}

require __DIR__.'../bootstrap.php';
require_once OPT_DIR.'opt.class.php';

$_tpl = new optClass;
$_tpl->compile(DIR_COMPILE);
	
if (isset($_POST['step']) && $_POST['step'] == '6')
{
	require_once '..'.DS.'config.php';

}
else
{
	define('EF5_SYSTEM', TRUE);
	define('DIR_LOCALE', '..'.DS.'locale'.DS);
	define('DIR_CLASS', '..'.DS.'system'.DS.'class'.DS);
}

require '..'.DS.'system'.DS.'helpers'.DS.'main.php';
require '..'.DS.'system'.DS.'class'.DS.'system.php';

$_system = new System(FALSE);

$default_lang = detectBrowserLanguage();

if (isset($_POST['localeset']) && file_exists('..'.DS.'locale'.DS.$_POST['localeset']) && is_dir('..'.DS.'locale'.DS.$_POST['localeset']))
{
	$_SESSION['localeset'] = $_POST['localeset'];
}
elseif ( ! isset($_SESSION['localeset']))
{
	if (file_exists('..'.DS.'locale'.DS.$default_lang) && is_dir('..'.DS.'locale'.DS.$default_lang))
	{
		$_SESSION['localeset'] = $default_lang;
	}
	else
	{
		$_SESSION['localeset'] = 'English';
	}
}
else
{
	unset($_SESSION['localeset']);
}

$_locale = new Locales(isset($_SESSION['localeset']) ? $_SESSION['localeset'] : $default_lang, DIR_LOCALE);
$_locale->load('setup');


if ((isset($_POST['step']) && $_POST['step'] == '7') || (isset($_GET['step']) && $_GET['step'] == '7'))
{
	HELP::removeSession('user', 'admin');
	HELP::removeCookie('user');
	HELP::redirect('../');
}

$charset = 'utf8';
$collate = 'utf8_general_ci';
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo(__('eXtreme-Fusion :version - Setup', array(':version' => VERSION))) ?></title>
	<meta http-equiv='Content-Type' content='text/html; charset=<?php echo __('Charset') ?>' />
	<link rel="stylesheet" type="text/css" href="../admin/templates/stylesheet/grid.reset.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../admin/templates/stylesheet/grid.text.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../admin/templates/stylesheet/grid.960.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="stylesheet/jquery.ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="stylesheet/jquery.uniform.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="stylesheet/jquery.table.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="stylesheet/main.css" media="screen" />
	<script type="text/javascript" src="../admin/templates/javascripts/jquery.js"></script>
	<script type="text/javascript" src="../admin/templates/javascripts/jquery.uniform.js"></script>
	<script type="text/javascript" src="javascripts/passwordStrengthMeter.js"></script>
	<script type="text/javascript" src="javascripts/main.js"></script>
</head>
<body>

	<div style="background:#121212;height:60px;margin-bottom:16px;padding:10px 0;">
		<div class="container_12">
			<img src='../admin/templates/images/shared/extreme-fusion-logo.png' alt=''/>
		</div>
	</div>

	<div id="Content">
		<div class='corner4px'><div class='ctl'><div class='ctr'><div class='ctc'></div></div></div><div class='cc'>
			<div id="IframeOPT" class="container_12">

				<?php
					if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
						$StepHeader = __('Step 1: Locale');
					} elseif (isset($_POST['step']) && $_POST['step'] == "2") {
						$StepHeader = __('Step 2: File and Folder Permissions Test');
					} elseif (isset($_POST['step']) && $_POST['step'] == "3") {
						$StepHeader = __('Step 3: Database Settings');
					} elseif (isset($_POST['step']) && $_POST['step'] == "4") {
						$StepHeader = __('Step 4: Database Config / Setup');
					} elseif (isset($_POST['step']) && $_POST['step'] == "5") {
						$StepHeader = __('Step 5: Head Admin Datails');
					} elseif (isset($_POST['step']) && $_POST['step'] == "6") {
						$StepHeader = __('Step 6: Final Settings');
					} else {
                        $StepHeader = '';
                    }
					$StepMenu1 = explode(":", __('Step 1: Locale'));
					$StepMenu2 = explode(":", __('Step 2: File and Folder Permissions Test'));
					$StepMenu3 = explode(":", __('Step 3: Database Settings'));
					$StepMenu4 = explode(":", __('Step 4: Database Config / Setup'));
					$StepMenu5 = explode(":", __('Step 5: Head Admin Datails'));
					$StepMenu6 = explode(":", __('Step 6: Final Settings'));
					$GetStep = (isset($_POST['step']) ? $_POST['step'] : "");

				?>

				<div class="clear"></div>
				<h3 class="ui-corner-all">
					<?php echo(__('eXtreme-Fusion :version - Setup', array(':version' => VERSION))) ?> &raquo; <?php echo($StepHeader) ?>
				</h3>
				<ul id="InstalationSteps">
					<li class='<?php if($GetStep=='') echo("bold"); elseif($GetStep>1) echo("done");?>'><?php echo($StepMenu1[0]) ?></li>
					<li class='<?php if($GetStep==2) echo("bold"); elseif($GetStep>2) echo("done");?>'><?php echo($StepMenu2[0]) ?></li>
					<li class='<?php if($GetStep==3) echo("bold"); elseif($GetStep>3) echo("done");?>'><?php echo($StepMenu3[0]) ?></li>
					<li class='<?php if($GetStep==4) echo("bold"); elseif($GetStep>4) echo("done");?>'><?php echo($StepMenu4[0]) ?></li>
					<li class='<?php if($GetStep==5) echo("bold"); elseif($GetStep>5) echo("done");?>'><?php echo($StepMenu5[0]) ?></li>
					<li class='<?php if($GetStep==6) echo("bold"); elseif($GetStep>6) echo("done");?>'><?php echo($StepMenu6[0]) ?></li>
				</ul>


				<form action="index.php" method="post" id="This" enctype="multipart/form-data" style="float:left;width:720px;" autocomplete="off">
					<?php
						if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
							$locale_list = makefileopts(makefilelist('..'.DS.'locale/', ".svn|.|..", true, "folders"), detectBrowserLanguage());

					?>
						<div class="tbl1">
							<div class="formLabel grid_4"><label for="01"><?php echo(__('Please select the required locale (language):')) ?></label></div>
							<div class="formField grid_4"><select id="01" name='localeset'><?php echo($locale_list) ?></select></div>
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
								<?php echo(__('I accept BSD License')) ?>
								</a>
							</label>
						</div>

						<br /><hr /><br />
						<div class="center">
							<input type='hidden' name='step' value='2' />
							<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;display:none;">
								<strong class="o">
									<strong class="m">
										<strong><?php echo(__('Next')) ?></strong>
									</strong>
								</strong>
							</a>
						</div>
						<div class="clear"></div>
						<ul id="links">
							<li id="team-tab"><a href="javascript:void(0)">Twórcy eXtreme-Fusion CMS v5</a></li>
						</ul>

						<div id="ef-crew">
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

									<div class="bold">jQuery && Ajax Developers:</div>

									<p>Dominik (Domon) Barylski</p>
									<p>Paweł (Inscure) Zegardło </p>
									<p>Wojciech (zer0) Mycka </p>

									<div class="bold">Beta testers:</div>

									<p>Dariusz (Chomik) Markiewicz</p>
									<p>Mariusz (FoxNET) Bartoszewicz</p>

								</div>
							</div>
						</div>
					<?php
						}

						if (isset($_POST['step']) && $_POST['step'] == "2") {
							$check_rewrite = false;
							$check_display = "";
							$config_prepared = false;
							$htaccess_prepared = false;

							if ( ! file_exists('..'.DS.'config.php')) {
								if (file_exists('..'.DS.'sample.config.php') && function_exists("rename")) {
									if (@rename('..'.DS.'sample.config.php', '..'.DS.'config.php') && @chmod('..'.DS.'config.php', 0777)) {
										$config_prepared = true;
									}
								}
								/* Po co to?
								 else {
									$handle = fopen('..'.DS.'config.php', "w");
									fclose($handle);
								}*/
							}
							if ($_system->apacheLoadedModules('mod_rewrite')) {
								$check_rewrite = true;

								if ( ! file_exists('..'.DS.'.htaccess')) {
									if (file_exists('..'.DS.'sample.htaccess') && function_exists("rename")) {
										if (@rename('..'.DS.'sample.htaccess', '..'.DS.'.htaccess')) {
											$htaccess_prepared = true;
										}
									}
								}
							}



							 ?>


							<div class="info"><?php echo "Nazwy poniższych plików proszę zmienić według instrukcji. Czynność jest niezbędna do dokończenia instalacji.";  ?></div><br />

							<div class='grid_2'>&nbsp;</div>
							<div class='grid_3'>&raquo; sample.config.php => config.php</div>
							<div class='center grid_3'>
								<?php if (file_exists('..'.DS.'config.php')) {
									echo "<span style='color:green;'>OK</span>";
								}else {
									$extension_error = true;
									echo "<span style='color:red;'>Nie zmieniono</span>";
								} ?>
								</div>
							<div class='clear'></div>
							<?php
							if ($check_rewrite === true) { ?>
							<div class='grid_2'>&nbsp;</div>
							<div class='grid_3'>&raquo; sample.htaccess => .htaccess</div>
							<div class='center grid_3'><?php if (file_exists('..'.DS.'.htaccess')) {
									echo "<span style='color:green;'>OK</span>";
								}else {
									$extension_error = true;
									echo "<span style='color:red;'>Nie zmieniono</span>";
								} ?>
								</div>
							<div class='clear'></div>
							<?php } ?>

							<br /><div class="info"><?php echo "Proszę także zmienić uprawnienia na zapis w poniższych katalogach i plikach"; ?></div><br />

							<?php
							$check_arr = array(
								"..".DS."cache".DS => false,
								"..".DS."upload".DS => false,
								"..".DS."upload".DS."archives".DS => false,
								"..".DS."upload".DS."documents".DS => false,
								"..".DS."upload".DS."images".DS => false,
								"..".DS."upload".DS."movies".DS => false,
								"..".DS."system".DS."opt".DS."plugins".DS => false,
								"..".DS."templates".DS."images".DS => false,
								"..".DS."templates".DS."images".DS."imagelist.js" => false,
								"..".DS."templates".DS."images".DS."avatars".DS => false,
								"..".DS."templates".DS."images".DS."news".DS => false,
								"..".DS."templates".DS."images".DS."news".DS."thumbs".DS => false,
								"..".DS."templates".DS."images".DS."news_cats".DS => false,
								"..".DS."templates_c".DS => false,
								"..".DS."tmp".DS => false,
								"..".DS."config.php" => false
							);

							$write_check = true; $i = 0;
							foreach ($check_arr as $key => $value) {
								if (file_exists($key) && is_writable($key)) {
									$check_arr[$key] = true;
									$i++;
								} else {
									if (file_exists($key) && function_exists("chmod") && @chmod($key, 0777) && is_writable($key)) {
										$check_arr[$key] = true;
										$i++;
									} else {
										$write_check = false;
									}
								}
								$check_display .= "<div class='grid_2'>&nbsp;</div>\n";
								$check_display .= "<div class='grid_3'>&raquo; ".str_replace(array('..\\', '../'), '', $key)."</div>\n";
								$check_display .= "<div class='center grid_3'>".($check_arr[$key] == true ? "<span style='color:green;'>".__('Writeable')."</span>" : "<span style='color:red;'>".__('Unwriteable')."</span>")."</div>\n";
								$check_display .= "<div class='clear'></div>\n";
							}
							$count = $i;

							?>



							<?php
							echo $check_display;


							?>

							<br />
								<?php
								if( ! extension_loaded('pdo_mysql'))
								{$extension_error = TRUE; ?>
									<div class='grid_1'>&nbsp;</div>
									<div class='center grid_7'><span style='color:red;'>Nie znaleziono wymaganego rozszerzenia mysql_pdo. Należy je załadować przez odpowiednią konfigurację serwera.</span></div>
									<div class='grid_1'>&nbsp;</div>
									<div class='clear'></div>
									<br />
								<?php
								}
								if( ! extension_loaded('mcrypt'))
								{ $extension_error = TRUE;?>
									<div class='grid_1'>&nbsp;</div>
									<div class='center grid_7'><span style='color:red;'>Nie znaleziono wymaganego rozszerzenia mcrypt. Należy je załadować przez odpowiednią konfigurację serwera.</span></div>
									<div class='grid_1'>&nbsp;</div>
									<div class='clear'></div>
									<br />
								<?php
								}

								if (! $write_check)
								{ $extension_error = TRUE;
								 ?>
										<br /><div class="valid"><?php echo(__('Write permissions check failed, please CHMOD files/folders marked Unwriteable.')) ?></div><br />

								 <?php
								}








								if (!isset($extension_error))
								{ ?>
									<div class="center">
									<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
									<input type='hidden' name='step' value='3' />
									<?php
										if ($count == 9)
										{
									?>
										<script language="javascript" type="text/javascript">
											document.forms[0].submit();
										</script>
									<?php
										}
									?>
									<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
										<strong class="o">
											<strong class="m">
												<strong><?php echo(__('Next')) ?></strong>
											</strong>
										</strong>
									</a>
								<?php } else { ?>
									<hr /><br />
										<div class="center">
										<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
										<input type='hidden' name='step' value='2' />
										<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
											<strong class="o">
												<strong class="m">
													<strong><?php echo(__('Refresh')) ?> &raquo;</strong>
												</strong>
											</strong>
										</a>
								<?php } ?>
							</div>
							<div class="clear"></div>
						<?php }


						if (isset($_POST['step']) && $_POST['step'] == "3") {
							$HostURL = '';
							$db_prefix = "extreme_".substr(md5(uniqid("ef5_db", false)), 13, 7)."_";
							$cookie_prefix = "extreme_".substr(md5(uniqid("ef5_cookie", false)), 13, 7)."_";
							$cache_prefix = "extreme_".substr(md5(uniqid("ef5_cache", false)), 13, 7)."_";

							$db_host = (isset($_POST['db_host']) ? stripinput(trim($_POST['db_host'])) : "localhost");
							$db_port = (isset($_POST['db_port']) ? stripinput(trim($_POST['db_port'])) : "3306");
							$db_user = (isset($_POST['db_user']) ? stripinput(trim($_POST['db_user'])) : "");
							$db_name = (isset($_POST['db_name']) ? stripinput(trim($_POST['db_name'])) : "");
							$db_prefix = (isset($_POST['db_prefix']) ? stripinput(trim($_POST['db_prefix'])) : $db_prefix);
							$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : $cookie_prefix);
							$cache_prefix = (isset($_POST['cache_prefix']) ? stripinput(trim($_POST['cache_prefix'])) : $cache_prefix);
							$db_error = (isset($_POST['db_error']) && isnum($_POST['db_error']) ? $_POST['db_error'] : "0");

							$field_class = array("", "", "", "", "");
							if ($db_error > "0") {
								$field_class[2] = " tbl-error";
								if ($db_error == 1) {
									$field_class[1] = " tbl-error";
									$field_class[2] = " tbl-error";
								} elseif ($db_error == 2) {
									$field_class[3] = " tbl-error";
								} elseif ($db_error == 3) {
									$field_class[4] = " tbl-error";
								} elseif ($db_error == 7) {
									if ($db_host == "") { $field_class[0] = " tbl-error"; }
									if ($db_user == "") { $field_class[1] = " tbl-error"; }
									if ($db_name == "") { $field_class[3] = " tbl-error"; }
									if ($db_prefix == "") { $field_class[4] = " tbl-error"; }
									if ($cookie_prefix == "") { $field_class[4] = " tbl-error"; }
									if ($cache_prefix == "") { $field_class[4] = " tbl-error"; }
								}
							}
							$HostURL = explode("install", $_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']);

							?>

							<?php
								if (isset($_POST['msg']))
								{
									echo($_POST['msg']);
								}
							?>
							<div class="info"><?php echo(__('Please enter your MySQL database access settings.')) ?></div><br />
							<div class="tbl1">
								<div class="formLabel grid_4"><label for="01"><?php echo(__('Database Hostname:')) ?></label></div>
								<div class="formField grid_3"><input id="01" type='text' value='<?php echo($db_host) ?>' name='db_host' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl2">
								<div class="formLabel grid_4"><label for="01"><?php echo(__('Database Port:')) ?></label></div>
								<div class="formField grid_3"><input id="01" type='text' value='<?php echo($db_port) ?>' name='db_port' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl1">
								<div class="formLabel grid_4"><label for="02"><?php echo(__('Database Username:')) ?></label></div>
								<div class="formField grid_3"><input id="02" type='text' value='<?php echo($db_user) ?>' name='db_user' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl2">
								<div class="formLabel grid_4"><label for="03"><?php echo(__('Database Password:')) ?></label></div>
								<div class="formField grid_3"><input id="03" type='password' name='db_pass' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl1">
								<div class="formLabel grid_4"><label for="04"><?php echo(__('Database Name:')) ?></label></div>
								<div class="formField grid_3"><input id="04" type='text' value='<?php echo($db_name) ?>' name='db_name' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl2">
								<div class="formLabel grid_4"><label for="05"><?php echo(__('Table Prefix:')) ?></label></div>
								<div class="formField grid_3"><input id="05" type='text' value='<?php echo($db_prefix) ?>' name='db_prefix' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl1">
								<div class="formLabel grid_4"><label for="06"><?php echo(__('Cookie Prefix:')) ?></label></div>
								<div class="formField grid_3"><input id="06" type='text' value='<?php echo($cookie_prefix) ?>' name='cookie_prefix' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl2">
								<div class="formLabel grid_4"><label for="06"><?php echo(__('Cache Prefix:')) ?></label></div>
								<div class="formField grid_3"><input id="06" type='text' value='<?php echo($cache_prefix) ?>' name='cache_prefix' /></div>
								<div class="clear"></div>
							</div>
							<div class="tbl1">
								<div class="formLabel grid_4"><label for="06"><?php echo(__('URL:')) ?></label></div>
								<div class="formField grid_3"><input id="06" type='text' value='<?php echo("http://".$HostURL[0]) ?>' name='site_url' /></div>
								<div class="clear"></div>
							</div>

							<br /><hr /><br />
							<div class="center">
								<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
								<input type='hidden' name='step' value='5'>
								<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
									<strong class="o">
										<strong class="m">
											<strong><?php echo(__('Next')) ?> &raquo;</strong>
										</strong>
									</strong>
								</a>
							</div>
							<div class="clear"></div>

						<?php }

					/*if (isset($_POST['step']) && $_POST['step'] == "4") {
						$msg = '';
						$db_host = (isset($_POST['db_host']) ? stripinput(trim($_POST['db_host'])) : "");
						$db_user = (isset($_POST['db_user']) ? stripinput(trim($_POST['db_user'])) : "");
						$db_pass = (isset($_POST['db_pass']) ? stripinput(trim($_POST['db_pass'])) : "");
						$db_name = (isset($_POST['db_name']) ? stripinput(trim($_POST['db_name'])) : "");
						$db_prefix = (isset($_POST['db_prefix']) ? stripinput(trim($_POST['db_prefix'])) : "");
						$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : "");
						$cache_prefix = (isset($_POST['cache_prefix']) ? stripinput(trim($_POST['cache_prefix'])) : "");
						$site_url = (isset($_POST['site_url']) ? $_POST['site_url'] : "");
						if ($db_prefix != "") {
							$db_prefix_last = $db_prefix[strlen($db_prefix)-1];
							if ($db_prefix_last != "_") { $db_prefix = $db_prefix."_"; }
						}
						if ($cookie_prefix != "") {
							$cookie_prefix_last = $cookie_prefix[strlen($cookie_prefix)-1];
							if ($cookie_prefix_last != "_") { $cookie_prefix = $cookie_prefix."_"; }
						}
						if ($cache_prefix != "") {
							$cache_prefix_last = $cache_prefix[strlen($cache_prefix)-1];
							if ($cache_prefix_last != "_") { $cache_prefix = $cache_prefix."_"; }
						}

						if ($db_host != "" && $db_user != "" && $db_name != "" && $db_prefix != "") {
							$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
							if ($db_connect) {
								$db_select = @mysql_select_db($db_name);
								if ($db_select) {
									if (dbrows(dbquery("SHOW TABLES LIKE '$db_prefix%'")) == "0") {
										$table_name = uniqid($db_prefix, false); $can_write = true;
										$result = dbquery("CREATE TABLE ".$table_name." (test_field VARCHAR(10) NOT NULL) ENGINE=MyISAM;");
										if (!$result) { $can_write = false; }
										$result = dbquery("DROP TABLE ".$table_name);
										if (!$result) { $can_write = false; }
										if ($can_write) {

											include_once "create_config.php";

											$temp = fopen("..".DS."config.php","w");
											if (fwrite($temp, $config)) {
												fclose($temp);
												$fail = false;

												include_once "create_db.php";

												if (!$fail) {
													$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
													$msg .= "<div class='valid'>".__('Config file successfully written.')."</div><br />";
													$msg .= "<div class='valid'>".__('Database tables created.')."</div>";
													$success = true;
													$db_error = 6;
												} else {
													$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
													$msg .= "<div class='valid'>".__('Config file successfully written.')."</div><br />";
													$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to create database tables.')."</div>";
													$success = false;
													$db_error = 0;
												}
											} else {
												$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
												$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to write config file.')."</div><br />";
												$msg .= "<div class='status'>".__('Please ensure config.php is writable.')."</div>";
												$success = false;
												$db_error = 5;
											}
										} else {
											$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
											$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Could not write or delete MySQL tables.')."</div><br />";
											$msg .= "<div class='status'>".__('Please make sure your MySQL user has read, write and delete permission for the selected database.')."</div>";
											$success = false;
											$db_error = 4;
										}
									} else {
										$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Table prefix error.')."</div><br />";
										$msg .= "<div class='status'>".__('The specified table prefix is already in use.')."</div>";
										$success = false;
										$db_error = 3;
									}
								} else {
									$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to connect with MySQL database.')."</div><br />";
									$msg .= "<div class='status'>".__('The specified MySQL database does not exist.')."</div>";
									$success = false;
									$db_error = 2;
								}
							} else {
								$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to connect with MySQL.')."</div><br />";
								$msg .= "<div class='status'>".__('Please ensure your MySQL username and password are correct.')."</div>";
								$success = false;
								$db_error = 1;
							}
						} else {
							$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Empty fields.')."</div><br />";
							$msg .= "<div class='status'>".__('Please make sure you have filled out all the MySQL connection fields.')."</div>";
							$success = false;
							$db_error = 7;
						}
						echo("<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />");
						if ($success) { ?>

							<br /><hr /><br />
							<div class="center">
								<input type='hidden' name='step' value='5'>
                                <input type='hidden' name='site_url' value='<?php echo($site_url) ?>' />
								<input type='hidden' name='success' value='<?php echo($success) ?>' />
								<input type='hidden' name='msg' value='<?php echo($msg) ?>' />
								<script language="javascript" type="text/javascript">
									document.forms[0].submit();
								</script>
								<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
									<strong class="o">
										<strong class="m">
											<strong><?php echo(__('Next')) ?> &raquo;</strong>
										</strong>
									</strong>
								</a>
							</div>

						<?php } else {
							echo($msg);
						?>

							<br /><hr /><br />
							<div class="center">
								<input type='hidden' name='step' value='3' />
								<input type='hidden' name='db_host' value='<?php echo($db_host) ?>' />
								<input type='hidden' name='db_user' value='<?php echo($db_user) ?>' />
								<input type='hidden' name='db_name' value='<?php echo($db_name) ?>' />
								<input type='hidden' name='db_prefix' value='<?php echo($db_prefix) ?>' />
								<input type='hidden' name='cookie_prefix' value='<?php echo($cookie_prefix) ?>' />
								<input type='hidden' name='cache_prefix' value='<?php echo($cache_prefix) ?>' />
								<input type='hidden' name='db_error' value='<?php echo($db_error) ?>' />
                                <input type='hidden' name='email' value='<?php echo($_POST['site_url']) ?>' />
								<a id="SendForm_This" class="CancelButton" style="width:100px;margin:0 auto;">
									<strong class="o">
										<strong class="m">
											<strong>&laquo; <?php echo(__('Back')) ?></strong>
										</strong>
									</strong>
								</a>
							</div>

						<?php
						}

					}*/

					if (isset($_POST['step']) && $_POST['step'] == "5") {
						$msg = '';
						$db_host = (isset($_POST['db_host']) ? stripinput(trim($_POST['db_host'])) : "");
						$db_port = (isset($_POST['db_port']) ? stripinput(trim($_POST['db_port'])) : "");
						$db_user = (isset($_POST['db_user']) ? stripinput(trim($_POST['db_user'])) : "");
						$db_pass = (isset($_POST['db_pass']) ? stripinput(trim($_POST['db_pass'])) : "");
						$db_name = (isset($_POST['db_name']) ? stripinput(trim($_POST['db_name'])) : "");
						$db_prefix = (isset($_POST['db_prefix']) ? stripinput(trim($_POST['db_prefix'])) : "");
						$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : "");
						$cache_prefix = (isset($_POST['cache_prefix']) ? stripinput(trim($_POST['cache_prefix'])) : "");
						$site_url = (isset($_POST['site_url']) ? $_POST['site_url'] : "");
						if ($db_prefix != "") {
							$db_prefix_last = $db_prefix[strlen($db_prefix)-1];
							if ($db_prefix_last != "_") { $db_prefix = $db_prefix."_"; }
						}
						if ($cookie_prefix != "") {
							$cookie_prefix_last = $cookie_prefix[strlen($cookie_prefix)-1];
							if ($cookie_prefix_last != "_") { $cookie_prefix = $cookie_prefix."_"; }
						}
						if ($cache_prefix != "") {
							$cache_prefix_last = $cache_prefix[strlen($cache_prefix)-1];
							if ($cache_prefix_last != "_") { $cache_prefix = $cache_prefix."_"; }
						}

						if ($db_host != "" && $db_port != "" && $db_user != "" && $db_name != "" && $db_prefix != "") {
							$db_connect = @mysql_connect($db_host.':'.$db_port, $db_user, $db_pass);
							if ($db_connect) {
								$db_select = @mysql_select_db($db_name);
								if ($db_select) {
									if (dbrows(dbquery("SHOW TABLES LIKE '$db_prefix%'")) == "0") {
										$table_name = uniqid($db_prefix, false); $can_write = true;
										$result = dbquery("CREATE TABLE ".$table_name." (test_field VARCHAR(10) NOT NULL) ENGINE=MyISAM;");
										if (!$result) { $can_write = false; }
										$result = dbquery("DROP TABLE ".$table_name);
										if (!$result) { $can_write = false; }
										if ($can_write) {

											include_once "create_config.php";

											$temp = fopen("..".DS."config.php","w");
											if (fwrite($temp, $config)) {
												fclose($temp);
												$fail = false;

												include_once "create_db.php";

												if (!$fail) {
													$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
													$msg .= "<div class='valid'>".__('Config file successfully written.')."</div><br />";
													$msg .= "<div class='valid'>".__('Database tables created.')."</div>";
													$success = true;
													$db_error = 6;
												} else {
													$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
													$msg .= "<div class='valid'>".__('Config file successfully written.')."</div><br />";
													$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to create database tables.')."</div>";
													$success = false;
													$db_error = 0;
												}
											} else {
												$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
												$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to write config file.')."</div><br />";
												$msg .= "<div class='status'>".__('Please ensure config.php is writable.')."</div>";
												$success = false;
												$db_error = 5;
											}
										} else {
											$msg .= "<div class='valid'>".__('Database connection established.')."</div><br />";
											$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Could not write or delete MySQL tables.')."</div><br />";
											$msg .= "<div class='status'>".__('Please make sure your MySQL user has read, write and delete permission for the selected database.')."</div>";
											$success = false;
											$db_error = 4;
										}
									} else {
										$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Table prefix error.')."</div><br />";
										$msg .= "<div class='status'>".__('The specified table prefix is already in use.')."</div>";
										$success = false;
										$db_error = 3;
									}
								} else {
									$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to connect with MySQL database.')."</div><br />";
									$msg .= "<div class='status'>".__('The specified MySQL database does not exist.')."</div>";
									$success = false;
									$db_error = 2;
								}
							} else {
								$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Unable to connect with MySQL.')."</div><br />";
								$msg .= "<div class='status'>".__('Please ensure your MySQL username and password are correct.')."</div>";
								$success = false;
								$db_error = 1;
							}
						} else {
							$msg .= "<div class='error'><strong>".__('Error:')."</strong> ".__('Empty fields.')."</div><br />";
							$msg .= "<div class='status'>".__('Please make sure you have filled out all the MySQL connection fields.')."</div>";
							$success = false;
							$db_error = 7;
						}

						$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
						$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
						$error_pass = (isset($_POST['error_pass']) && isnum($_POST['error_pass']) ? $_POST['error_pass'] : "0");
						$error_name = (isset($_POST['error_name']) && isnum($_POST['error_name']) ? $_POST['error_name'] : "0");
						$error_mail = (isset($_POST['error_mail']) && isnum($_POST['error_mail']) ? $_POST['error_mail'] : "0");
                        $site_url = (isset($_POST['site_url']) ? $_POST['site_url'] : "");

						$field_class = array("", "", "", "", "", "");
						if ($error_pass == "1" || $error_name == "1" || $error_mail == "1") {
							$field_class = array("", " tbl-error", " tbl-error", " tbl-error", " tbl-error", "");
							if ($error_name == 1) { $field_class[0] = " tbl-error"; }
							if ($error_mail == 1) { $field_class[5] = " tbl-error"; }
						}

						if ($success)
						{
						?>

						<div class="info"><?php echo(__('Primary Super Admin login details')) ?></div><br />
						<div class="tbl1">
							<div class="formLabel grid_4"><label for="username"><?php echo(__('Username:')) ?></label></div>
							<div class="formField grid_3"><input id="username" type='text' value='<?php echo($username) ?>' name='username' maxlength='30' /></div>
							<div class="clear"></div>
						</div>
						<div class="tbl2">
							<div class="formLabel grid_4"><label for="password1"><?php echo(__('Login Password:')) ?></label></div>
							<div class="formField grid_3">
								<input id="password1" type='password' name='password1' maxlength='20' />
								<div class="graybar" id="graybar"></div>
								<div class="colorbar" id="colorbar"></div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="tbl1">
							<div class="formLabel grid_4"><label for="03"><?php echo(__('Repeat Login password:')) ?></label></div>
							<div class="formField grid_3"><input id="03" type='password' name='password2' maxlength='20' /></div>
							<div class="clear"></div>
						</div>
						<div class="tbl2">
							<div class="formLabel grid_4"><label for="06"><?php echo(__('Email address:')) ?></label></div>
							<div class="formField grid_3">
                                <input id="06" type='text' value='<?php echo($email) ?>' name='email' maxlength='100' />
                                <input type='hidden' name='site_url' value='<?php echo($site_url) ?>' /></div>
							<div class="clear"></div>
						</div>

						<br /><hr /><br />
						<div class="center">
							<input type='hidden' name='step' value='6' />
							<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
                            <input type='hidden' name='site_url' value='<?php echo($site_url) ?>' />
							<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong><?php echo(__('Next')) ?> &raquo;</strong>
									</strong>
								</strong>
							</a>
						</div>

						<?php
						}
						else
						{
						?>
							<div class="info"><?php echo(__('Please enter your MySQL database access settings.')) ?></div><br />
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="01"><?php echo(__('Database Hostname:')) ?></label></div>
									<div class="formField grid_3"><input id="01" type='text' value='<?php echo($db_host) ?>' name='db_host' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="01"><?php echo(__('Database Port:')) ?></label></div>
									<div class="formField grid_3"><input id="01" type='text' value='<?php echo($db_port) ?>' name='db_port' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="02"><?php echo(__('Database Username:')) ?></label></div>
									<div class="formField grid_3"><input id="02" type='text' value='<?php echo($db_user) ?>' name='db_user' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="03"><?php echo(__('Database Password:')) ?></label></div>
									<div class="formField grid_3"><input id="03" type='password' name='db_pass' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="04"><?php echo(__('Database Name:')) ?></label></div>
									<div class="formField grid_3"><input id="04" type='text' value='<?php echo($db_name) ?>' name='db_name' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="05"><?php echo(__('Table Prefix:')) ?></label></div>
									<div class="formField grid_3"><input id="05" type='text' value='<?php echo($db_prefix) ?>' name='db_prefix' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="06"><?php echo(__('Cookie Prefix:')) ?></label></div>
									<div class="formField grid_3"><input id="06" type='text' value='<?php echo($cookie_prefix) ?>' name='cookie_prefix' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl2">
									<div class="formLabel grid_4"><label for="06"><?php echo(__('Cache Prefix:')) ?></label></div>
									<div class="formField grid_3"><input id="06" type='text' value='<?php echo($cache_prefix) ?>' name='cache_prefix' /></div>
									<div class="clear"></div>
								</div>
								<div class="tbl1">
									<div class="formLabel grid_4"><label for="06"><?php echo(__('URL:')) ?></label></div>
									<div class="formField grid_3"><input id="06" type='text' value='<?php echo($site_url) ?>' name='site_url' /></div>
									<div class="clear"></div>
								</div>

								<br /><hr /><br />
								<div class="center">
									<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
									<input type='hidden' name='step' value='3' />
									<input type='hidden' name='db_host' value='<?php echo($db_host) ?>' />
									<input type='hidden' name='db_port' value='<?php echo($db_port) ?>' />
									<input type='hidden' name='db_user' value='<?php echo($db_user) ?>' />
									<input type='hidden' name='db_name' value='<?php echo($db_name) ?>' />
									<input type='hidden' name='db_prefix' value='<?php echo($db_prefix) ?>' />
									<input type='hidden' name='cookie_prefix' value='<?php echo($cookie_prefix) ?>' />
									<input type='hidden' name='cache_prefix' value='<?php echo($cache_prefix) ?>' />
									<input type='hidden' name='db_error' value='<?php echo($db_error) ?>' />
									<input type='hidden' name='msg' value='<?php echo($msg) ?>' />
									<input type='hidden' name='email' value='<?php echo($site_url) ?>' />
									<a id="SendForm_This" class="SendButton" style="width:100px;margin:0 auto;">
										<strong class="o">
											<strong class="m">
												<strong><?php echo(__('Next')) ?> &raquo;</strong>
											</strong>
										</strong>
									</a>
								</div>
								<div class="clear"></div>
						<?php
						}
					}

					if (isset($_POST['step']) && $_POST['step'] == "6") {

						if (file_exists('..'.DS.'cache'.DS))
						{
							$_system->clearCache(NULL, array(), '..'.DS.'cache'.DS);
						}

						$dbconnect = dbconnect($_dbconfig['host'].':'.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], $_dbconfig['database']);

						$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
						$password1 = (isset($_POST['password1']) ? stripinput(trim($_POST['password1'])) : "");
						$password2 = (isset($_POST['password2']) ? stripinput(trim($_POST['password2'])) : "");
						$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
                        $site_url = (isset($_POST['site_url']) ? $_POST['site_url'] : "");

						$error = ""; $error_pass = "0"; $error_name = "0"; $error_mail = "0";

						if ($username == "") {
							$error .= __('User name field can not be left empty.')."<br /><br />\n";
							$error_name = "1";
						} elseif (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
							$error .= __('User name contains invalid characters.')."<br /><br />\n";
							$error_name = "1";
						}

						if ($password1 == "" || $password2 == "") {
							$error .= __('Login password fields can not be left empty')."<br /><br />\n";
							$error_pass = "1";
						} elseif (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
							if ($password1 != $password2) {
								$error .= __('Your two login passwords do not match.')."<br /><br />\n";
								$error_pass = "1";
							}
						} else {
							$error .= __('Invalid login password, please use alpha numeric characters only.<br />Password must be a minimum of 6 characters long.')."<br /><br />\n";
						}
						if ($email == "") {
							$error .= __('Email field can not be left empty.')."<br /><br />\n";
							$error_mail = "1";
						} elseif (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
							$error .= __('Your email address does not appear to be valid.')."<br /><br />\n";
							$error_mail = "1";
						}

						$rows = rowCount($_dbconfig['prefix'].'users', '`id`');

						if ($error == "") {
							if ($rows == 0) {
								include_once "create_settings.php";
							}

							if (function_exists("chmod")) { @chmod('..'.DS.'config.php', 0644); }
							?>

							<div class="center"><?php echo(__('Setup complete')) ?></div><br />
							<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
                            <input type='hidden' name='site_url' value='<?php echo($site_url) ?>' />
							<input type='hidden' name='step' value='7' />
							<br /><hr /><br />
							<a id="SendForm_This" class="SendButton" style="width:110px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong><?php echo(__('Finish')) ?> &raquo;</strong>
									</strong>
								</strong>
							</a>

						<?php } elseif ($rows == 0) { ?>

							<div class="error"><?php echo(__('Your user settings are not correct:')) ?></div><br />
							<?php echo($error) ?>
							<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
							<input type='hidden' name='error_pass' value='<?php echo($error_pass) ?>' />
							<input type='hidden' name='error_name' value='<?php echo($error_name) ?>' />
							<input type='hidden' name='error_mail' value='<?php echo($error_mail) ?>' />
							<input type='hidden' name='username' value='<?php echo($username) ?>' />
							<input type='hidden' name='step' value='5' />
							<br /><hr /><br />
							<a id="SendForm_This" class="CancelButton" style="width:100px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong>&laquo; <?php echo(__('Back')) ?></strong>
									</strong>
								</strong>
							</a>

						<?php } else { ?>

							<div class="center"><?php echo(__('Setup complete')) ?></div><br />
							<input type='hidden' name='localeset' value='<?php echo(stripinput($_POST['localeset'])) ?>' />
							<input type='hidden' name='step' value='7' />
							<br /><hr /><br />
							<a id="SendForm_This" class="SendButton" style="width:110px;margin:0 auto;">
								<strong class="o">
									<strong class="m">
										<strong><?php echo(__('Finish')) ?> &raquo;</strong>
									</strong>
								</strong>
							</a>

						<?php }
					}
					?>
				</form>
				<div class="clear"></div>
			</div>
		</div><div class='cfl'><div class='cfr'><div class='cfc'></div></div></div></div>
	</div>
</body>
</html>


<?php
// mySQL database functions
function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	global $db_connect;

	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	dbquery("SET NAMES utf8");
	if (!$db_connect) {
		return false;
	} else {
		return true;
	}
}

function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbrows($query) {
	$result = @mysql_num_rows($query);
	return $result;
}

function rowCount($table, $field, $conditions = '')
{
	$cond = ($conditions ? ' WHERE '.$conditions : '');
	$result = mysql_query("SELECT Count(".$field.") FROM ".$table.$cond);

	return mysql_result($result, 0);
}

/**
 * Dodawanie rekordów
 *
 *@copyright Clear-PHP.com
 *
 * Przykład użycia:
 * 		$result = $sql_manager->insert('test', array('fgfg' => '34', 'baza' => 'sdsd'));
 *
 * @param string $table, array $fields
 * @return number of modified records
 */

function insert($table = null, $fields = null)
{
	// Sprawdzanie, czy parametry nie zostały pominięte, a zmienna $fields jest tablicą
	if (is_null($table) || is_null($fields) || !is_array($fields))
	{
		return false;
	}

	$keys = implode('`, `', array_keys($fields));
	$values = implode("', '", array_values($fields));

	return dbquery("INSERT INTO ".$table." (`".$keys."`) VALUES ('".$values."')");

} // end of insert();


// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Validate numeric input
/*
function isnum($value) {
	if (!is_array($value)) {
		return (preg_match("/^[0-9]+$/", $value));
	} else {
		return false;
	}
}
*/

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter);
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = "") {
	$res = "";
	for ($i=0; $i < count($files); $i++) {
		$sel = ($selected == $files[$i] ? " selected='selected'" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}

function detectBrowserLanguage()
{
	$langs = array(
		'cs' => 'Czech',
		'en' => 'English',
		'pl' => 'Polish',
	);

	$var = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$var = explode(',', $var[0]);

	$current = null;
	foreach($var as $data)
	{
		if(isset($langs[$data]))
		{
			$current = $langs[$data];
			break;
		}
	}

	if(is_null($current))
	{
		$current = 'English';
	}

	return $current;
}

if (isset($db_connect) && $db_connect != false) { mysql_close($db_connect); }
?>