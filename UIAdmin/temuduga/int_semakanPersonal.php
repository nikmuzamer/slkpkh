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

$colname_rsJawatan = "-1";
if (isset($_SESSION['ICNO'])) {
  $colname_rsJawatan = $_SESSION['ICNO'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJawatan = sprintf(" SELECT * FROM t144mohon_jawatan, t100peribadi WHERE t144mohon_jawatan.ICNO=t100peribadi.ICNO AND f630nama LIKE %s ORDER BY f630nama ASC", GetSQLValueString("%" . $colname_rsJawatan . "%", "text"));
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

$maxRows_rsTemuduga = 30;
$pageNum_rsTemuduga = 0;
if (isset($_GET['pageNum_rsTemuduga'])) {
  $pageNum_rsTemuduga = $_GET['pageNum_rsTemuduga'];
}
$startRow_rsTemuduga = $pageNum_rsTemuduga * $maxRows_rsTemuduga;

$colname_rsTemuduga = "-1";
if (isset($_POST['txtNamaPemohon'])) {
  $colname_rsTemuduga = $_POST['txtNamaPemohon'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTemuduga = sprintf("SELECT * FROM t101temuduga, t109kod_jawatan WHERE f101nama LIKE %s AND t101temuduga.f101kodjawatan = t109kod_jawatan.f109jawKod ORDER BY f101nama ASC ", GetSQLValueString("%" . $colname_rsTemuduga . "%", "text"));
$query_limit_rsTemuduga = sprintf("%s LIMIT %d, %d", $query_rsTemuduga, $startRow_rsTemuduga, $maxRows_rsTemuduga);
$rsTemuduga = mysql_query($query_limit_rsTemuduga, $sqlconn) or die(mysql_error());
$row_rsTemuduga = mysql_fetch_assoc($rsTemuduga);

if (isset($_GET['totalRows_rsTemuduga'])) {
  $totalRows_rsTemuduga = $_GET['totalRows_rsTemuduga'];
} else {
  $all_rsTemuduga = mysql_query($query_rsTemuduga);
  $totalRows_rsTemuduga = mysql_num_rows($all_rsTemuduga);
}
$totalPages_rsTemuduga = ceil($totalRows_rsTemuduga/$maxRows_rsTemuduga)-1;

$queryString_rsTemuduga = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsTemuduga") == false && 
        stristr($param, "totalRows_rsTemuduga") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsTemuduga = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsTemuduga = sprintf("&totalRows_rsTemuduga=%d%s", $totalRows_rsTemuduga, $queryString_rsTemuduga);

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
-->
</style>
<script src="../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
		function confirm_delete()
		{
			if (confirm("Anda pasti untuk membuang rekod ini?"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
</script>
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
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
		    <h2 class="title"><a href="#" class="title">Konfigurasi Maklumat Perjawatan</a></h2>
		    <p class="meta">Kemaskini terakhir pada  
			    <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 5:06 PM<!-- #EndDate -->
			</p>
		    <div class="entry">
		      <form id="form1" method="post" action="int_semakanPersonal.php">
<table width="643" height="198">
  <tr>
    <td><strong>INFO:</strong></td>
          </tr>
  <tr>
    <td>
    <em>*Sila masukkan Nama Jawatan untuk Carian dan tekan butang Cari</em><br />
    <em>*Senarai carian adalah senarai pendek calon untuk ditemuduga</em>
    </td>
          </tr>
  <tr>
    <td>&nbsp;</td>
          </tr>
  <tr>
    <td height="51" align="center" valign="top"><table width="650" height="47">
      <tr>
        <td><fieldset>
          <legend>Borang Carian</legend>
          <table width="100%" border="0">
            <tr>
              <td width="25%" valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Carian Nama :</strong></div></td>
              <td width="75%" valign="top"><label><span id="sprytxtNamaPemohon">
                <input name="txtNamaPemohon" type="text" id="txtNamaPemohon" size="50" />
                <span class="textfieldRequiredMsg">*Wajib diisi.</span></span></label></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="submit" name="cmdSubmit" id="cmdSubmit" value="Cari" /></td>
            </tr>
          </table>
          
        </fieldset></td>
                </tr>
      </table></td>
          </tr>
  <tr>
    <td align="center"><table width="100%" border="1">
      <tr>
        <td colspan="5" align="center" valign="middle" bgcolor="#CCCCCC"><strong>SENARAI PERMOHONAN JAWATAN</strong></td>
                </tr>
      <tr>
        <td width="206" bgcolor="#E7E4E6"><strong>Nama Pemohon</strong></td>
        <td width="129" bgcolor="#E7E4E6"><strong>ICNO</strong></td>
        <td width="179" bgcolor="#E7E4E6"><strong>JAWATAN</strong></td>
        <td colspan="2" bgcolor="#E7E4E6"><strong>Tindakan</strong></td>
                </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rsTemuduga['f101nama']; ?></td>
          <td class="center"><?php echo $row_rsTemuduga['f101noic']; ?></td>
          <td class="center"><?php echo $row_rsTemuduga['f109jawDesc']; ?></td>
          <td width="42" class="center"><a href="int_validate.php?ICNO=<?php echo $row_rsTemuduga['f101noic']; ?>&amp;JAWGRED=<?php echo $row_rsTemuduga['f101kodjawatan']; ?>&amp;JAWKAT=<?php echo $row_rsTemuduga['f109jawStatus']; ?>" target="_blank">Cetak</a> <a href="int_semakanPersonal-update.php?ICNO=<?php echo $row_rsTemuduga['f101noic']; ?>"></a></td>
          <td width="47" class="center"><a href="int_semakanPersonal-update.php?ICNO=<?php echo $row_rsTemuduga['f101noic']; ?>&amp;jawKOD=<?php echo $row_rsTemuduga['f101kodjawatan']; ?>">Tetapan</a></td>
          </tr>
        <?php } while ($row_rsTemuduga = mysql_fetch_assoc($rsTemuduga)); ?>
                    </table></td>
          </tr>
	            </table><br />
                <p>&nbsp;<a href="<?php printf("%s?pageNum_rsTemuduga=%d%s", $currentPage, 0, $queryString_rsTemuduga); ?>"><img src="../../images/j_button2_first.png" alt="" /></a> <a href="<?php printf("%s?pageNum_rsTemuduga=%d%s", $currentPage, min($totalPages_rsTemuduga, $pageNum_rsTemuduga + 1), $queryString_rsTemuduga); ?>"><img src="../../images/j_button2_next.png" alt="" /></a> <a href="<?php printf("%s?pageNum_rsTemuduga=%d%s", $currentPage, max(0, $pageNum_rsTemuduga - 1), $queryString_rsTemuduga); ?>"><img src="../../images/j_button2_prev.png" alt="" /></a> <a href="<?php printf("%s?pageNum_rsTemuduga=%d%s", $currentPage, $totalPages_rsTemuduga, $queryString_rsTemuduga); ?>"><img src="../../images/j_button2_last.png" alt="" /></a> Rekod <?php echo ($startRow_rsTemuduga + 1) ?> hingga <?php echo min($startRow_rsTemuduga + $maxRows_rsTemuduga, $totalRows_rsTemuduga) ?> daripada <?php echo $totalRows_rsTemuduga ?> rekod.</p>
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
<script src="http://cdn.wibiya.com/Toolbars/dir_0781/Toolbar_781680/Loader_781680.js" type="text/javascript"></script>
<script type="text/javascript">
var _cmo = {form: '4d9ef09163b3d34f2200e573', text: 'Hubungi', align: 'left', valign: 'top', lang: 'en', background_color: '#003C68'}; (function() {var cms = document.createElement('script'); cms.type = 'text/javascript'; cms.async = true; cms.src = ('https:' == document.location.protocol ? 'https://d1uwd25yvxu96k.cloudfront.net' : 'http://static.contactme.com') + '/widgets/tab/v1/tab.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(cms, s);})();
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytxtNamaPemohon", "none");
</script>

</body>
</html>
<?php
mysql_free_result($rsJawatan);

mysql_free_result($rsUser);

mysql_free_result($rsTemuduga);
?>
