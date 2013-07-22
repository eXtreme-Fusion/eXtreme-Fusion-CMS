<?php
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
| Author: Paul Buek (Muscapaul)
| Author: Hans Kristian Flaatten (Starefossen)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	$_locale->load('users');

    if ( ! $_user->hasPermission('admin.users'))
    {
        throw new userException(__('Access denied'));
    }

	$_fav->setFavByLink('users.php', $_user->get('id'));

    $_tpl = new Iframe;

	$_tpl->assign('page', $_request->get('page')->show());

	require_once DIR_CLASS.'Mailer.php';
	$_mail = new Mailer($_sett->get('smtp_username'), $_sett->get('smtp_password'), $_sett->get('smtp_host'));

	if ($_request->get('page')->show() === 'users')
	{
		if ($_request->get('user')->show() && $_request->get('user')->isNum())
		{
			$rows = $_pdo->getRow('SELECT `id` FROM [users] WHERE `id` = '.$_request->get('user')->show());
			if ($rows)
			{
				$action = '';

				if ($_request->get('act')->show() === 'error')
				{
					$_tpl->printMessage('error', __('This action can not be done for user with ID 1.'));
				}
				elseif ($_request->get('act')->show() === 'ok')
				{
					$_tpl->printMessage('valid', __('Action was done successfully.'));
				}
				elseif ($_request->get('act')->show() === 'error1')
				{
					$_tpl->printMessage('error', __('Error! User name and e-mail fields are emtpy.'));
				}
				elseif ($_request->get('act')->show() === 'error2')
				{
					$_tpl->printMessage('error', __('User name contains incorrect characters.'));
				}
				elseif ($_request->get('act')->show() === 'error3')
				{
					$_tpl->printMessage('error', __('Passwords do not match.'));
				}
				elseif ($_request->get('act')->show() === 'error4')
				{
					$_tpl->printMessage('error', $_user->getAvatarErrorByCode($_request->get('code')->show()));
				}
				elseif ($_request->get('act')->show() === 'error5')
				{
					$_tpl->printMessage('error', __('Incorrect e-mail address.'));
				}
				elseif ($_request->get('act')->show() === 'error6')
				{
					$_tpl->printMessage('error', __('This user name is already in use.'));
				}
				elseif ($_request->get('act')->show() === 'error7')
				{
					$_tpl->printMessage('error', __('This e-mail address is already in use.'));
				}

				if ($_request->get('edit')->show() === '')
				{
					$action = 'edit';
					if ($_request->post('save')->show())
					{
						$error = FALSE;
						if ($_user->get('id') !== '1')
						{
							if ($_request->get('user')->show() === '1')
							{
								$_tpl->printMessage('error', __('Error! This action can not be done for user with ID 1.'));
								$error = TRUE;
								//$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error'), 'edit');
							}
						}

						if (! $_request->post('username')->show() || ! $_request->post('user_email')->show())
						{
							$_tpl->printMessage('error', __('Error! Some required fields are empty.'));
							$error = TRUE;
							//$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error1'), 'edit');
						}

						$username = trim($_request->post('username')->show());

						// Sprawdzanie, czy zmieniono nazwę użytkownika
						if ($username !== $_user->getByID($_request->get('user')->show(), 'username'))
						{
							// Sprawdzanie, czy nowa nazwa użytkownika jest nieprawidłowa
							if ( ! $_user->validLogin($username))
							{
								$_tpl->printMessage('error', __('Error! The username contains incorrect characters.'));
								$error = TRUE;
								//$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error2'), 'edit');
							}
							else
							{
								// Sprawdzanie, czy nowa nazwa użytkownika jest dostępna
								if ($_user->newName($username))
								{
									$change_username = TRUE;
								}
								else
								{
									$_tpl->printMessage('error', __('Error! The username is already in use.'));
									$error = TRUE;
								}
							}
						}

						if ($_request->post('user_email')->show() !== $_user->getByID($_request->get('user')->show(), 'email'))
						{
							if ( ! $_user->validEmail($_request->post('user_email')->show()))
							{
								$_tpl->printMessage('error', __('Error! Incorrect e-mail address.'));
								$error = TRUE;
								//$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error5'));
							}
							else
							{
								// Sprawdzanie dostępności nazwy użytkownika
								if ($_user->newEmail($_request->post('user_email')->show()))
								{
									$change_email = TRUE;
								}
								else
								{
									// TODO: The username
									$_tpl->printMessage('error', __('Error! This email address is already in use.'));
									$error = TRUE;
								}
							}
						}

						if ($_request->post('user_pass')->show() !== '' || $_request->post('user_pass2')->show() !== '')
						{
							if ($_user->validPassword($_request->post('user_pass')->show(), $_request->post('user_pass2')->show()))
							{
								$change_password = TRUE;
								//$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error3'));
							}
							else
							{
								$_tpl->printMessage('error', __('Error! Passwords do not match.'));
								$error = TRUE;
							}
						}

						if (! $error && $_request->upload('avatar'))
						{
							if ( ! $_user->saveNewAvatar($_request->get('user')->show(), $_request->files('avatar')->show()))
							{
								$_tpl->printMessage('error', $_user->getAvatarErrorByCode($_user->getAvatarErrorCode()));
								$error = TRUE;
								//$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error4', 'code' => $_user->getAvatarErrorCode()), 'edit');
							}
						}

						// ! $error są rodzielone, dlatego by w przypadku błędu
						// przy aktualizacji zdjęcia profilowego, nie wykonywać dalszego kodu.
						if (! $error)
						{
							if (isset($change_username))
							{
								$_user->newName($username, $_request->get('user')->show());
							}

							if (isset($change_email))
							{
								$_user->newEmail($_request->post('user_email')->show(), $_request->get('user')->show());
							}

							if (isset($change_password))
							{
								$_user->changePass($_request->get('user')->show(), $_request->post('user_pass')->show(), $_user->getByID($_request->get('user')->show(), 'password'), $_request->post('user_pass2')->show());
							}

							$role = $_request->post('roles')->show();
							asort($role);
							$_user->setRoles($role, $_request->post('role')->show(), $_request->get('user')->show());

							$_fields = array();

							if ($fields = $_pdo->getData('SELECT * FROM [user_fields]'))
							{
								$data = $_request->post('data')->show();
								foreach($fields as $field)
								{
									$key   = $field['index'];
									$value = $data[$key];

									$_fields[$key] = $value;
								}
							}

							$_user->update($_request->get('user')->show(), array(
								'hide_email' => $_request->post('hide_email')->isNum(TRUE),
								'theme'      => $_request->post('theme_set')->show(),
								'lang'       => $_request->post('language')->show(),
							), $_fields);

							$_system->clearCache('profiles');
							$_log->insertSuccess('edit', __('User :user has been edited.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
							$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
						}
						else
						{
							$_tpl->assign('user', array(
								'username' => $username,
								'email' => $_request->post('user_email')->show(),
								'hide_email' => $_request->post('hide_email')->show(),
								'theme_set' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'themes', array('templates'), TRUE, 'folders'), $_request->post('theme_set')->show()),
							));

							$_tpl->assign('locale_set', $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $_request->post('language')->show()));

							$_tpl->assign('all_groups', $_tpl->getMultiSelect($_user->getViewGroups(), $_request->post('roles')->show()), FALSE);
							$_tpl->assign('insight_groups', $_tpl->createSelectOpts($_user->getViewGroups(), intval($_request->post('role')->show()), TRUE, FALSE), TRUE);

							// Dodatkowe pola
							$data = $_user->getCustomData($_request->get('user')->show(), $_request->post('data')->show());
						}
					}
					else
					{
						$data = $_pdo->getRow('SELECT * FROM [users] WHERE `id` = '.$_request->get('user')->show());

						$user = array(
							'username' => $data['username'],
							'email' => $data['email'],
							'hide_email' => $data['hide_email'],
							'theme' => $data['theme'],
							'avatar' => $data['avatar'],
						);

						$_tpl->assign('all_groups', $_tpl->getMultiSelect($_user->getViewGroups(), $_user->convertRoles($data['roles'])), FALSE);
						$_tpl->assign('insight_groups', $_tpl->createSelectOpts($_user->getViewGroups(), intval($data['role']), TRUE, FALSE), TRUE);

						$_tpl->assignGroup(array(
							'user' => $user,
							'theme_set' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'themes', array('templates'), TRUE, 'folders'), $data['theme']),
							'locale_set' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $_user->get('lang')),
						));

						// Dodatkowe pola
						$data = $_user->getCustomData($_request->get('user')->show());
					}

					$_tpl->assignGroup(array(
						'avatar_filesize' => $_sett->get('avatar_filesize')/1024,
						'avatar_height' => $_sett->get('avatar_height'),
						'avatar_width' => $_sett->get('avatar_width')
					));
					$_tpl->assign('fields', $data['fields']);
					$_tpl->assign('cats', $data['categories']);

				}
				elseif ($_request->get('suspend')->show() === '')
				{
					$action = 'suspend';
					if ($_request->get('user')->show() !== '1')
					{
						if ($_user->getByID($_request->get('user')->show(), 'status') === '3')
						{
							$_pdo->exec('UPDATE [users] SET `status` = 0 WHERE `id` = :id',
								array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
							);

							if ($_sett->get('email_verification'))
							{
								$message = __('Welcome!').'<br /><br />'.
									__('Administrator :admin unlocked your account on website :portal.', array(':admin' => $_user->get('username'), ':portal' => $_sett->get('site_name'))).'<br />
									<br />
									<strong>'.__('Greetings').'</strong><br />
									<em>'.$_sett->get('site_username').'</em><br />
									<br />
									<hr />'.__('This message was sent automatically. Please do not respond.');

								$_mail->send($_user->getByID($_request->get('user')->show(), 'email'), $_sett->get('contact_email'), __('Account suspend'), $message);
							}

							$_system->clearCache('profiles');
							$_log->insertSuccess('suspend', __('User account :user has been unlocked.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
							$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
						}
						else
						{
							$_pdo->exec('UPDATE [users] SET `status` = 3 WHERE `id` = :id',
								array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
							);

							if ($_sett->get('email_verification'))
							{
								$message = __('Welcome!').'<br /><br />'.
									__('Administrator :admin suspended your account on website :portal.', array(':admin' => $_user->get('username'), ':portal' => $_sett->get('site_name'))).'<br />
									<br />'.__('If you do not know why your account has been suspended please contact with administrator (:mail) or respond to this e-mail.', array(':mail' => $_user->get('email'))).'<br />
									<br />
									<strong>'.__('Greetings').'</strong><br />
									<em>'.$_sett->get('site_username').'</em><br />
									<br />
									<hr />'.__('This message was sent automatically.');

								$_mail->send($_user->getByID($_request->get('user')->show(), 'email'), $_sett->get('contact_email'), __('Account suspend'), $message);
							}

							$_system->clearCache('profiles');
							$_log->insertSuccess('suspend', __('User account :user has been suspended.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
							$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
						}
					}

					$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error'));
				}
				elseif ($_request->get('unactive')->show() === '')
				{
					$action = 'unactive';
					if ($_request->get('user')->show() !== '1')
					{
						if ($_user->getByID($_request->get('user')->show(), 'status') === '2')
						{
							$_pdo->exec('UPDATE [users] SET `status` = 0 WHERE `id` = :id',
								array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
							);

							if ($_sett->get('email_verification'))
							{
								$message = __('Welcome!').'<br /><br />'.
									__('Administrator :admin has activated your account on website :portal.', array(':admin' => $_user->get('username'), ':portal' => $_sett->get('site_name'))).'<br />
									<br />
									<strong>'.__('Greetings.').'</strong><br />
									<em>'.$_sett->get('site_username').'</em><br />
									<br />
									<hr />'.__('Message was sent automatically. Please do not respond.');

								$_mail->send($_user->getByID($_request->get('user')->show(), 'email'), $_sett->get('contact_email'), __('Account suspend'), $message);
							}

							$_system->clearCache('profiles');
							$_log->insertSuccess('unactive', __('User account :user has been activated.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
							$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
						}
						else
						{
							$_pdo->exec('UPDATE [users] SET `status` = 2 WHERE `id` = :id',
								array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
							);

							if ($_sett->get('email_verification'))
							{
								$message = __('Welcome!').'<br /><br />'.
									__('Administrator :admin has deactivated your account on website :portal.', array(':admin' => $_user->get('username'), ':portal' => $_sett->get('site_name'))).'<br />
									<br />'.__('If you do not know why your account has been deactivated please contact with administrator (:mail) or respond to this e-mail.', array(':mail' => $_user->get('email'))).'<br />
									<br />
									<strong>'.__('Greetings').'</strong><br />
									<em>'.$_sett->get('site_username').'</em><br />
									<br />
									<hr />'.__('This message was sent automaticaly.');

								$_mail->send($_user->getByID($_request->get('user')->show(), 'email'), $_sett->get('contact_email'), __('Account suspend'), $message);
							}

							$_system->clearCache('profiles');
							$_log->insertSuccess('unactive', __('User account :user has been deactivated.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
							$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
						}
					}
					$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error'));
				}
				elseif ($_request->get('active')->show() === '')
				{
					$action = 'active';
					if ($_request->get('user')->show() !== '1')
					{
						$_pdo->exec('UPDATE [users] SET `status` = 0 WHERE `id` = :id',
							array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
						);

						$_system->clearCache('profiles');
						$_log->insertSuccess('active', __('User account :user has been activeted.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
						$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
					}
					$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error'));
				}
				elseif ($_request->get('hide')->show() === '')
				{
					$action = 'hide';
					if ($_request->get('user')->show() !== '1')
					{
						if ($_user->getByID($_request->get('user')->show(), 'status') === '4')
						{
							$_pdo->exec('UPDATE [users] SET `status` = 0 WHERE `id` = :id',
								array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
							);

							$_system->clearCache('profiles');
							$_log->insertSuccess('hide', __('User account :user has been made visible.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
							$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
						}

						$_pdo->exec('UPDATE [users] SET `status` = 4 WHERE `id` = :id',
							array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
						);

						$_system->clearCache('profiles');
						$_log->insertSuccess('hide', __('User account :user has been hidden.', array(':user' => $_user->getByID($_request->get('user')->show(), 'username'))));
						$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'ok'));
					}
					$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error'));
				}
				elseif ($_request->get('delete')->show() === '')
				{
					$action = 'delete';
					if ($_request->get('user')->show() !== '1')
					{
						$user = $_user->getByID($_request->get('user')->show(), 'username');

						if ($_sett->get('email_verification'))
						{
							$message = __('Welcome!').'<br /><br />'.
								__('Administrator :admin has deleted your account on website :portal.', array(':admin' => $_user->get('username'), ':portal' => $_sett->get('site_name'))).'<br />
								<br />'.__('If you do not know why your account has been deleted please contact with administrator (:mail) or repond to this e-mail.', array(':mail' => $_user->get('email'))).'<br />
								<br />
								<strong>'.__('Greetings').'</strong><br />
								<em>'.$_sett->get('site_username').'</em><br />
								<br />
								<hr />'.__('This message has been sent autmatically.');

							$_mail->send($_user->getByID($_request->get('user')->show(), 'email'), $_sett->get('contact_email'), __('Account suspend'), $message);
						}

						$data = $_pdo->getRow('SELECT `avatar` FROM [users] WHERE `id` = '.$_request->get('user')->show());
						if ($data['avatar'])
						{
							$count = $_user->delAvatar($data['avatar'], $_request->get('user')->show());
						}

						$count = $_pdo->exec('DELETE FROM [users] WHERE `id` = '.$_request->get('user')->show());
						$count = $_pdo->exec('DELETE FROM [users_data] WHERE `user_id` = '.$_request->get('user')->show());

						$_system->clearCache('profiles');
						$_log->insertSuccess('delete', __('User account :user has been deleted.', array(':user' => $user)));
						$_request->redirect(FILE_PATH, array('page' => 'users', 'act' => 'delete'));
					}
					$_request->redirect(FILE_PATH, array('page' => 'users', 'user' => $_request->get('user')->show(), 'act' => 'error'));
				}
				else
				{
					$data = $_pdo->getRow('SELECT * FROM [users] WHERE `id` = :id',
						array(':id', $_request->get('user')->show(), PDO::PARAM_INT)
					);

					$info = __('On this page there are displayed all user :user data.', array(':user' => $data['username']));

					if ($data)
					{
						$users = array(
							'id' => $data['id'],
							'username' => $_user->getUsername($data['id']),
							'email' => $data['email'],
							'role' => $_user->getRoleName($data['role']),
							'roles' => implode($_user->getUserRolesTitle($data['id']), ', '),
							'avatar' => $_user->getAvatarAddr($data['id']),
							'joined' => HELP::showDate('shortdate', $data['joined']),
							'status' => $data['status'],
							'visit' => ($data['lastvisit'] != 0 ? HELP::showDate('shortdate', $data['lastvisit']) : __('Nie był na stronie')),
							'theme' => $data['theme']
						);
					}

					// Dodatkowe pola
					$data = $_user->getCustomData($_request->get('user')->show());
					$_tpl->assign('fields', $data['fields']);
					$_tpl->assign('cats', $data['categories']);

					$_tpl->assignGroup(array(
						'id' => $_user->get('id'),
						'account' => $users,
						'info' => $info,
						'action' => $action
					));
				}

				$_tpl->assignGroup(array(
					'id' => $_user->get('id'),
					'action' => $action
				));
			}
			else
			{
				throw new userException(__('Error! User with ID :id can not be found.', array(':id' => $_request->get('user')->show())));
			}
		}
		// Lista użytkowników
		else
		{
			if ($_request->get('act')->show() === 'delete')
			{
				$_tpl->printMessage('valid', __('User account has been deleted.'));
			}

			if ($_request->get('status')->show() === 'suspend')
			{
				$info = __('Admin banned');
				$status = 3;
			}
			elseif ($_request->get('status')->show() === 'required')
			{
				$info = __('Not activated by link');
				$status = 1;
			}
			elseif ($_request->get('status')->show() === 'unactive')
			{
				$info = __('Not activated by admin');
				$status = 2;
			}
			elseif ($_request->get('status')->show() === 'hidden')
			{
				$info = __('Hidden users');
				$status = 4;
			}
			else
			{
				$info = __('On this page there are displayed active users and administrators.');
				$status = 0;
			}

			$current_page = intval($_request->get('current')->show() ? $_request->get('current')->show() : 1);

			$rows = $_pdo->getMatchRowsCount('SELECT * FROM [users] WHERE `status` = :status ORDER BY `username` ASC',
				array(':status', $status, PDO::PARAM_INT)
			);

			$user = array();
			if ($rows)
			{
				$items_per_page = intval($_sett->get('users_per_page'));

				$query = $_pdo->getData('SELECT * FROM [users] WHERE `status` = :status ORDER BY `username` ASC LIMIT :rowstart,:items_per_page',
					array(
						array(':status', $status, PDO::PARAM_INT),
						array(':rowstart', Paging::getRowStart($current_page, $items_per_page), PDO::PARAM_INT),
						array(':items_per_page', $items_per_page, PDO::PARAM_INT)
					)
				);

				if ($_pdo->getRowsCount($query))
				{
					$i = 0;
					foreach($query as $data)
					{
						$user[] = array(
							'row_color' => ($i % 2 == 0 ? 'tbl1' : 'tbl2'),
							'id'   => $data['id'],
							'username' => $_user->getUsername($data['id']),
							'roles' => $_user->getRoleName($data['role']),
						);
					}
				}

				$ec->paging->setPagesCount($rows, $current_page, $items_per_page);
				$ec->pageNav->get($ec->pageNav->create($_tpl, 10), 'page_nav', DIR_ADMIN_TEMPLATES.'paging'.DS);
			}

			$_tpl->assignGroup(array(
				'user' => $user,
				'info' => $info
			));
		}
	}
	elseif ($_request->get('page')->show() === 'add')
	{
		// Errory
		/*if ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error')
		{
			//$_tpl->printMessage('error', __('Error! There are empty fields in the form.'));
		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error1')
		{
			$_tpl->printMessage('error', __('Error! User name contains incorrect characters.'));
		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error2')
		{
			$_tpl->printMessage('error', __('Error! Passwords do not match.'));
		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error3')
		{

		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error4')
		{
			$_tpl->printMessage('error', );
		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error5')
		{
			$_tpl->printMessage('error', __('Error! This user name is already in use.'));
		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error6')
		{
			$_tpl->printMessage('error', __('Error! This e-mail address is already in use.'));
		}
		elseif ($_request->get('page')->show() === 'add' && $_request->get('act')->show() === 'error7')
		{
			$_tpl->printMessage('error', __('Error! This e-mail address is banned.'));
		}*/

		if ($_request->post('create_account')->show())
		{
			$username = $_request->post('username')->trim();

			if ( ! $_user->validLogin($username))
			{
				$_tpl->printMessage('error', __('Error! User name contains incorrect characters.'));
				$error = TRUE;
				//$_request->redirect(FILE_PATH, array('page' => 'add', 'act' => 'error1'));
			}
			elseif ( ! $_user->newName($username))
			{
				$_tpl->printMessage('error', __('Error! This user name is already in use.'));
				$error = TRUE;
				//$_request->redirect(FILE_PATH, array('page' => 'add', 'act' => 'error5'));
			}

			if ( ! $_user->validPassword($_request->post('user_pass')->show(), $_request->post('user_pass2')->show()))
			{
				$_tpl->printMessage('error', __('Error! Passwords do not match.'));
				$error = TRUE;
				//$_request->redirect(FILE_PATH, array('page' => 'add', 'act' => 'error2'));
			}

			if ( ! $_user->validEmail($_request->post('user_email')->show()))
			{
				$_tpl->printMessage('error', __('Error! Incorrect e-mail address.'));
				$error = TRUE;
				//$_request->redirect(FILE_PATH, array('page' => 'add', 'act' => 'error4'));
			}
			elseif ( ! $_user->newEmail($_request->post('user_email')->show()))
			{
				$_tpl->printMessage('error', __('Error! This e-mail address is already in use.'));
				$error = TRUE;
				//$_request->redirect(FILE_PATH, array('page' => 'add', 'act' => 'error6'));
			}

			$count = $_pdo->getMatchRowsCount('SELECT `id` FROM [blacklist] WHERE `email` = :email OR `email` = :domain',
				array(
					array(':email', $_request->post('user_email')->show(), PDO::PARAM_STR),
					array(':domain', substr(strrchr($_request->post('user_email')->show(), "@"), 1), PDO::PARAM_STR)
				)
			);

			if ($count)
			{
				$_tpl->printMessage('error', __('Error! This e-mail address is banned.'));
				$error = TRUE;
				//$_request->redirect(FILE_PATH, array('page' => 'add', 'act' => 'error7'));
			}

			if (! $error)
			{
				$salt = substr(sha512(uniqid(rand(), true)), 0, 5);
				$password = sha512($salt.'^'.$_request->post('user_pass')->show());

				$status = 0; $valid = '';
				if ( ! $_request->post('active')->show())
				{
					if ($_sett->get('email_verification') === '1')
					{
						$status = 1;
						$valid = md5(uniqid(time()));
					}
					elseif ($_sett->get('admin_activation') === '1')
					{
						$status = 2;
						$valid = md5(uniqid(time()));
					}
				}

				$role = $_request->post('roles')->show(); asort ($role);

				$count = $_pdo->exec("INSERT INTO [users] (`username`, `password`, `salt`, `link`, `email`, `hide_email`, `valid`, `valid_code`, `offset`, `avatar`, `joined`, `lastvisit`, `ip`, `status`, `theme`, `role`, `roles`) VALUES (:username, :password, :salt, :link, :email, :hidemail, '1', :valid, '0', '', '".time()."', '0', '0.0.0.0', :status, 'Default', :role, :roles)",
					array(
						array(':username', $username, PDO::PARAM_STR),
						array(':password', $password, PDO::PARAM_STR),
						array(':salt', $salt, PDO::PARAM_STR),
						array(':link', HELP::Title2Link($username), PDO::PARAM_STR),
						array(':email', $_request->post('user_email')->show(), PDO::PARAM_STR),
						array(':hidemail', $_request->post('hideemail')->show(), PDO::PARAM_INT),
						array(':valid', $valid, PDO::PARAM_STR),
						array(':status', $status, PDO::PARAM_INT),
						array(':role', $_request->post('role')->show(), PDO::PARAM_INT),
						array(':roles', serialize($role), PDO::PARAM_STR)
					)
				);

				$last_user_id = $_pdo->getField('SELECT `id` FROM [users] WHERE `username` = :user', array(':user', $username, PDO::PARAM_STR));

				$_fields = array();
				if ($fields = $_pdo->getData('SELECT * FROM [user_fields]'))
				{
					$data = $_request->post('data')->show();
					foreach($fields as $field)
					{
						$key   = $field['index'];
						$value = $data[$key];

						$_fields[$key] = $value;
					}
				}

				$_user->update($last_user_id, array(), $_fields);

				if ($_request->post('active')->show() !== 'yes' && $_sett->get('email_verification'))
				{
					$message = __('Welcome!').'<br /><br />'.
						__('Administrator has created an account with this e-mail on website :portal.', array(':portal' => $_sett->get('site_name'))).'<br />
						<br />'.__('Access data for this account:').'<br />
						<strong>'.__('Login:').'</strong> '.$username.'<br />
						<strong>'.__('Password:').'</strong> '.$_request->post('user_pass')->show().'<br />
						<br />'.__('To get full access to this website you have to activate your account with link below:').'<br />
						<br />
						<a href="'.$_url->path(array('controller' => 'register', 'action' => 'active', $valid)).'">'.$_url->path(array('controller' => 'register', 'action' => 'active', $valid)).'</a><br />
						<br />
						<strong>'.__('Greetings').'</strong><br />
						<em>'.$_sett->get('site_username').'</em><br />
						<br />
						<hr />'.__('This message was sent automatically. Please do not respond.');

					$_mail->send($_request->post('user_email')->show(), $_sett->get('contact_email'), __('Account activation'), $message);
				}

				$_log->insertSuccess('add', $_user->get('username').__(' has created user account ').$username);
				$_request->redirect(FILE_PATH, array('page' => 'users', 'act' => 'add', 'status' => 'ok'));
			}
			else
			{
				$_tpl->assign('user', array(
					'username' => $username,
					'email' => $_request->post('user_email')->show(),
					'hide_email' => $_request->post('hide_email')->show(),
					'active' => $_request->post('active')->show()
				));


				$_tpl->assign('all_groups', $_tpl->getMultiSelect($_user->getViewGroups(), $_request->post('roles')->show()), FALSE);
				$_tpl->assign('insight_groups', $_tpl->createSelectOpts($_user->getViewGroups(), intval($_request->post('role')->show()), TRUE, TRUE), TRUE);

				// Dodatkowe pola
				$data = $_user->getCustomData(NULL, $_request->post('data')->show());
			}
		}
		else
		{
			// Dodatkowe pola
			$data = $_user->getCustomData();
			$_tpl->assign('user', array());
			$_tpl->assign('all_groups', $_tpl->getMultiSelect($_user->getViewGroups(), NULL), FALSE);
			$_tpl->assign('insight_groups', $_tpl->createSelectOpts($_user->getViewGroups(), NULL, TRUE));
		}

		$_tpl->assign('fields', $data['fields']);
		$_tpl->assign('cats', $data['categories']);

		if ($_sett->get('email_verification') === '1' || $_sett->get('admin_activation') === '1')
		{
			$_tpl->assign('active', TRUE);
		}
	}
	elseif ($_request->get('page')->show() === 'mail')
	{
		if ($_request->get('page')->show() === 'mail' && $_request->get('act')->show() === 'send')
		{
			$_tpl->printMessage('valid', __('E-mail has been sent to the users.'));
		}
		elseif ($_request->get('page')->show() === 'mail' && $_request->get('act')->show() === 'error')
		{
			$_tpl->printMessage('error', __('Error! E-mail has not been sent to the users.'));
		}

		if ($_request->post('send')->show())
		{
			$subject = ($_request->post('subject')->show() ? $_request->post('subject')->filters('trim', 'strip') : __('Message from website :portal.', array(':portal' => $_sett->get('site_name'))));
			$message = ($_request->post('email_message')->show() ? $_request->post('email_message')->show() : __('E-mail message'));

			if($_request->post('hide')->isNum() == 1)
			{
				$count = $_mail->sendBcc($_request->post('mail')->show(), $_sett->get('contact_email'), $subject, $message);
			}
			else
			{
				$count = $_mail->sendCc($_request->post('mail')->show(), $_sett->get('contact_email'), $subject, $message);
			}
			if ($count)
			{
				$_log->insertSuccess('mail', __('E-mail has been sent to the users.'));
				$_request->redirect(FILE_PATH, array('page' => 'mail', 'act' => 'send'));
			}
			$_log->insertFail('mail', __('Error! E-mail has not been sent to the users.'));
			$_request->redirect(FILE_PATH, array('page' => 'mail', 'act' => 'error'));
		}
	}


	$_tpl->assign('status', $_request->get('status', NULL)->show() === NULL && $_request->get('user', NULL)->show() === NULL ?'active' : $_request->get('status')->show());

  $_tpl->template('users');
}
catch(optException $exception)
{
	optErrorHandler($exception);
}
catch(systemException $exception)
{
	systemErrorHandler($exception);
}
catch(argumentException $exception)
{
	argumentErrorHandler($exception);
}
catch(userException $exception)
{
	userErrorHandler($exception);
}
catch(PDOException $exception)
{
    PDOErrorHandler($exception);
}
