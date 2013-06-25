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
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	if ($_user->hasPermission('admin.pages'))
	{
		if ( ! $_user->isLoggedIn())
		{
			throw new userException(__('Access Denied'));
		}

		if ($_request->get('page')->show() === 'types')
		{
			/* Array of database columns which should be read and sent back to DataTables. Use a space where
			 * you want to insert a non-database field (for example a counter or static image)
			 */
			$aColumns = array('id', 'name', 'for_news_page', 'for_news_page');

			// Source table
			$table = 'pages_types';
		}
		elseif ($_request->get('page')->show() === 'categories')
		{
			/* Array of database columns which should be read and sent back to DataTables. Use a space where
			 * you want to insert a non-database field (for example a counter or static image)
			 */
			$aColumns = array('id', 'name', 'description', 'is_system');

			// Source table
			$table = 'pages_categories';
		}
		elseif ($_request->get('page')->show() === 'entries')
		{
			/* Array of database columns which should be read and sent back to DataTables. Use a space where
			 * you want to insert a non-database field (for example a counter or static image)
			 */
			$aColumns = array('id', 'title', 'description', 'type');

			// Source table
			$table = 'pages';
			
			$sIndexColumn = 'title';
		}
		else
		{
			exit('Nieprawidłowy odnośnik');
		}

			/* Indexed column (used for fast and accurate table cardinality) */
			$sIndexColumn = !isset($sIndexColumn) ? 'name' : $sIndexColumn;

			// Pozzycja w tablicy kolumny Opcje
			define('OPTIONS_COL', 3);

			/*
			 * Paging
			 */
			$sLimit = '';
			if ($_request->get('iDisplayStart', NULL)->show() !== NULL && $_request->get('iDisplayLength')->show() != '-1')
			{
				$sLimit = ' LIMIT '.$_request->get('iDisplayStart')->strip().', '.$_request->get('iDisplayLength')->strip();
			}

			/*
			 * Ordering
			 */
			$sOrder = '';
			if ($_request->get('iSortCol_0', NULL)->show() !== NULL)
			{
				$sOrder = 'ORDER BY  ';
				for ($i = 0; $i < intval($_request->get('iSortingCols')->show()); $i++)
				{
					if ($_request->get('bSortable_'.intval($_request->get('iSortCol_'.$i)->show()))->show() == 'true' && intval($_request->get('iSortCol_'.$i)->show()) != OPTIONS_COL)
					{
						$sOrder .= $aColumns[intval($_request->get('iSortCol_'.$i)->show())].' '.$_request->get('sSortDir_'.$i)->strip().', ';
					}
				}

				$sOrder = substr_replace($sOrder, '', -2);
				if ( $sOrder == 'ORDER BY')
				{
					$sOrder = '';
				}
			}

			/*
			 * Filtering
			 * NOTE this does not match the built-in DataTables filtering which does it
			 * word by word on any field. It's possible to do here, but concerned about efficiency
			 * on very large tables, and MySQL's regex functionality is very limited
			 */
			$sWhere = '';
			if ($_request->get('sSearch', NULL)->show() !== NULL && $_request->get('sSearch')->show() != '')
			{
				$sWhere = 'WHERE (';
				for ($i = 0; $i < count($aColumns); $i++)
				{
					$sWhere .= $aColumns[$i]." LIKE '%".$_request->get('sSearch')->strip()."%' OR ";
				}
				$sWhere = substr_replace($sWhere, '', -3);
				$sWhere .= ')';
			}

			/* Individual column filtering */
			for ($i = 0; $i < count($aColumns); $i++)
			{
				if ($_request->get('bSearchable_'.$i, NULL)->show() !== NULL && $_request->get('bSearchable_'.$i)->show() == 'true' && $_request->get('sSearch_'.$i)->show() != '')
				{
					if ($sWhere == '')
					{
						$sWhere = 'WHERE ';
					}
					else
					{
						$sWhere .= ' AND ';
					}
					if ($i < count($aColumns))
					$sWhere .= $aColumns[$i]." LIKE '%".$_request->get('sSearch_'.$i)->strip()."%' ";
				}
			}


			/*
			 * SQL queries
			 * Get data to display
			 */
			$sQuery = '
				SELECT SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)).'
				FROM   ['.$table.']
				'.$sWhere
				.$sOrder
				.$sLimit
			;
			$rResult = $_pdo->getData($sQuery);

			/* Data set length after filtering */
			$sQuery = 'SELECT FOUND_ROWS()';

			$aResultFilterTotal = $_pdo->getData($sQuery);
			$iFilteredTotal = $aResultFilterTotal[0];

			/* Total data set length */
			$sQuery = 'SELECT COUNT('.$sIndexColumn.') FROM   ['.$table.']';
			//$aResultTotal = $_pdo->getData( $sQuery);
			$iTotal =  $_pdo->getSelectCount( $sQuery);


			/*
			 * Output
			 */
			$output = array(
				'sEcho' => $_request->get('sEcho', NULL)->show() !== NULL ? intval($_request->get('sEcho')->show()) : '',
				'iTotalRecords' => $iTotal,
				'iTotalDisplayRecords' => $iFilteredTotal,
				'aaData' => array()
			);

			foreach($rResult as $aRow)
			{
				$row = array();
				// -1 -> pomijanie kolumny is_system
				for ($i = 0; $i < count($aColumns)-1; $i++)
				{
					if ($aColumns[$i] == 'version')
					{
						/* Special output formatting for 'version' column */
						$row[] = ($aRow[ $aColumns[$i] ]== '0') ? '-' : $aRow[$aColumns[$i]];
					}
					elseif ($aColumns[$i] != ' ')
					{
						/* General output */
						$row[] = $aRow[$aColumns[$i]];
					}
				}
				$row[] = '<a href="'.FILE_SELF.'?page='.$_request->get('page')->show().'&amp;action=edit&amp;id='.$row[0].'" class="tipTip" rel="'.__('Edit').'"><img src="'.ADDR_ADMIN_IMAGES.'icons/edit.png" alt="'.__('Edit').'" /></a>'.(!isset($aRow['is_system']) || !$aRow['is_system'] ? '<a href="'.FILE_SELF.'?page='.$_request->get('page')->show().'&amp;action=delete&amp;id='.$row[0].'" class="tipTip confirm_button" rel="'.__('Delete').'"><img src="'.ADDR_ADMIN_IMAGES.'icons/delete.png" alt="'.__('Delete').'" /></a>' : '');
				$output['aaData'][] = $row;
			}
		}

		_e(json_encode($output));

}
catch(optException $exception)
{
	optErrorHandler($exception);
}
catch(systemException $exception)
{
	systemErrorHandler($exception);
}
catch(userException $exception)
{
	userErrorHandler($exception);
}
catch(PDOException $exception)
{
    PDOErrorHandler($exception);
}
