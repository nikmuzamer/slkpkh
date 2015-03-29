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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$maxRows_rsPeribadi = 30;
$pageNum_rsPeribadi = 0;
if (isset($_GET['pageNum_rsPeribadi'])) {
  $pageNum_rsPeribadi = $_GET['pageNum_rsPeribadi'];
}
$startRow_rsPeribadi = $pageNum_rsPeribadi * $maxRows_rsPeribadi;

$colname_rsPeribadi = "-1";
if (isset($_POST['txtNamaPemohon'])) {
  $colname_rsPeribadi = $_POST['txtNamaPemohon'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPeribadi = sprintf("SELECT * FROM  t100peribadi WHERE  f630nama LIKE %s ORDER BY f630nama ASC", GetSQLValueString("%" . $colname_rsPeribadi . "%", "text"));
$query_limit_rsPeribadi = sprintf("%s LIMIT %d, %d", $query_rsPeribadi, $startRow_rsPeribadi, $maxRows_rsPeribadi);
$rsPeribadi = mysql_query($query_limit_rsPeribadi, $sqlconn) or die(mysql_error());
$row_rsPeribadi = mysql_fetch_assoc($rsPeribadi);

if (isset($_GET['totalRows_rsPeribadi'])) {
  $totalRows_rsPeribadi = $_GET['totalRows_rsPeribadi'];
} else {
  $all_rsPeribadi = mysql_query($query_rsPeribadi);
  $totalRows_rsPeribadi = mysql_num_rows($all_rsPeribadi);
}
$totalPages_rsPeribadi = ceil($totalRows_rsPeribadi/$maxRows_rsPeribadi)-1;

$colname_rsPengalamanKerja = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsPengalamanKerja = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPengalamanKerja = sprintf("SELECT * FROM t100pengalaman WHERE ICNO = %s", GetSQLValueString($colname_rsPengalamanKerja, "text"));
$rsPengalamanKerja = mysql_query($query_rsPengalamanKerja, $sqlconn) or die(mysql_error());
$row_rsPengalamanKerja = mysql_fetch_assoc($rsPengalamanKerja);
$totalRows_rsPengalamanKerja = mysql_num_rows($rsPengalamanKerja);

$colname_rsAkademik = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsAkademik = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsAkademik = sprintf("SELECT * FROM t100akademik WHERE f100ICNO = %s", GetSQLValueString($colname_rsAkademik, "text"));
$rsAkademik = mysql_query($query_rsAkademik, $sqlconn) or die(mysql_error());
$row_rsAkademik = mysql_fetch_assoc($rsAkademik);
$totalRows_rsAkademik = mysql_num_rows($rsAkademik);

$queryString_rsPeribadi = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPeribadi") == false && 
        stristr($param, "totalRows_rsPeribadi") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPeribadi = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPeribadi = sprintf("&totalRows_rsPeribadi=%d%s", $totalRows_rsPeribadi, $queryString_rsPeribadi);

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
	      <?php menuadmin_tapisan(); ?>
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
		    <h2 class="title"><a href="#" class="title">Saringan</a> <a href="#" class="title">Jabatan</a></h2>
		    <p class="meta">Kemaskini terakhir pada  
		      <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 4:48 PM<!-- #EndDate -->
				</p>
		    <div class="entry">
		      <form id="form1" method="post" action="../../Administrator/tapisan/statusPemohon.php">
<table width="643" height="460">
  <tr>
    <td height="17"><strong>INFO:</strong></td>
	              </tr>
  <tr>
    <td height="28"><em>*Sila masukkan jawatan untuk Carian dan tekan butang Cari</em></td>
  </tr>
  <tr>
    <td height="51" align="center" valign="top"><table width="650" height="47">
      <tr>
        <td><fieldset>
          <legend><strong>Kriteria Tapisan</strong>          </legend>
          <p align="center"></p>
          <p align="center">Jawatan :
            <label>
              <select name="select" id="select">
              </select>
            </label>
          </p>
          <p align="center">Kelulusan :
            <label>
              <select name="select2" id="select2">
              </select>
            </label>
            </p>
          <p align="center">Keputusan :
            <label>
              <select name="select3" id="select3">
              </select>
            </label>
            </p>
          <p align="center">Pengalaman (Tempoh Khidmat):
            <label>
              <select name="select4" id="select4">
              </select>
              <br />
              <br />
<input type="submit" name="cmdSubmit4" id="cmdSubmit4" value="Carian" />
              </label>
            </p>
          </fieldset></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="137" align="center"><table width="100%" border="0">
      <tr>
        <td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>SENARAI PERMOHONAN JAWATAN</strong></td>
	                  </tr>
      <tr>
        <td width="298" bgcolor="#E7E4E6"><strong>Nama Pemohon</strong></td>
        <td width="80" bgcolor="#E7E4E6"><strong>ICNO</strong></td>
        <td width="60" bgcolor="#E7E4E6"><strong>Tindakan</strong></td>
	                  </tr>
      <?php do { ?>
        <tr>
          <td height="30"><?php echo $row_rsPeribadi['f630nama']; ?></td>
          <td class="center"><?php echo $row_rsPeribadi['ICNO']; ?></td>
          <td class="center"></td>
	                    </tr>
        <?php } while ($row_rsPeribadi = mysql_fetch_assoc($rsPeribadi)); ?>
                    </table></td>
	              </tr>
	            </table>
                <p>&nbsp;<a href="<?php printf("%s?pageNum_rsPeribadi=%d%s", $currentPage, 0, $queryString_rsPeribadi); ?>"><img src="../../images/j_button2_first.png" alt="" /></a> <a href="<?php printf("%s?pageNum_rsPeribadi=%d%s", $currentPage, min($totalPages_rsPeribadi, $pageNum_rsPeribadi + 1), $queryString_rsPeribadi); ?>"><img src="../../images/j_button2_next.png" alt="" /></a> <a href="<?php printf("%s?pageNum_rsPeribadi=%d%s", $currentPage, max(0, $pageNum_rsPeribadi - 1), $queryString_rsPeribadi); ?>"><img src="../../images/j_button2_prev.png" alt="" /></a> <a href="<?php printf("%s?pageNum_rsPeribadi=%d%s", $currentPage, $totalPages_rsPeribadi, $queryString_rsPeribadi); ?>"><img src="../../images/j_button2_last.png" alt="" /></a> <br />Rekod <?php echo ($startRow_rsPeribadi + 1) ?> hingga <?php echo min($startRow_rsPeribadi + $maxRows_rsPeribadi, $totalRows_rsPeribadi) ?> daripada <?php echo $totalRows_rsPeribadi ?> rekod.</p>
</form>
	        </div>
	      </div>
</div>
		<!-- end #content -->
			  <div id="sidebar">
	  <ul>
	    <li><?php include("t_menu.php"); ?></li>
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

mysql_free_result($rsUser);

mysql_free_result($rsPeribadi);

mysql_free_result($rsPengalamanKerja);

mysql_free_result($rsAkademik);
?>
