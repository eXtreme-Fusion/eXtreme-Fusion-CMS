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
    if ($data = $_pdo->getRow('SELECT `url`, `file`, `cat` FROM [download] WHERE `id`= :id', array(':id', $_route->getParam('file'), PDO::PARAM_INT)))
    {
       $cdata = $_pdo->getRow('SELECT `access` FROM [download_cat] WHERE `id`= :id', array(':id', $data['cat'], PDO::PARAM_INT));
       if ($_user->hasAccess($cdata['access']))
       {
            if (isset($data['file']) && file_exists(DIR_SITE.'upload'.DS.'files'.DS.$data['file']))
            {
                $_pdo->exec('UPDATE [download] SET `count` = `count`+1 WHERE `id`= :id', array(':id', $_route->getParam('file'), PDO::PARAM_INT));
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
$ds_count = $_pdo->getRow('SELECT SUM(`count`) AS count FROM [download]');
$ds_files = $_pdo->getRow('SELECT Count(`cat`) AS files FROM [download]');

$_tpl->assign('statistics', array(
    'count' => $ds_count['count'],
    'files' => $ds_files['files'],
));

if ($data = $_pdo->getRow('SELECT td.`id` AS `file_id`, td.`title`, td.`count`, td.`cat`, tc.`id`, tc.`access` FROM [download] td LEFT JOIN [download_cat] tc ON td.`cat`=tc.`id` WHERE tc.`access` IN ('.$_user->listRoles().') ORDER BY `count` DESC LIMIT 0,1'))
{
    $_tpl->assign('popular', array(
        'link' => $_route->path(array('controller' => 'downloads', 'action' => 'view', $data['file_id'], HELP::Title2Link($data['title']))),
        'title_long' => $data['title'],
        'title_short' => HELP::trimlink($data['title'], 100),
        'count' => $data['count']
    ));
    unset($data);
}

if ($data = $_pdo->getRow('SELECT td.`id` AS `file_id`, td.`title`, td.`count`, td.`cat`, td.`datestamp`, tc.`id`, tc.`access` FROM [download] td LEFT JOIN [download_cat] tc ON td.`cat`=tc.`id` WHERE tc.`access` IN ('.$_user->listRoles().') ORDER BY `datestamp` DESC LIMIT 0,1'))
{
    $_tpl->assign('latest', array(
        'link' => $_route->path(array('controller' => 'downloads', 'action' => 'view', $data['file_id'], HELP::Title2Link($data['title']))),
        'title_long' => $data['title'],
        'title_short' => HELP::trimlink($data['title'], 100),
        'count' => $data['count']
    ));
    unset($data);
}
// *********************************************
// Podgląd konkretnej strony z plikiem 100% działa
// *********************************************
if ($_route->getAction() == 'view' && isNum($_route->getParamVoid(1), FALSE))
{
    ! class_exists('DownloadSett') || $_download_sett = New DownloadSett($_system, $_pdo);
    
    $_head->set('    <link rel="stylesheet" href="'.ADDR_SITE.'modules/downloads/templates/script/colorbox/colorbox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="'.ADDR_SITE.'modules/downloads/templates/script/colorbox/jquery.colorbox.js"></script>
    <script type="text/javascript">
        /* <![CDATA[ */
        jQuery(document).ready(function(){
            jQuery("a.tozoom").colorbox();
        });
        /* ]]>*/
    </script>');
    
    if ($data = $_pdo->getRow('SELECT td.*, tc.`id` `cat_id`, tc.`access`, tc.`name`, tu.`id` AS `user_id`, tu.`username`, tu.`status` FROM [download] td LEFT JOIN [download_cat] tc ON td.`cat`=tc.`id` LEFT JOIN [users] tu ON td.`user`=tu.`id` WHERE td.`id`=:id', array(array(':id', $_route->getParamVoid(1), PDO::PARAM_INT))))
    {
        if ( ! $_user->hasAccess($data['access']))
        {
            $_route->redirect(array('controller' => 'downloads', 'action' => 'error', 'Could not access'));
        }

        if (HELP::Title2Link($data['title']) !== $_route->getByID(3))
        {
            $_route->redirect(array('controller' => 'downloads', 'action' => 'error', 'No file was found'));
        }
     
        $_tpl->assign('result', TRUE);
        
        $_sbb = $ec->sbb;
        
        if ($data['homepage'] !== '') 
        {
            if ( ! strstr($data['homepage'], 'http://') && ! strstr($data['homepage'], 'https://')) 
            {
                $homepage = 'http://'.$data['homepage'];
            } 
            else
            {
                $homepage = $data['homepage'];
            }
        }
        
        $_tpl->assign('view', array(
            'title' => $data['title'],
            'base_link' => $_route->path(array('controller' => 'downloads')),
            'cat_link' => $_route->path(array('controller' => 'downloads', 'action' => 'cat', $data['cat'], $data['name'])),
            'cat_id' => $data['cat'],
            'cat_name' => $data['name'],
            'version' => $data['version'],
            'description' => ($data['description'] != '' ? nl2br($_sbb->parseAllTags($data['description'])) : nl2br(stripslashes($data['description_short']))),
            'filesize' => $_files->getFileSize($data['filesize']),
            'download_link' => $_route->path(array('controller' => 'downloads', 'action' => 'prepare', 'file-'.$data['id'], $data['title'], 'ready')),
            'homepage' => $data['homepage'] ? $homepage : '',
            'screenshot_src' => $_download_sett->get('screenshot') && $data['image'] ? ADDR_SITE.'upload/images/'.$data['image'] : '',
            'author_name' => $_user->getUsername($data['user_id']),
            'author_link' => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username']))),
            'datestamp' => HELP::showDate('shortdate', $data['datestamp']),
            'count' => $data['count'],
            'version' => $data['version'],
            'license' => $data['license'],
            'os' => $data['os'],
            'copyright' => $data['copyright'],
            'allow_comments' => $data['allow_comments'],
            'allow_ratings' => $data['allow_ratings'],
        ));
        
        if ($data['allow_comments'] === '1')
        {
            $_comment = new CommentPageNav($ec, $_pdo, $_tpl);
            if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'download_nav_comments'))
            {
                $_comment->create($data['id'], $_route->getByID(5), $ec->comment->getLimit(), 5, $_route->getFileName(), 'download_nav_comments', DIR_THEME.'templates'.DS.'paging'.DS);
            }
            else
            {
                $_comment->create($data['id'], $_route->getByID(5), $ec->comment->getLimit(), 5, $_route->getFileName(), 'download_nav_comments', DIR_MODULES.'downloads'.DS.'templates'.DS.'paging'.DS);
            }

            if (isset($_POST['comment']['save']))
            {
                $comment = array_merge($comment, array(
                        'action'  => 'add',
                        'author'  => $_user->get('id'),
                        'content' => $_POST['comment']['content']
                ));
            }
        }
        unset($data);
    }
    else
    {
        $_route->redirect(array('controller' => 'downloads', 'action' => 'error', 'There is no such file'));
    }
}
// *********************************************
// Podgląd konkretnej strony z plikiem 100% działa
// *********************************************

if ( ! $_route->getAction() || ! inArray($_route->getAction(), array('error', 'prepare', 'view')))
{
    ! class_exists('DownloadSett') || $_download_sett = New DownloadSett($_system, $_pdo);
    
    $query = $_pdo->getData('
        SELECT `id`, `name`, `description`, `access`, `sorting`
        FROM [download_cat]
        WHERE `access` IN ('.$_user->listRoles().')
	ORDER BY `name`'
    );  

    $category = array();
    if ($_pdo->getRowsCount($query))
    {
        foreach($query as $row)
        {
            $category[$row['id']] = $row['name'];
        }
    }

    $_tpl->assignGroup(array(
        'category_list' => $_tpl->createSelectOpts($category, $_route->getParamVoid(1), TRUE),
        'order_by' => 'user',
        'sort' => 'DESC'
    ));

    unset($category);

    $_head->set('    <script>/* <![CDATA[ */ jQuery(document).ready(function() { jQuery("#filter_button").hide();});/* ]]>*/</script>');

    if ($_pdo->getRowsCount($query))
    {	
        $sign_cat = array(); $j=0; $sign = array(); $i=0; $d = array(); 
        foreach($query as $row)
        {
            if ($_user->hasAccess($row['access']))
            {                
                $rows = $_pdo->getRow('SELECT Count(`id`) FROM [download] WHERE `cat` = :cat', array(':cat', $row['id'], PDO::PARAM_INT));

                if ($rows) 
                {
                    $query = $_pdo->getData('SELECT td.`id`, td.`user`, td.`datestamp`, td.`image_thumb`, td.`cat`,
                            td.`title`, td.`version`, td.`count`, td.`description_short`,
                            tu.`id` AS user_id, tu.`username`, tu.`status`
                            FROM [download] td
                            LEFT JOIN [users] tu ON td.`user`=tu.`id`
                            WHERE td.`cat`=:cat'
                    , array(':cat', $row['id'], PDO::PARAM_INT));

                    if ($_pdo->getRowsCount($query))
                    {
                        foreach($query as $data)
                        {
                            if ($data['cat'] === $row['id'])
                            {
                                $count = $_pdo->getRow('SELECT Count(`id`) AS comments FROM [comments] WHERE `content_type` = "downloads" AND `content_id` = :content_id ', array(':content_id', $data['id'], PDO::PARAM_INT));
                                $sign[] = array(
                                    'row' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
                                    'id' => $data['id'],
                                    'new' => $data['datestamp'] + 604800 > time() + ($_sett->get('offset_timezone') * 3600),
                                    'image_thumb' => isset($data['image_thumb']) && $data['image_thumb'] != '' && file_exists(DIR_SITE.'upload'.DS.'images'.DS.$data['image_thumb']) ? ADDR_SITE.'upload/images/'.$data['image_thumb'] : ADDR_MODULES.'downloads/templates/images/no_image.png',
                                    'title_link' => $_route->path(array('controller' => 'downloads', 'action' => 'view', $data['id'], HELP::Title2Link($data['title']))),
                                    'title' => $data['title'],
                                    'datestamp' => HELP::showDate('shortdate', $data['datestamp']),
                                    'user_link' => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username']))),
                                    'user_name' => $data['username'],
                                    'version' => $data['version'] ? $data['version'] : '--',
                                    'count' => $data['count'],
                                    'comment_count' => $count['comments'],                                        
                                    'screenshot' => $_download_sett->get('screenshot'),
                                    'description_short' => $data['description_short'] ? nl2br(stripslashes($data['description_short'])) : ''
                                );
                                $i++;
                            }
                        } 
                    }
                    $sign_cat[] = array(
                        'row' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'list' => isset($sign) ? $sign : FALSE
                    );
                    
                    unset($query, $data, $sign);    
                }
            }
            $j++;
        }

        $_tpl->assign('cat_list', $sign_cat);
    }
}
// *********************************************

$_tpl->assign('Theme', $theme);
    
// Definiowanie katalogu templatek modułu
$_tpl->setPageCompileDir(DIR_MODULES.'downloads'.DS.'templates'.DS);
