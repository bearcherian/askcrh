<?php
$dbconfig['host'] = "localhost";
$dbconfig['username'] = "";
$dbconfig['password'] = "";
$dbconfig['dbname'] = "";
$dbconfig['host'] = "";

$db = mysql_connect($dbconfig['host'], $dbconfig['username'], $dbconfig['password']);
mysql_select_db($dbconfig['dbname']);

?>
