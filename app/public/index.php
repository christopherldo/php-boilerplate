<?php

require_once(dirname(__DIR__).'/config.php');
require_once(dirname(__DIR__).'/dao/UserDaoPostgreSQL.php');

$userDao = new UserDaoPostgreSQL($pdo);
$usersCount = $userDao->countAll();
?>

<h1>Hello World, welcome to this boilerplate.</h1>
<h2>There is currently, <?=$usersCount?> users registered.</h2>
