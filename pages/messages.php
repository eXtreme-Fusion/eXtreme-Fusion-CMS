<?php defined('EF5_SYSTEM') || exit;
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/
$_head->set('<meta name="robots" content="noindex" />');

$_user->onlyForUsers($_route);

$_locale->load('messages');

//Initiation of Smiley & BBCode parser
$_sbb = $ec->getService('sbb');

$_tpl->assign('bbcode', $_sbb->bbcodes());

$_head->set('<script src="'.ADDR_TEMPLATES.'javascripts/messages.js"></script>');
$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/messages.css" media="screen" rel="stylesheet" />');

// Przegląd wszystkich wiadomości
if ($_route->getAction() === NULL)
{
	// Usuwanie przestarzałych wiadomości
	$_pdo->exec('DELETE FROM [messages] WHERE datestamp < '.(time() - 60*60*24*61)); // automatyczne usuwanie wiadomości starszych niż 61 dni

	$query = $_pdo->getData('SELECT k.to, k.from, (SELECT mm.subject FROM [messages] AS mm WHERE mm.item_id = k.item_id ORDER BY mm.id ASC LIMIT 1) AS subject, k.datestamp, k.item_id, k.read FROM [messages] AS k WHERE (k.to = :user OR k.from = :user) AND k.id IN (SELECT max(b.id) FROM [messages] AS b GROUP BY b.item_id) ORDER BY k.id DESC',
		array(':user', $_user->get('id'), PDO::PARAM_INT)
	);


	$_tpl->assign('url_new_message', $_route->path(array('controller' => 'messages', 'action' => 'new')));
}
// Wejście w podstronę do odpisywania
elseif ($_route->getAction() === 'view')
{
	$_tpl->assign('to', $_route->getParamVoid(1));

	$theme = array(
		'Title' => __('Wyślij wiadomość do: ').$_user->getByID(intval($_route->getParamVoid(1)), 'username').' &raquo; '.$_sett->get('site_name'),
		'Keys' => 'Prywatne wiadomości, komunikacja, prywatny chat z '.$_user->getByID(intval($_route->getParamVoid(1)), 'username').'',
		'Desc' => 'W łatwy sposób możesz komunikować się z '.$_user->getByID(intval($_route->getParamVoid(1)), 'username').'.'
	);
	
	if ($_route->getParamVoid(2))
	{
		$query = $_pdo->getData('SELECT `to`, `from`, `subject`, `datestamp`, `item_id`, `read` FROM [messages] WHERE `item_id` = :item_id AND (`to` = :user OR `from` = :user) ORDER BY id DESC',
			array(
				array(':item_id', $_route->getParamVoid(2), PDO::PARAM_INT),
				array(':user', $_user->get('id'), PDO::PARAM_INT)
			)
		);

		$update = $_pdo->exec('UPDATE [messages] SET `read` = 1 WHERE `item_id` = :item_id AND `to` = :to',
			array(
				array(':item_id', $_route->getParamVoid(2), PDO::PARAM_INT),
				//array(':from', $_route->getByID(1), PDO::PARAM_INT),
				array(':to', $_user->get('id'), PDO::PARAM_INT)
			)
		);
	}
	else
	{
		$_tpl->assign('new_discuss', TRUE);
    
		$query = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `id` != :user  ORDER BY username DESC',
			array(
				array(':user', $_user->get('id'), PDO::PARAM_INT)
			)
		);
    $i = 0;
		foreach($query as $row)
		{

			$data[$i] = array(
				'id' => $row['id'],
				'username' => $row['username']
			);
			$i++;
		}
    $_tpl->assign('data', $data);
	}
}
// Podstrona określania odbiorcy
elseif ($_route->getAction() === 'new')
{
    $_tpl->assign('new_discuss', TRUE);
    $theme = array(
			'Title' => __('Nowa wiadomość do: ').$_user->getByID(intval($_route->getParamVoid(1)), 'username').' &raquo; '.$_sett->get('site_name'),
			'Keys' => 'Prywatne wiadomości, komunikacja, prywatny chat z '.$_user->getByID(intval($_route->getParamVoid(1)), 'username').'',
			'Desc' => 'W łatwy sposób możesz komunikować się z '.$_user->getByID(intval($_route->getParamVoid(1)), 'username').'.'
		);
		$query = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `id` != :user  ORDER BY username DESC',
			array(
				array(':user', $_user->get('id'), PDO::PARAM_INT)
			)
		);
    $i = 0;
		foreach($query as $row)
		{

			$data[$i] = array(
				'id' => $row['id'],
				'username' => $row['username']
			);
			$i++;
		}
    $_tpl->assign('data', $data);	
 
}
// Lista wiadomosći
if ($_route->getAction() === NULL)
{
	if($query)
	{
		$theme = array(
			'Title' => __('Przeczytaj wiadomość od: ').$_user->getByID(intval($_route->getParamVoid(1)), 'username').' &raquo; '.$_sett->get('site_name'),
			'Keys' => 'Prywatne wiadomości, komunikacja, prywatny chat z '.$_user->getByID(intval($_route->getParamVoid(1)), 'username').'',
			'Desc' => 'W łatwy sposób możesz komunikować się z '.$_user->getByID(intval($_route->getParamVoid(1)), 'username').'.'
		);
		
		$i = 0;
		foreach($query as $row)
		{
			$userid = ($row['to'] == $_user->get('id') ? $row['from'] : $row['to']);

			$data[$i] = array(
				'user_id' => $userid,
				'user_link' => HELP::profileLink(NULL, $userid),
				'subject' => $row['subject'],
				'datestamp' => HELP::showDate('shortdate', $row['datestamp']),
				'item_id' => $row['item_id'],
				'msg_link' => $_route->path(array('controller' => 'messages', 'action' => 'view', $userid, $row['item_id'], HELP::Title2Link($row['subject'])))
			);

			// Wiadomość wysłana została do mnie
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
}

if ($_route->getParamVoid(2))
{
	$_tpl->assign('get_item_id', $_route->getParamVoid(2));
}
else
{
	$_tpl->assign('get_item_id', $_pdo->getMaxValue('SELECT MAX(`item_id`) FROM [messages]')+1);
}