<?php

$message = $_pdo->getField('SELECT `message` FROM [cookies]');
$_tpl->assign('message', $message);