<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname = "db600436593.db.1and1.com";
$database = "db600436593";
$username = "dbo600436593";
$password = "20eligefacil15#";
$dbConn = mysql_pconnect($hostname, $username, $password) or trigger_error(mysql_error(),E_USER_ERROR);
?>