<?php include("../../Common/common-topmenu.php"); ?>
<?php require_once('../../Connections/sqlconn.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../../login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "3";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../noaccess.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmUpdate")) {
  $updateSQL = sprintf("UPDATE t142_akaun SET f142email=%s, f142Name=%s, f142catatan=%s WHERE f142idakaun=%s",
                       GetSQLValueString($_POST['txtEmel'], "text"),
                       GetSQLValueString($_POST['txtName'], "text"),
                       GetSQLValueString($_POST['txtareaCatatan'], "text"),
                       GetSQLValueString($_POST['txtIDAkaun'], "int"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($updateSQL, $sqlconn) or die(mysql_error());

  $updateGoTo = "profile_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142noID = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/*$colname_rsUser = "-1";
if (isset($_SESSION['colname'])) {
  $colname_rsUser = $_SESSION['colname'];
}*/

//Carry Username and User Level
$username = $_SESSION['MM_Username'];
$userlevel = $_SESSION['MM_UserGroup'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://ptmk.upnm.edu.my/
Released for free under a Creative Commons Attribution 2.5 License

Name       : Condition 
Description: A two-column, fixed-width design for 1024x768 screen resolutions.
Version    : 1.0
Released   : 20100103

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Pengurusan E-Pejabat, Universiti Pertahanan Nasional Malaysia</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../../Common/style-blue.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
<!--
body,td,th {
	color: #000;
}
.tengah {text-align: center;
	vertical-align: middle;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
		function validate_logout()
		{
			if (confirm("Anda pasti? "))
   			{
     			return true;
    		}
    		else
    		{
      			return false;
    		} 	
		}
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35150695-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<div id="wrapper">
	<div id="logo">
		<h1>&nbsp;</h1>
</div>
	<hr />
	<!-- end #logo -->
	<div id="header">
	  <div id="menu">
	    <ul>
	      <?php menupengurus_profile(); ?>
	      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
        </ul>
      </div>
	  <!-- end #menu -->
	  <div id="search">
	    <div id="search2"> <span class="welcome"><br />
	      <a href="profile.php"><img src="../../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
      </div>
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
		<div id="content">
		  <div class="post">
			<h2 class="title"><a href="#">Profail Pengguna</a></h2>
				<p class="meta">Kemaskini terakhir pada  
				  <!-- #BeginDate format:fcSw1a -->Wednesday, 16 April, 2014 10:24 AM<!-- #EndDate -->
				</p>
				<div class="entry">
				  <form name="frmUpdate" id="frmUpdate" method="POST" action="<?php echo $editFormAction; ?>">
				    <table width="658" border="0">
				      <tr>
		<td width="150" align="right"><div align="right"><strong>Nama : </strong></div></td>
		<td width="498"><label>
		  <input name="txtName" type="text" id="txtName" value="<?php echo $row_rsUser['f142Name']; ?>" />
		</label></td>
	  </tr>
				      <tr>
		<td align="right"><div align="right"><strong>ID Pengguna / No IC : </strong></div></td>
		<td><strong><?php echo $row_rsUser['f142noID']; ?></strong></td>
	  </tr>
				      <tr>
		<td align="right"><div align="right"><strong>Emel : </strong></div></td>
		<td><label>
		  <input name="txtEmel" type="text" id="txtEmel" value="<?php echo $row_rsUser['f142email']; ?>" size="60" />
		</label></td>
	  </tr>
				      <tr>
		<td align="right"><div align="right"><strong>Level : </strong></div></td>
		<td><?php 
						if 
							($row_rsUser['f142idlevel'] == 1) {echo 'Pentadbir Sistem (Modul Administrator)';} 
						elseif 
							($row_rsUser['f142idlevel'] == 2) {echo 'Pengguna Sistem (Modul Pengguna)';}
						elseif
							($row_rsUser['f142idlevel'] == 3) {echo 'Pentadbir Sistem (Modul Pengurus)';}
						else
							{echo 'Tiada konfigurasi untuk level ini. Sila hubungi Pusat Teknologi Maklumat Dan Komunikasi';}
						?></td>
	  </tr>
				      <tr>
		<td align="right" valign="top"><div align="right"><strong>Catatan : </strong></div></td>
		<td><label>
		  <textarea name="txtareaCatatan" id="txtareaCatatan" cols="45" rows="3"><?php echo $row_rsUser['f142catatan']; ?></textarea>
	    </label></td>
	  </tr>
				      <tr>
		<td align="right">&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
				      <tr>
		<td align="right">&nbsp;</td>
		<td><input type="submit" name="btnKemaskini" id="btnKemaskini" value="Kemaskini" /></td>
	  </tr>
	</table>
				    <input type="hidden" name="MM_update" value="frmUpdate" />
				    <input type="hidden" name="MM_update" value="frmUpdate" />
				    <input name="txtIDAkaun" type="hidden" id="txtIDAkaun" value="<?php echo $row_rsUser['f142idakaun']; ?>" />
				  </form>
				</div>
		  </div>
</div>
		<!-- end #content -->
			<div id="sidebar">
	  <ul>
	    <li><?php include("profile_menu.php"); ?></li>
	  </ul>
	  </div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	</div>
	<!-- end #page -->
	<div id="footer-bgcontent">
	<div id="footer">
		<p>Hakcipta (c) 2011 ptmk.upnm.edu.my. Hakcipta terpelihara. Dibangunkan oleh Pusat Teknologi maklumat &amp; komunikasi.<br />
	    Universiti Pertahanan Nasional Malaysia<br /></p>
	</div>
	</div>
	<!-- end #footer -->
</div>


</body>
</html>
<?php
mysql_free_result($rsUser);
?>
