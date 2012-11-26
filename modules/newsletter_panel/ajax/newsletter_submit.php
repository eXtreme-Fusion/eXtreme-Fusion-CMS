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

$request = '<br />Zostałeś wpisany na listę<br />&nbsp;';

if ( ! isset($_POST['email']) || $_POST['email'] == '') $request = '<br />Podaj swój adres email<br />&nbsp;';
if ( ! isset($_POST['rules'])) $request = '<br />Zaakceptuj regulamin<br />&nbsp;';
	
	
echo $request;