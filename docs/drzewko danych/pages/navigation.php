<?php

/**
foreach($data as $r)
{
	if ($right && $r['right'] < $right)
	{
		$z[] = $right;
	} 
	elseif ($z && $r['right'] > $z[count($z)-1])
	{
		array_pop($z);
	}
	
	foreach($z as $val)
	{
		echo '+';
	}
	
	echo $r['nazwa'].' <br />';
	
	$right = $r['right'];
}
**/



$_tree = new Tree($_pdo, 'drzewko');

$_tpl->assign('tree', $_tree->get(1));

/*
	foreach($_tree->get(1) as $val)
	{
		if (isset($val['new']))
		{
			echo '<ul>';
		}
		elseif (isset($val['end']))
		{
			echo '</ul>';
		}
		
		echo '<li>'.$val['name'].'</li>';
	}
*/

	