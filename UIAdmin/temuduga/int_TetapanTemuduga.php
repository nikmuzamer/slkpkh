<?php require_once('../../Connections/sqlconn.php'); ?>
<?php include("../../Common/common-topmenu.php"); ?>
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
$MM_authorizedUsers = "1";
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

$MM_restrictGoTo = "../../UIUser/application/desktop-user.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmTetapanTemuduga")) {
  $updateSQL = sprintf("UPDATE t101temuduga SET f101semak_tempat=%s, f101semak_tarafjawatan=%s, f101semak_tarikh=%s, f101semak_masa=%s WHERE f101kodjawatan=%s",
                       GetSQLValueString($_POST['slctTmpt'], "text"),
                       GetSQLValueString($_POST['slctTaraf'], "text"),
                       GetSQLValueString($_POST['textfield2'], "text"),
                       GetSQLValueString($_POST['slctMasa'], "text"),
                       GetSQLValueString($_POST['hdnJawatan'], "text"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($updateSQL, $sqlconn) or die(mysql_error());

  $updateGoTo = "int_berjayalist.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$currentPage = $_SERVER["PHP_SELF"];

$jaw_rsJawatan = "-1";
if (isset($_GET['ID'])) {
  $jaw_rsJawatan = $_GET['ID'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJawatan = sprintf("SELECT * FROM t144mohon_jawatan, t100peribadi WHERE t100peribadi.ICNO=t144mohon_jawatan.ICNO  AND t100peribadi.peribadiID=%s", GetSQLValueString($jaw_rsJawatan, "int"));
$rsJawatan = mysql_query($query_rsJawatan, $sqlconn) or die(mysql_error());
$row_rsJawatan = mysql_fetch_assoc($rsJawatan);
$jawKOD_rsJawatan = "-1";
if (isset($_GET['JAWKOD'])) {
  $jawKOD_rsJawatan = $_GET['JAWKOD'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJawatan = sprintf("SELECT * FROM t109kod_jawatan WHERE t109kod_jawatan.f109jawKod= %s", GetSQLValueString($jawKOD_rsJawatan, "int"));
$rsJawatan = mysql_query($query_rsJawatan, $sqlconn) or die(mysql_error());
$row_rsJawatan = mysql_fetch_assoc($rsJawatan);
$totalRows_rsJawatan = mysql_num_rows($rsJawatan);

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142noIC = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$colname_rsPeribadi = "-1";
if (isset($_GET['ID'])) {
  $colname_rsPeribadi = $_GET['ID'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPeribadi = sprintf("SELECT * FROM t100peribadi WHERE peribadiID = %s", GetSQLValueString($colname_rsPeribadi, "int"));
$rsPeribadi = mysql_query($query_rsPeribadi, $sqlconn) or die(mysql_error());
$row_rsPeribadi = mysql_fetch_assoc($rsPeribadi);
$totalRows_rsPeribadi = mysql_num_rows($rsPeribadi);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTempat = "SELECT * FROM t109kod_semaktempat ORDER BY ALAMAT_TEMPAT ASC";
$rsTempat = mysql_query($query_rsTempat, $sqlconn) or die(mysql_error());
$row_rsTempat = mysql_fetch_assoc($rsTempat);
$totalRows_rsTempat = mysql_num_rows($rsTempat);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsStatTemuduga = "SELECT * FROM t109kod_semakstatus ORDER BY f109statusdescription ASC";
$rsStatTemuduga = mysql_query($query_rsStatTemuduga, $sqlconn) or die(mysql_error());
$row_rsStatTemuduga = mysql_fetch_assoc($rsStatTemuduga);
$totalRows_rsStatTemuduga = mysql_num_rows($rsStatTemuduga);

$queryString_rsJawatan = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsJawatan") == false && 
        stristr($param, "totalRows_rsJawatan") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsJawatan = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsJawatan = sprintf("&totalRows_rsJawatan=%d%s", $totalRows_rsJawatan, $queryString_rsJawatan);

//$colname_rsUser = "-1";
//if (isset($_SESSION['f142username'])) {
//  $colname_rsUser = $_SESSION['f142username'];
//}
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
<link href="../../Common/style-red.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
<!--
body,td,th {
	color: #000;
}
.center {	text-align: center;
}
.center {
	text-align: center;
}
#wrapper #page #page-bgtop #content .post .entry #form1 table tr td table {
	text-align: right;
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
	      <?php menuadmin_temuduga(); ?>
	      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
	      
        </ul>
  </div>
  <!-- end #menu -->
	  <div id="search"> <span class="welcome"><br />
	    <a href="../profile/profile.php"><img src="../../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
  <!-- end #search -->
</div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
<div id="content">
		  <div class="post">
		    <h2 class="title"><a href="#" class="title">Tetapan Temuduga</a></h2>
		    <p class="meta">Kemaskini terakhir pada  
				  <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 5:06 PM<!-- #EndDate -->
			</p>
		    <div class="entry">
		      <form action="<?php echo $editFormAction; ?>" name="frmTetapanTemuduga" id="frmTetapanTemuduga" method="POST">
<table width="643" height="173">
  <tr>
    <td><strong>INFO:</strong></td>
          </tr>
  <tr>
    <td><em>*Tetapan ini adalah pengemaskinian bagi semua senarai pendek mengikut jawatan.</em></td>
  </tr>
  <tr>
    <td align="center"><table width="633" height="186" border="0">
      <tr>
        <td width="150" height="17" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="473" bgcolor="#FFFFFF"><input name="hdnJawatan" type="hidden" id="hdnJawatan" value="<?php echo $row_rsJawatan['f109jawKod']; ?>" /></td>
        </tr>
      <tr>
        <td height="19"><div align="right"><strong>Jawatan  :</strong><br />
          </div></td>
        <td class="boldwelcome"><strong><?php echo $row_rsJawatan['f109jawDesc']; ?></strong></td>
        </tr>
      <tr>
        <td height="19"><div align="right"><strong>Tempat Temuduga  :</strong></div></td>
        <td class="center"><label for="slctTmpt"></label>
          <select name="slctTmpt" id="slctTmpt">
            <option value="0">---SILA PILIH---</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsTempat['TEMPAT']?>"><?php echo $row_rsTempat['ALAMAT_TEMPAT']?></option>
            <?php
} while ($row_rsTempat = mysql_fetch_assoc($rsTempat));
  $rows = mysql_num_rows($rsTempat);
  if($rows > 0) {
      mysql_data_seek($rsTempat, 0);
	  $row_rsTempat = mysql_fetch_assoc($rsTempat);
  }
?>
            </select></td>
        </tr>
      <tr>
        <td height="28"><div align="right"><strong>Taraf Jawatan   :</strong></div></td>
        <td class="center"><label for="slctTaraf"></label>
          <select name="slctTaraf" id="slctTaraf">
            <option value="NULL">---SILA PILIH---</option>
            <option value="TETAP">TETAP</option>
            <option value="KONTRAK">KONTRAK</option>
            </select></td>
        </tr>
     <!--  <tr>
       <td height="19"><div align="right"><strong>Status Temuduga   :</strong></div></td>
        <td class="center"><label for="slctTemuduga"></label>
          <select name="slctTemuduga" id="slctTemuduga">
            <option value="0">---SILA PILIH---</option>
           <?php  /*
do {  
?>
            <option value="<?php echo $row_rsStatTemuduga['f109statuskod']?>"><?php echo $row_rsStatTemuduga['f109statusdescription']?></option>
            <?php
} while ($row_rsStatTemuduga = mysql_fetch_assoc($rsStatTemuduga));
  $rows = mysql_num_rows($rsStatTemuduga);
  if($rows > 0) {
      mysql_data_seek($rsStatTemuduga, 0);
	  $row_rsStatTemuduga = mysql_fetch_assoc($rsStatTemuduga);
  }
*/?>
            </select></td>
        </tr>-->
      <tr>
        <td height="19"><div align="right"><strong>Tarikh  :</strong></div></td>
        <td class="center"><label for="select2"></label>
          <label for="textfield2"></label>
          <input type="text" name="textfield2" id="textfield2" />
          <label for="textfield"></label></td>
        </tr>
      <tr>
        <td height="19"><div align="right"><strong>Masa   :</strong></div></td>
        <td class="center"><label for="slctMasa"></label>
          <select name="slctMasa" id="slctMasa">
            <option value="NULL" selected="selected">---SILA PILIH---</option>
            <option value="08.00 PAGI">08.00 PAGI</option>
            <option value="08.30 PAGI">08.30 PAGI</option>
            <option value="09.00 PAGI">09.00 PAGI</option>
            <option value="09.30 PAGI">09.30 PAGI</option>
            <option value="10.00 PAGI">10.00 PAGI</option>
            <option value="10.30 PAGI">10.30 PAGI</option>
            <option value="11.00 PAGI">11.00 PAGI</option>
            <option value="11.30 PAGI">11.30 PAGI</option>
            <option value="12.00 TENGAH HARI">12.00 TENGAH HARI</option>
            <option value="12.30 TENGAH HARI">12.30 TENGAH HARI</option>
            <option value="01.00 PETANG">01.00 PETANG</option>
            <option value="01.30 PETANG">01.30 PETANG</option>
            <option value="02.00 PETANG">02.00 PETANG</option>
            <option value="02.30 PETANG">02.30 PETANG</option>
            <option value="03.00 PETANG">03.00 PETANG</option>
            <option value="03.30 PETANG">03.30 PETANG</option>
            <option value="04.00 PETANG">04.00 PETANG</option>
            <option value="04.30 PETANG">04.30 PETANG</option>
            <option value="05.00 PETANG">05.00 PETANG</option>
          </select>
          <label></label></td>
        </tr>
      <tr>
        <td height="19">&nbsp;</td>
        <td class="center"><label>
          <input type="submit" name="txtSimpan" id="txtSimpan" value="Kemaskini" />
          </label></td>
        </tr>
      </table></td>
  </tr>
	            </table>
<input type="hidden" name="MM_update" value="frmTetapanTemuduga" />
              </form>
	        </div>
	      </div>
</div>
		<!-- end #content -->
		<div id="sidebar">
	  <ul>
	    <li><?php include("int_menu.php"); ?></li>
	  </ul>
	</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	</div>
	<!-- end #page -->
	<div id="footer-bgcontent">
	<div id="footer">
		<p>Hakcipta (c) 2011 ptmk.upnm.edu.my. Hakcipta terpelihara. Dibangunkan oleh Pusat Teknologi maklumat &amp; komunikasi.<br />
	    Universiti Pertahanan Nasional Malaysia</p>
	</div>
	</div>
	<!-- end #footer -->
</div>


</body>
</html>
<?php
mysql_free_result($rsJawatan);

//mysql_free_result($rsJawatan);

mysql_free_result($rsUser);

mysql_free_result($rsPeribadi);

mysql_free_result($rsTempat);

mysql_free_result($rsStatTemuduga);
?>
