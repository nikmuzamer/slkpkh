<?php
function _dbConnect()
{
	$host = "localhost";
	$username = "root";
    $password = "admin";
	$link = mysql_connect($host,$username,$password);
	if (!$link)
	{
		die ("Could not connect");
	}
	$db_selected = mysql_select_db('epejabatdb', $link);
	if (!$db_selected)
	{
		die ("Could not select db");
	}
}
?>