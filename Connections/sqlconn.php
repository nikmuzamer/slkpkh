<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sqlconn = "localhost";
$database_sqlconn = "slkpkh";
$username_sqlconn = "root";
$password_sqlconn = "admin";
$sqlconn = mysql_pconnect($hostname_sqlconn, $username_sqlconn, $password_sqlconn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>