<?php defined('EF5_SYSTEM') || exit;

$_locale->moduleLoad('lang', 'downloads');

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

$theme = array(
	'Title' => __('Download - :site_name', array(':site_name' => $_sett->get('site_name')))
);

// Pobierz konkretny plik 100% działania
// *********************************************
if ($_route->getAction() && $_route->getAction() == 'prepare' && isNum($_route->getParam('file'), FALSE))
{
    if ($data = $_pdo->getRow('SELECT `url`, `file`, `cat` FROM [download] WHERE `id`= :id', array(array(':id', $_route->getParam('file'), PDO::PARAM_INT))))
    {
       $cdata = $_pdo->getRow('SELECT `access` FROM [download_cat] WHERE `id`= :id', array(array(':id', $data['cat'], PDO::PARAM_INT)));
       if ($_user->hasAccess($cdata['access']))
       {
            $_pdo->exec('UPDATE [download] SET `count` = `count`+1 WHERE `id`= :id', array(array(':id', $_route->getParam('file'), PDO::PARAM_INT)));
            if (isset($data['file']) && file_exists(DIR_SITE.'upload'.DS.'files'.DS.$data['file']))
            {
                require_once DIR_MODULES.'downloads'.DS.'class'.DS.'class.httpdownload.php';
                ob_end_clean();
                $object = new httpdownload;
                $object->set_byfile(DIR_SITE.'upload'.DS.'files'.DS.$data['file']);
                $object->use_resume = TRUE;
                $object->set_filename(str_replace(' ', '_', __('Download from - :site_name - :file', array(':site_name' => $_sett->get('site_name'), ':file' => $data['file']))));
                $object->download();
                exit;
            }
            else
            {
                $_route->redirect(array('controller' => 'downloads', 'action' => 'error', 'No file was found'));
            }
        }
        else
        {
            $_route->redirect(array('controller' => 'downloads', 'action' => 'error', 'Could not access'));
        }
    }
    else
    {
        $_route->redirect(array('controller' => 'downloads', 'action' => 'error', 'There is no such file'));
    }
}
// *********************************************

// Komunikaty błędu podczas pobierania 100% działa
// *********************************************
if ($_route->getAction() && $_route->getAction() == 'error')
{
    if($_route->getParamVoid(1) && inArray($_route->getParamVoid(1), array('could_not_access', 'there_is_no_such_file', 'no_file_was_found')))
    {
        $_tpl->assign('error', $_route->getParamVoid(1));
    }
    else
    {
        $_route->redirect(array('controller' => 'downloads'));
    }
}
// *********************************************

// Statystyki 100% działa
// *********************************************
$ds_cout = $_pdo->getRow('SELECT SUM(count) AS count FROM [download]');
$ds_files = $_pdo->getRow('SELECT Count(cat) AS files FROM [download]');

$_tpl->assign('statistics', array(
    'count' => $ds_cout['count'],
    'files' => $ds_files['files'],
));

if ($data = $_pdo->getRow('SELECT td.id, td.title, td.count, td.cat, tc.id, tc.access FROM [download] td LEFT JOIN [download_cat] tc ON td.cat=tc.id WHERE tc.`access` IN ('.$_user->listRoles().') ORDER BY count DESC LIMIT 0,1'))
{
    $_tpl->assign('popular', array(
        'link' => $_route->path(array('controller' => 'downloads', 'action' => 'view', $data['id'], HELP::Title2Link($data['title']))),
        'title_long' => $data['title'],
        'title_short' => HELP::trimlink($data['title'], 100),
        'count' => $data['count']
    ));
    unset($data);
}

if ($data = $_pdo->getRow('SELECT td.id, td.title, td.count, td.cat, td.datestamp, tc.id, tc.access FROM [download] td LEFT JOIN [download_cat] tc ON td.cat=tc.id WHERE tc.`access` IN ('.$_user->listRoles().')ORDER BY datestamp DESC LIMIT 0,1'))
{
    $_tpl->assign('latest', array(
        'link' => $_route->path(array('controller' => 'downloads', 'action' => 'view', $data['id'], HELP::Title2Link($data['title']))),
        'title_long' => $data['title'],
        'title_short' => HELP::trimlink($data['title'], 100),
        'count' => $data['count']
    ));
    unset($data);
}
// *********************************************




$_tpl->assign('Theme', $theme);

// Definiowanie katalogu templatek modu�u
$_tpl->setPageCompileDir(DIR_MODULES.'downloads'.DS.'templates'.DS);
