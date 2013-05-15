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
require '../../config.php';
require '../../system/sitecore.php';

$_comment = $ec->comment;

if ($_request->get('action')->show() === 'delete')
{
	if ($_request->get('id')->isNum())
	{
		if ($_comment->canDelete($_request->get('id')->show()))
		{
			_e('<div id="ajax"><p class="delete center" id="'.$_request->get('id')->show().'"><span class="pointer button">Usuń ten komentarz</span></p></div>');
		}
	}
}
elseif ($_request->post('action')->show() === 'delete')
{
	if ($_request->post('id')->isNum())
	{
		if ($_comment->canDelete($_request->post('id')->show()))
		{
			if ($_request->post('request')->show() === 'confirm')
			{
				if ($_pdo->exec('DELETE FROM [comments] WHERE `id` = '.$_request->post('id')->show()))
				{
					_e('deleted');
				}
			}
		}
	}
}
elseif ($_request->get('action')->show() === 'edit')
{
	if ($_comment->canEdit($_request->get('id')->show()))
	{
		if ($_request->get('request')->show() === 'get')
		{
			$post = $_pdo->getField('SELECT `post` FROM [comments] WHERE `id` = '.$_request->get('id')->show());
			?>

			<form id="ajax">
				<textarea class="cm_textarea" cols="40" rows="4" name="post" id="post"><?php echo $post ?></textarea><br />
				<p id="<?php echo $_request->get('id')->show() ?>" class="update center"><span class="pointer button">Zaktualizuj</span></p>
			</form>

			<?php
		}
	}
}
elseif ($_request->post('action')->show() === 'edit')
{
	if ($_comment->canEdit($_request->post('id')->show()))
	{
		if ($_request->post('request')->show() === 'confirm')
		{
			$r = array(
				'status'  => 0,
				'content' => 'Błąd: nie podano treści komentarza.',
			);

			if ($_request->post('post')->show())
			{
				$_pdo->exec('UPDATE [comments] SET post = :post WHERE id = '.$_request->post('id')->show(),
					array(':post', $_request->post('post')->strip(), PDO::PARAM_STR)
				);

				$_sbb = $ec->getService('Sbb');

				$r = array(
					'status'  => 1,
					'content' => $_sbb->parseAllTags(nl2br($_request->post('post')->strip())),
				);
			}

			_e(json_encode($r));
		}
	}
}
elseif ($_request->post('action')->show() === 'save')
{
	if ($_comment->canAdd())
	{
		if ($_request->post('post')->show() && $_request->post('type')->show() && $_request->post('item')->isNum())
		{
			$author = $_request->post('author')->show() ? $_request->post('author')->show() : NULL;
			_e($_comment->addComment($_request->post('post')->show(), $_request->post('type')->show(), $_request->post('item')->show(), $author));
		}
	}
	_e(0);
}
elseif ($_request->get('action')->show() === 'load')
{
	_e($_comment->get($_request->get('comment_type')->show(), $_request->get('comment_item')->show(), 0, 100, TRUE));
}
