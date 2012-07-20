<?php
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
require '../../config.php';
require '../../system/sitecore.php';

$_comment = $ec->comment;

if ($_request->get('action')->show() === 'delete')
{
	if ($_request->get('id')->isNum())
	{
		if ($_comment->canDelete($_request->get('id')->show()))
		{
			_e('<div id="ajax"><p class="delete center" id="'.$_request->get('id')->show().'"><span class="pointer">Usuń ten komentarz</span></p></div>');
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
				<p id="<?php echo $_request->get('id')->show() ?>" class="update center"><span class="pointer">Zaktualizuj</span></p>
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
			if ($_request->post('post')->show())
			{
				$r = $_pdo->exec('UPDATE [comments] SET post = :post WHERE id = '.$_request->post('id')->show(),
					array(':post', $_request->post('post')->strip(), PDO::PARAM_STR)
				);

				_e($r);
			}

			_e('Błąd: nie podano treści komentarza.');
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
	_e($_comment->get($_request->get('comment_type')->show(), $_request->get('comment_item')->show(), 0, TRUE));
}