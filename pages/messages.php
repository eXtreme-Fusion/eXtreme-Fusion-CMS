<?php defined('EF5_SYSTEM') || exit;
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
| 
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
/*
Do zrobienie
Sprawdzanie czy po wpisaniu w polu nazwa użytkownika jest wpisana nazwa użytkownika istniejącego konta użytkownica
Bo w przeciwnym wypadku powinien być zablokowany klawisz wyślij, po wpisaniu pierwszej lepszej nazwy i kliknięciu 
wyślij wyskakuje informacja o podejrzewanym ataku XSS
*/
$_user->onlyForUsers($_route);

$_head->set('<meta name="robots" content="noindex">');

$_locale->load('messages');

//Initiation of Smiley & BBCode parser
$_sbb = $ec->getService('Sbb');

$_tpl->assignGroup(array(
	'bbcode' => $_sbb->bbcodes(),
	'smiley' => $_sbb->smileys()
));

if ($_route->getAction() !== NULL)
{
	$_head->set('<script src="'.ADDR_TEMPLATES.'javascripts/messages.js"></script>');
}

$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/messages.css" media="screen" rel="stylesheet">');
$_head->set('<script src="'.ADDR_TEMPLATES.'javascripts/jquery.tabs.js"></script>');

// Przegląd wszystkich wiadomości
if ($_route->getAction() === NULL)
{
	$_tpl->assign('section', 'overview');

	// Usuwanie przestarzałych wiadomości
	$_pdo->exec('DELETE FROM [messages] WHERE datestamp < '.(time() - 60*60*24*61)); // automatyczne usuwanie wiadomości starszych niż 61 dni

	// Pobieranie listy wątków tematycznych spośród wszystkich wiadomości
	$query = $_pdo->getData('SELECT k.to, k.from, (SELECT mm.subject FROM [messages] AS mm WHERE mm.item_id = k.item_id ORDER BY mm.id ASC LIMIT 1) AS subject, k.datestamp, k.item_id, k.read FROM [messages] AS k WHERE (k.to = :user OR k.from = :user) AND k.id IN (SELECT max(b.id) FROM [messages] AS b GROUP BY b.item_id) ORDER BY k.id DESC',
		array(':user', $_user->get('id'), PDO::PARAM_INT)
	);

	if($query)
	{
		$theme = array(
			'Title' => __('Skrzynka odbiorcza Prywatnych Wiadomości'),
			'Keys' => 'prywatne wiadomości, komunikacja, chat',
			'Desc' => 'W łatwy sposób możesz komunikować się z użytkownikami.'
		);

		$i = 0;
		foreach($query as $row)
		{
			$userid = $row['to'] == $_user->get('id') ? $row['from'] : $row['to'];

			$data[$i] = array(
				'user_id' => $userid,
				'user_link' => HELP::profileLink(NULL, $userid),
				'user_avatar' => $_user->getAvatarAddr($userid),
				'subject' => $row['subject'],
				'datestamp' => HELP::showDate('shortdate', $row['datestamp']),
				'datetime' => date('c', $row['datestamp']),
				'item_id' => $row['item_id'],
				'msg_link' => $_route->path(array('controller' => 'messages', 'action' => 'view', $userid, $row['item_id'], $row['subject'] ? HELP::Title2Link($row['subject']) : HELP::Title2Link('no subject')))
			);

			// Czy wiadomość wysłao do mnie?
			if ($row['to'] === $_user->get('id'))
			{
				if ($row['read'] === '0')
				{
					// Jeszcze nie została przeczytana.
					$data[$i]['read_status'] = '1';
				}
				else
				{
					// Już została przeczytana.
					$data[$i]['read_status'] = '2';
				}
			}
			else
			{
				if ($row['read'] === '0')
				{
					// Jeszcze nie została przeczytana przez odbiorcę.
					$data[$i]['read_status'] = '3';
				}
				else
				{
					// Już została przeczytana przez odbiorcę.
					$data[$i]['read_status'] = '4';
				}
			}

			$i++;
		}

		$_tpl->assign('data', $data);
	}
	
	// Sprawdzanie, czy są jakieś wiadomości w kategoriach "odebrane", "wysłane", "robocze" - do zrobienia :)
	$_tpl->assign('has_messages', array('inbox' => TRUE, 'outbox' => TRUE, 'draft' => TRUE));

	// Link do "Nowej wiadomości"
	$_tpl->assign('url_new_message', $_route->path(array('controller' => 'messages', 'action' => 'new')));
}
// Podstrona kontynuowania rozpoczętego wątku - odpowiadania na wiadomość
elseif ($_route->getAction() === 'view')
{
	// Sprawdzanie formatu parametrów: ID adresata, ID konwersacji
	if (isNum($_route->getParamVoid(1)) && isNum($_route->getParamVoid(2)))
	{
		$_tpl->assign('section', 'entry');

		// Nazwa użytkownika-adresata, którego dotyczy konwersacja
		$recipient = $_user->getByID($_route->getParamVoid(1), 'username');

		$theme = array(
			'Title' => __('Wyślij wiadomość do: ').$recipient.' » '.$_sett->get('site_name'),
			'Keys' => 'prywatne wiadomości, komunikacja, chat z '.$recipient,
			'Desc' => 'W łatwy sposób możesz komunikować się z '.$recipient.'.'
		);

		/**
		 * Sprawdzanie, czy konwersacja o podanym ID istnieje.
		 *
		 * Należy rozważyć sytuację, gdy ktoś do nas napisał pierwszy lub gdy my napisaliśmy,
		 * ale zanim adresat odebrał wiadomość, ponownie weszliśmy w tę określoną konwersację.
		 * Dlatego też zapytanie ma warunki zapisane w takiej, a nie innej formie.
		 */
		$query = $_pdo->getSelectCount('SELECT Count(`id`) FROM [messages] WHERE `item_id` = :item_id AND (`to` = :user OR `from` = :user) ORDER BY id DESC LIMIT 1',
			array(
				array(':item_id', $_route->getParamVoid(2), PDO::PARAM_INT),
				array(':user', $_user->get('id'), PDO::PARAM_INT)
			)
		);

		if ($query)
		{
			// Oznaczanie wiadomości wysłanej do nas, którą właśnie przeglądamy, jako przeczytanej.
			$update = $_pdo->exec('UPDATE [messages] SET `read` = 1 WHERE `item_id` = :item_id AND `to` = :to',
				array(
					array(':item_id', $_route->getParamVoid(2), PDO::PARAM_INT),
					array(':to', $_user->get('id'), PDO::PARAM_INT)
				)
			);

			$_tpl->assign('item_id', $_route->getParamVoid(2));
			$_tpl->assign('user', array('id' => $_route->getParamVoid(1), 'username' => $recipient));
		}
	}
}
// Podstrona określania odbiorcy
elseif ($_route->getAction() === 'new')
{
	if ($_route->getParamVoid(1) && isNum($_route->getParamVoid(1)))
	{
		$_tpl->assign('section', 'new-by-user');

		$recipient = $_pdo->getRow('SELECT `id`, `username` FROM [users] WHERE `id` = :user  ORDER BY username DESC',
			array(':user', $_route->getParamVoid(1), PDO::PARAM_INT)
		);

		$_tpl->assign('user', $recipient);
	}
	else
	{
		$_tpl->assign('section', 'new-by-search');

		$theme = array(
			'Title' => __('Nowa wiadomość'),
			'Keys' => 'prywatne wiadomości, komunikacja, chat',
			'Desc' => 'W łatwy sposób możesz komunikować się z użytkownikami strony.'
		);
	}
}
