<?php require_once('../../Connections/sqlconn.php'); ?>
<?php include("../../Common/common-topmenu.php"); ?>
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

$MM_restrictGoTo = "../../Administrator/noaccess.php";
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

/*$colname_esUser = "-1";
if (isset($_SESSION['f142username'])) {
  $colname_esUser = $_SESSION['f142username'];
}*/
//Carry Username and User Level
$username = $_SESSION['MM_Username'];
$userlevel = $_SESSION['MM_UserGroup'];

$colname_rsUser = "-1";
if (isset($_GET['icno'])) {
  $colname_rsUser = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142noIC = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$colname_rsMohonJawatan = "-1";
if (isset($_GET['icno'])) {
  $colname_rsMohonJawatan = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsMohonJawatan = sprintf("SELECT * FROM t144mohon_jawatan WHERE ICNO = %s AND jaw_kategori=1 ORDER BY mohonID ASC", GetSQLValueString($colname_rsMohonJawatan, "text"));
$rsMohonJawatan = mysql_query($query_rsMohonJawatan, $sqlconn) or die(mysql_error());
$row_rsMohonJawatan = mysql_fetch_assoc($rsMohonJawatan);
$totalRows_rsMohonJawatan = mysql_num_rows($rsMohonJawatan);

$colname_rsPeribadi = "-1";
if (isset($_GET['icno'])) {
  $colname_rsPeribadi = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPeribadi = sprintf("SELECT *, t100peribadi.f630daerahsm AS BANDAR, b.f109negaradesc AS NEGARASM,  t109kod_negerisemasa.f109desc NEGERISM,  t109kod_agama.f761desc AS AGAMA,  t109kod_warga.f109keterangan AS STATWARGA,  t109kod_nlahir.f109keterangan AS NegeriL,  t109kod_bumi.f109bumiDesc AS BUMIPUTRA,  t109kod_bangsa.f109desc AS BANGSA,  t109kod_kahwin.f109desc AS KAHWIN,  a.f109negaradesc AS NEGARAL FROM t100peribadi, t109kod_gelar, t109kod_nlahir, t109kod_negara a,  t109kod_negara b,  t109kod_negerisemasa, t109kod_agama, t109kod_warga, t109kod_bumi, t109kod_bangsa, t109kod_kahwin WHERE t100peribadi.f630kdgelar=t109kod_gelar.f786kdgelar    AND t100peribadi.f630kdnlahir=t109kod_nlahir.f109kdnegeri    AND t100peribadi.f630kdnegs= t109kod_negerisemasa.f109kdnegeri    AND t100peribadi.f630kdngrs=b.f109kdnegara    AND t100peribadi.f630kdagama = t109kod_agama.f761kod       AND t100peribadi.f630bumi=t109kod_bumi.f109kdBumi    AND t100peribadi.f630kdbangsa=t109kod_bangsa.f109kod    AND t100peribadi.f630kdkahwin=t109kod_kahwin.f109kod  AND t100peribadi.f630natstatuscd=t109kod_warga.f109kdwarga AND ICNO=%s AND t100peribadi.f630negaralahir=a.f109kdnegara", GetSQLValueString($colname_rsPeribadi, "text"));
$rsPeribadi = mysql_query($query_rsPeribadi, $sqlconn) or die(mysql_error());
$row_rsPeribadi = mysql_fetch_assoc($rsPeribadi);
$totalRows_rsPeribadi = mysql_num_rows($rsPeribadi);

$maxRows_rsAkademik = 10;
$pageNum_rsAkademik = 0;
if (isset($_GET['pageNum_rsAkademik'])) {
  $pageNum_rsAkademik = $_GET['pageNum_rsAkademik'];
}
$startRow_rsAkademik = $pageNum_rsAkademik * $maxRows_rsAkademik;

$colname_rsAkademik = "-1";
if (isset($_GET['icno'])) {
  $colname_rsAkademik = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsAkademik = sprintf("SELECT * FROM t100akademik, t109kod_institusi, t109kod_layak WHERE t100akademik.f100kdInst=t109kod_institusi.f777kdinst AND t100akademik.f100kdlayak=t109kod_layak.f109kod AND t100akademik.f100ICNO=%s", GetSQLValueString($colname_rsAkademik, "text"));
$query_limit_rsAkademik = sprintf("%s LIMIT %d, %d", $query_rsAkademik, $startRow_rsAkademik, $maxRows_rsAkademik);
$rsAkademik = mysql_query($query_limit_rsAkademik, $sqlconn) or die(mysql_error());
$row_rsAkademik = mysql_fetch_assoc($rsAkademik);

if (isset($_GET['totalRows_rsAkademik'])) {
  $totalRows_rsAkademik = $_GET['totalRows_rsAkademik'];
} else {
  $all_rsAkademik = mysql_query($query_rsAkademik);
  $totalRows_rsAkademik = mysql_num_rows($all_rsAkademik);
}
$totalPages_rsAkademik = ceil($totalRows_rsAkademik/$maxRows_rsAkademik)-1;

$maxRows_rsResult = 10;
$pageNum_rsResult = 0;
if (isset($_GET['pageNum_rsResult'])) {
  $pageNum_rsResult = $_GET['pageNum_rsResult'];
}
$startRow_rsResult = $pageNum_rsResult * $maxRows_rsResult;

$colname_rsResult = "-1";
if (isset($_GET['icno'])) {
  $colname_rsResult = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsResult = sprintf("SELECT * FROM t100akademikresult, t109kod_peringkat WHERE t100akademikresult.f100kelulusan=t109kod_peringkat.f109kdperingkat AND  f100ICNO = %s", GetSQLValueString($colname_rsResult, "text"));
$query_limit_rsResult = sprintf("%s LIMIT %d, %d", $query_rsResult, $startRow_rsResult, $maxRows_rsResult);
$rsResult = mysql_query($query_limit_rsResult, $sqlconn) or die(mysql_error());
$row_rsResult = mysql_fetch_assoc($rsResult);

if (isset($_GET['totalRows_rsResult'])) {
  $totalRows_rsResult = $_GET['totalRows_rsResult'];
} else {
  $all_rsResult = mysql_query($query_rsResult);
  $totalRows_rsResult = mysql_num_rows($all_rsResult);
}
$totalPages_rsResult = ceil($totalRows_rsResult/$maxRows_rsResult)-1;

$maxRows_rsPengalaman = 10;
$pageNum_rsPengalaman = 0;
if (isset($_GET['pageNum_rsPengalaman'])) {
  $pageNum_rsPengalaman = $_GET['pageNum_rsPengalaman'];
}
$startRow_rsPengalaman = $pageNum_rsPengalaman * $maxRows_rsPengalaman;

$colname_rsPengalaman = "-1";
if (isset($_GET['icno'])) {
  $colname_rsPengalaman = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPengalaman = sprintf("SELECT * FROM t100pengalaman WHERE ICNO = %s", GetSQLValueString($colname_rsPengalaman, "text"));
$query_limit_rsPengalaman = sprintf("%s LIMIT %d, %d", $query_rsPengalaman, $startRow_rsPengalaman, $maxRows_rsPengalaman);
$rsPengalaman = mysql_query($query_limit_rsPengalaman, $sqlconn) or die(mysql_error());
$row_rsPengalaman = mysql_fetch_assoc($rsPengalaman);

if (isset($_GET['totalRows_rsPengalaman'])) {
  $totalRows_rsPengalaman = $_GET['totalRows_rsPengalaman'];
} else {
  $all_rsPengalaman = mysql_query($query_rsPengalaman);
  $totalRows_rsPengalaman = mysql_num_rows($all_rsPengalaman);
}
$totalPages_rsPengalaman = ceil($totalRows_rsPengalaman/$maxRows_rsPengalaman)-1;

$maxRows_rsMaklumatBahasa = 10;
$pageNum_rsMaklumatBahasa = 0;
if (isset($_GET['pageNum_rsMaklumatBahasa'])) {
  $pageNum_rsMaklumatBahasa = $_GET['pageNum_rsMaklumatBahasa'];
}
$startRow_rsMaklumatBahasa = $pageNum_rsMaklumatBahasa * $maxRows_rsMaklumatBahasa;

$colname_rsMaklumatBahasa = "-1";
if (isset($_GET['icno'])) {
  $colname_rsMaklumatBahasa = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsMaklumatBahasa = sprintf("SELECT * FROM t100bahasa, t109kod_bahasa WHERE t100bahasa.LangCd=t109kod_bahasa.LangCd AND t100bahasa.ICNO= %s", GetSQLValueString($colname_rsMaklumatBahasa, "text"));
$query_limit_rsMaklumatBahasa = sprintf("%s LIMIT %d, %d", $query_rsMaklumatBahasa, $startRow_rsMaklumatBahasa, $maxRows_rsMaklumatBahasa);
$rsMaklumatBahasa = mysql_query($query_limit_rsMaklumatBahasa, $sqlconn) or die(mysql_error());
$row_rsMaklumatBahasa = mysql_fetch_assoc($rsMaklumatBahasa);

if (isset($_GET['totalRows_rsMaklumatBahasa'])) {
  $totalRows_rsMaklumatBahasa = $_GET['totalRows_rsMaklumatBahasa'];
} else {
  $all_rsMaklumatBahasa = mysql_query($query_rsMaklumatBahasa);
  $totalRows_rsMaklumatBahasa = mysql_num_rows($all_rsMaklumatBahasa);
}
$totalPages_rsMaklumatBahasa = ceil($totalRows_rsMaklumatBahasa/$maxRows_rsMaklumatBahasa)-1;

$maxRows_rsTrengkas = 10;
$pageNum_rsTrengkas = 0;
if (isset($_GET['pageNum_rsTrengkas'])) {
  $pageNum_rsTrengkas = $_GET['pageNum_rsTrengkas'];
}
$startRow_rsTrengkas = $pageNum_rsTrengkas * $maxRows_rsTrengkas;

$colname_rsTrengkas = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsTrengkas = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTrengkas = sprintf("SELECT * FROM t109kod_bahasa, t100trengkas WHERE t100trengkas.f100bahasa=t109kod_bahasa.LangCd AND f100ICNO=%s", GetSQLValueString($colname_rsTrengkas, "text"));
$query_limit_rsTrengkas = sprintf("%s LIMIT %d, %d", $query_rsTrengkas, $startRow_rsTrengkas, $maxRows_rsTrengkas);
$rsTrengkas = mysql_query($query_limit_rsTrengkas, $sqlconn) or die(mysql_error());
$row_rsTrengkas = mysql_fetch_assoc($rsTrengkas);

if (isset($_GET['totalRows_rsTrengkas'])) {
  $totalRows_rsTrengkas = $_GET['totalRows_rsTrengkas'];
} else {
  $all_rsTrengkas = mysql_query($query_rsTrengkas);
  $totalRows_rsTrengkas = mysql_num_rows($all_rsTrengkas);
}
$totalPages_rsTrengkas = ceil($totalRows_rsTrengkas/$maxRows_rsTrengkas)-1;

$maxRows_rsKomputer = 10;
$pageNum_rsKomputer = 0;
if (isset($_GET['pageNum_rsKomputer'])) {
  $pageNum_rsKomputer = $_GET['pageNum_rsKomputer'];
}
$startRow_rsKomputer = $pageNum_rsKomputer * $maxRows_rsKomputer;

$colname_rsKomputer = "-1";
if (isset($_GET['icno'])) {
  $colname_rsKomputer = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsKomputer = sprintf("SELECT * FROM t100komputer WHERE f100ICNO = %s", GetSQLValueString($colname_rsKomputer, "text"));
$query_limit_rsKomputer = sprintf("%s LIMIT %d, %d", $query_rsKomputer, $startRow_rsKomputer, $maxRows_rsKomputer);
$rsKomputer = mysql_query($query_limit_rsKomputer, $sqlconn) or die(mysql_error());
$row_rsKomputer = mysql_fetch_assoc($rsKomputer);

if (isset($_GET['totalRows_rsKomputer'])) {
  $totalRows_rsKomputer = $_GET['totalRows_rsKomputer'];
} else {
  $all_rsKomputer = mysql_query($query_rsKomputer);
  $totalRows_rsKomputer = mysql_num_rows($all_rsKomputer);
}
$totalPages_rsKomputer = ceil($totalRows_rsKomputer/$maxRows_rsKomputer)-1;

$maxRows_rsLesen = 10;
$pageNum_rsLesen = 0;
if (isset($_GET['pageNum_rsLesen'])) {
  $pageNum_rsLesen = $_GET['pageNum_rsLesen'];
}
$startRow_rsLesen = $pageNum_rsLesen * $maxRows_rsLesen;

$colname_rsLesen = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsLesen = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsLesen = sprintf("SELECT * FROM t100lesen, t109kod_lesen, t109kod_jenislesen WHERE t100lesen.LicClassCd=t109kod_lesen.LicClassCd AND t100lesen.LicCD=t109kod_jenislesen.LicCD AND t100lesen.ICNO= %s", GetSQLValueString($colname_rsLesen, "text"));
$query_limit_rsLesen = sprintf("%s LIMIT %d, %d", $query_rsLesen, $startRow_rsLesen, $maxRows_rsLesen);
$rsLesen = mysql_query($query_limit_rsLesen, $sqlconn) or die(mysql_error());
$row_rsLesen = mysql_fetch_assoc($rsLesen);

if (isset($_GET['totalRows_rsLesen'])) {
  $totalRows_rsLesen = $_GET['totalRows_rsLesen'];
} else {
  $all_rsLesen = mysql_query($query_rsLesen);
  $totalRows_rsLesen = mysql_num_rows($all_rsLesen);
}
$totalPages_rsLesen = ceil($totalRows_rsLesen/$maxRows_rsLesen)-1;

$maxRows_rsRujukan = 10;
$pageNum_rsRujukan = 0;
if (isset($_GET['pageNum_rsRujukan'])) {
  $pageNum_rsRujukan = $_GET['pageNum_rsRujukan'];
}
$startRow_rsRujukan = $pageNum_rsRujukan * $maxRows_rsRujukan;

$colname_rsRujukan = "-1";
if (isset($_GET['icno'])) {
  $colname_rsRujukan = $_GET['icno'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsRujukan = sprintf("SELECT * FROM t100rujukan WHERE f100ICNO = %s", GetSQLValueString($colname_rsRujukan, "text"));
$query_limit_rsRujukan = sprintf("%s LIMIT %d, %d", $query_rsRujukan, $startRow_rsRujukan, $maxRows_rsRujukan);
$rsRujukan = mysql_query($query_limit_rsRujukan, $sqlconn) or die(mysql_error());
$row_rsRujukan = mysql_fetch_assoc($rsRujukan);

if (isset($_GET['totalRows_rsRujukan'])) {
  $totalRows_rsRujukan = $_GET['totalRows_rsRujukan'];
} else {
  $all_rsRujukan = mysql_query($query_rsRujukan);
  $totalRows_rsRujukan = mysql_num_rows($all_rsRujukan);
}
$totalPages_rsRujukan = ceil($totalRows_rsRujukan/$maxRows_rsRujukan)-1;

$colname_rsBahasa = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsBahasa = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsBahasa = sprintf("SELECT * FROM t100bahasa WHERE ICNO = %s", GetSQLValueString($colname_rsBahasa, "text"));
$rsBahasa = mysql_query($query_rsBahasa, $sqlconn) or die(mysql_error());
$row_rsBahasa = mysql_fetch_assoc($rsBahasa);
$totalRows_rsBahasa = mysql_num_rows($rsBahasa);

$colname_rsPenyelidikan = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsPenyelidikan = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPenyelidikan = sprintf("SELECT * FROM t100penyelidikan WHERE f100ICNO = %s", GetSQLValueString($colname_rsPenyelidikan, "text"));
$rsPenyelidikan = mysql_query($query_rsPenyelidikan, $sqlconn) or die(mysql_error());
$row_rsPenyelidikan = mysql_fetch_assoc($rsPenyelidikan);
$totalRows_rsPenyelidikan = mysql_num_rows($rsPenyelidikan);

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
<link href="../../Administrator/Common/style-red.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
<!--
body,td,th {
	color: #000;
}
#page #page-bgtop table tr td .post .entry #frmPeribadi table {
	text-align: center;
	font-size: 12px;
}
.BIGTITLE {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 24px;
	font-weight: bold;
	text-transform: uppercase;
	text-align: center;
	vertical-align: middle;
}
#page #page-bgtop table tr td .post .entry #frmPeribadi table tr td table {
	text-align: center;
}
#page #page-bgtop table tr td .post .entry #frmPeribadi table tr td table {
	font-weight: bold;
	font-size: 12px;
}
.tengah {
	text-align: center;
	vertical-align: middle;
}
#page #page-bgtop table tr td .post .entry #frmPeribadi table tr td strong {
	font-size: 10px;
}
#page #page-bgtop table tr td .post .entry #frmPeribadi table tr td table {
	font-size: 10px;
}
.tajukBrg {
	font-size: 18px;
	font-weight: bold;
	color: #059FE6;
}
.tajuklbrg {	font-size: 18px;
	font-weight: bold;
	color: #000;
}
.bigjawatan {
	font-size: 14px;
}
.fakultiBidang {
	font-family: "Arial Black", Gadget, sans-serif;
	font-size: 12px;
	font-style: italic;
	font-weight: bold;
	font-variant: normal;
	color: #FFF;
}
.fakultiBidang {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: italic;
	font-weight: bold;
	color: #FFF;
}
.fakultiBidang {
	font-family: "Arial Black", Gadget, sans-serif;
	font-size: 12px;
	font-style: italic;
	font-weight: bold;
	color: #333;
}
.fakultiBidang {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: italic;
	font-weight: normal;
	color: #000;
}
.size12 {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #000;
}
.size11Arial {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: bold;
	color: #000;
}
.arial11nonbold {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	color: #000;
}
.aaaa {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #000;
}
.ARIALsize14bold {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #000;
}
.ARIALsize12 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #000;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
		function cetak()
		{
				window.print();
		}
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
		function validate(frmPeribadi) 
		{
			if (frmPeribadi.listwarganegara.value == "---Sila Pilih---")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.listwarganegara.focus();
				return false;	
			}
			
			if (frmPeribadi.listbangsa.value == "---Sila Pilih---")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.listbangsa.focus();
				return false;	
			}
			
			
			if (frmPeribadi.listagama.value == "---Sila Pilih---")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.listagama.focus();
				return false;	
			}
			
			if (frmPeribadi.txtposkod.value == "")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.txtposkod.focus();
				return false;	
			}
			
			if (frmPeribadi.listnlahir2.value == "---Sila Pilih---")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.listnlahir2.focus();
				return false;	
			}
			
			if (frmPeribadi.listkahwin.value == "---Sila Pilih---")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.listkahwin.focus();
				return false;	
			}
			
			if (frmPeribadi.listjantina.value == "---Sila Pilih---")
			{
				alert("Sila isikan maklumat yang bertanda bintang");
				frmPeribadi.listjantina.focus();
				return false;	
			}
			
			
			
		}
</script>
<SCRIPT language=javascript>
<!--
<!-- Begin  //place these scripts within BODY tag if you are using IE 4.0 or below.
//****************************************************************************
// PopCalendar 3.30, Emailware(please mail&commend me if u like it)
// Originally coded by Liming(Victor) Weng, email: victorwon@netease.com
// Release date: 2000.3.7
// Anyone may modify it to satify his needs, but please leave this comment ahead.
//****************************************************************************

var gdCtrl = new Object();
var goSelectTag = new Array();
var gcGray = "#808080";
var gcToggle = "#ffff00";
var gcBG = "#cccccc";

var gdCurDate = new Date();
var giYear = gdCurDate.getFullYear();
var giMonth = gdCurDate.getMonth()+1;
var giDay = gdCurDate.getDate();

function fSetDate(iYear, iMonth, iDay){
  VicPopCal.style.visibility = "hidden";
  gdCtrl.value = iDay+"/"+iMonth+"/"+iYear; //Here, you could modify the locale as you need !!!!
  for (i in goSelectTag)
  	goSelectTag[i].style.visibility = "visible";
  goSelectTag.length = 0;
}

function fSetSelected(aCell){
  var iOffset = 0;
  var iYear = parseInt(tbSelYear.value);
  var iMonth = parseInt(tbSelMonth.value);

  self.event.cancelBubble = true;
  aCell.bgColor = gcBG;
  with (aCell.children["cellText"]){
  	var iDay = parseInt(innerText);
  	if (color==gcGray)
		iOffset = (Victor<10)?-1:1;
	iMonth += iOffset;
	if (iMonth<1) {
		iYear--;
		iMonth = 12;
	}else if (iMonth>12){
		iYear++;
		iMonth = 1;
	}
  }
  fSetDate(iYear, iMonth, iDay);
}

function Point(iX, iY){
	this.x = iX;
	this.y = iY;
}

function fBuildCal(iYear, iMonth) {
  var aMonth=new Array();
  for(i=1;i<7;i++)
  	aMonth[i]=new Array(i);

  var dCalDate=new Date(iYear, iMonth-1, 1);
  var iDayOfFirst=dCalDate.getDay();
  var iDaysInMonth=new Date(iYear, iMonth, 0).getDate();
  var iOffsetLast=new Date(iYear, iMonth-1, 0).getDate()-iDayOfFirst+1;
  var iDate = 1;
  var iNext = 1;

  for (d = 0; d < 7; d++)
	aMonth[1][d] = (d<iDayOfFirst)?-(iOffsetLast+d):iDate++;
  for (w = 2; w < 7; w++)
  	for (d = 0; d < 7; d++)
		aMonth[w][d] = (iDate<=iDaysInMonth)?iDate++:-(iNext++);
  return aMonth;
}

function fDrawCal(iYear, iMonth, iCellHeight, iDateTextSize) {
  var WeekDay = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
  var styleTD = " bgcolor='"+gcBG+"' bordercolor='"+gcBG+"' valign='middle' align='center' height='"+iCellHeight+"' style='font:bold "+iDateTextSize+" verdana;";            //Coded by Liming Weng(Victor Won)  email:victorwon@netease.com

  with (document) {
	write("<tr>");
	for(i=0; i<7; i++)
		write("<td "+styleTD+"color:#990099' >" + WeekDay[i] + "</td>");
	write("</tr>");

  	for (w = 1; w < 7; w++) {
		write("<tr>");
		for (d = 0; d < 7; d++) {
			write("<td id=calCell "+styleTD+"cursor:hand;' onMouseOver='this.bgColor=gcToggle' onMouseOut='this.bgColor=gcBG' onclick='fSetSelected(this)'>");
			write("<font id=cellText Victor='Liming Weng'> </font>");
			write("</td>")
		}
		write("</tr>");
	}
  }
}

function fUpdateCal(iYear, iMonth) {
  myMonth = fBuildCal(iYear, iMonth);
  var i = 0;
  for (w = 0; w < 6; w++)
	for (d = 0; d < 7; d++)
		with (cellText[(7*w)+d]) {
			Victor = i++;
			if (myMonth[w+1][d]<0) {
				color = gcGray;
				innerText = -myMonth[w+1][d];
			}else{
				color = ((d==0)||(d==6))?"red":"black";
				innerText = myMonth[w+1][d];
			}
		}
}

function fSetYearMon(iYear, iMon){
  tbSelMonth.options[iMon-1].selected = true;
  for (i = 0; i < tbSelYear.length; i++)
	if (tbSelYear.options[i].value == iYear)
		tbSelYear.options[i].selected = true;
  fUpdateCal(iYear, iMon);
}

function fPrevMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (--iMon<1) {
	  iMon = 12;
	  iYear--;
  }

  fSetYearMon(iYear, iMon);
}

function fNextMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (++iMon>12) {
	  iMon = 1;
	  iYear++;
  }

  fSetYearMon(iYear, iMon);
}

function fToggleTags(){
  with (document.all.tags("SELECT")){
 	for (i=0; i<length; i++)
 		if ((item(i).Victor!="Won")&&fTagInBound(item(i))){
 			item(i).style.visibility = "hidden";
 			goSelectTag[goSelectTag.length] = item(i);
 		}
  }
}

function fTagInBound(aTag){
  with (VicPopCal.style){
  	var l = parseInt(left);
  	var t = parseInt(top);
  	var r = l+parseInt(width);
  	var b = t+parseInt(height);
	var ptLT = fGetXY(aTag);
	return !((ptLT.x>r)||(ptLT.x+aTag.offsetWidth<l)||(ptLT.y>b)||(ptLT.y+aTag.offsetHeight<t));
  }
}

function fGetXY(aTag){
  var oTmp = aTag;
  var pt = new Point(0,0);
  do {
  	pt.x += oTmp.offsetLeft;
  	pt.y += oTmp.offsetTop;
  	oTmp = oTmp.offsetParent;
  } while(oTmp.tagName!="BODY");
  return pt;
}

// Main: popCtrl is the widget beyond which you want this calendar to appear;
//       dateCtrl is the widget into which you want to put the selected date.
// i.e.: <input type="text" name="dc" style="text-align:center" readonly><INPUT type="button" value="V" onclick="fPopCalendar(dc,dc);return false">
function fPopCalendar(popCtrl, dateCtrl){
  gdCtrl = dateCtrl;
  fSetYearMon(giYear, giMonth);
  var point = fGetXY(popCtrl);
  with (VicPopCal.style) {
  	left = point.x;
	top  = point.y+popCtrl.offsetHeight+1;
	width = VicPopCal.offsetWidth;
	height = VicPopCal.offsetHeight;
	fToggleTags(point);
	visibility = 'visible';
  }
  VicPopCal.focus();
}

function fHideCal(){
  var oE = window.event;
  if ((oE.clientX>0)&&(oE.clientY>0)&&(oE.clientX<document.body.clientWidth)&&(oE.clientY<document.body.clientHeight)) {
	var oTmp = document.elementFromPoint(oE.clientX,oE.clientY);
	while ((oTmp.tagName!="BODY") && (oTmp.id!="VicPopCal"))
		oTmp = oTmp.offsetParent;
	if (oTmp.id=="VicPopCal")
		return;
  }
  VicPopCal.style.visibility = 'hidden';
  for (i in goSelectTag)
	goSelectTag[i].style.visibility = "visible";
  goSelectTag.length = 0;
}

var gMonths = new Array("January","February","March","April","May","June","July","August","September","October","November","December");

with (document) {
write("<DIV id='VicPopCal' onblur='fHideCal()' onclick='focus()' style='POSITION:absolute;visibility:hidden;border:1px ridge;height:10;width:10;z-index:100;'>");
write("<table border='0' bgcolor='#6699cc'>");
write("<TR>");
write("<td valign='middle' align='center'><input type='button' name='PrevMonth' value='<' style='height:20;width:20;FONT:16 Fixedsys' onClick='fPrevMonth()' onblur='fHideCal()'>");
write("&nbsp;&nbsp;<select name='tbSelMonth' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won' onclick='self.event.cancelBubble=true' onblur='fHideCal()'>");
for (i=0; i<12; i++)
	write("<option value='"+(i+1)+"'>"+gMonths[i]+"</option>");
write("</SELECT>");
write("&nbsp;&nbsp;<SELECT name='tbSelYear' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won' onclick='self.event.cancelBubble=true' onblur='fHideCal()'>");
for(i=1940;i<2015;i++)
	write("<OPTION value='"+i+"'>&nbsp;&nbsp;"+i+"&nbsp;&nbsp;</OPTION>");
write("</SELECT>");
write("&nbsp;&nbsp;<input type='button' name='PrevMonth' value='>' style='height:20;width:20;FONT:16 Fixedsys' onclick='fNextMonth()' onblur='fHideCal()'>");
write("</td>");
write("</TR><TR>");
write("<td align='center'>");
write("<DIV style='background-color:teal;'><table width='100%' height='100%' border='0'>");
fDrawCal(giYear, giMonth, 10, 11);
write("</table></DIV>");
write("</td>");
write("</TR><TR><TD align='center'>");
write("<font face=Verdana size=1><B style='cursor:hand' onclick='fSetDate(giYear,giMonth,giDay); self.event.cancelBubble=true' onMouseOver='this.style.color=gcToggle' onMouseOut='this.style.color=0'>Today:&nbsp;&nbsp;"+gMonths[giMonth-1]+"&nbsp;"+giDay+",&nbsp;&nbsp;"+giYear+"</B></font>");
write("</TD></TR>");write("</TD></TR>");
write("</TABLE></Div>");
}
// End -- Coded by Liming Weng, email: victorwon@netease.com -->

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</SCRIPT>
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
<body class="aaaa">
	<div id="page">
	<div id="page-bgtop">
	  <table width="800" height="100%" border="0" align="center" cellspacing="0">
	    <tr>
	      <td width="156" rowspan="2" bgcolor="#FFFFFF"><img src="../../images/logotip.jpg" alt="" width="136" height="130" /></td>
	      <td width="718" rowspan="2" bgcolor="#FFFFFF" class="BIGTITLE">     UNIVERSITI PERTAHANAN NASIONAL MALAYSIA<br /><br />
          <span class="ARIALsize14bold">BORANG JAWATAN AKADEMIK</span></td>
	      <td width="124" bgcolor="#FFFFFF" class="tengah">UPNM/PEND-01</td>
        </tr>
	    <tr>
	      <td height="113" bgcolor="#FFFFFF" class="tengah"><?php
								//$str_link = "../profile/profile_avatar.php";
								$str_link = "#";
							?>
            <a href="<?php echo $str_link; ?>" target="_self">
            <?php
                            $str = $row_rsUser['f142photo'];
							$strnoimage = "noimage.png";

                            if ($str == "")
							{
								echo"<img src=\"../../Passport/$strnoimage\" width=\"82\" height=\"100\" >";
								echo "<br>";
								
							}
                            else 
							{
								echo"<img src=\"../../Passport/$str\" width=\"82\" height=\"100\" >";
								echo "<br>";
								
							}
                            ?>
          </a><br /></td>
        </tr>
	    <tr>
	      <td colspan="3" bgcolor="#FFFFFF"><div class="post">
	        <div class="entry">
	          <form method="post" enctype="multipart/form-data" id="frmPeribadi">
	            <table width="100%" border="0">
	              <tr>
	                <td width="100%" colspan="3"><table width="815" border="0" cellspacing="0" height="80">
	                  <tr>
	                    <td width="100%" height="80" align="left" valign="top" bgcolor="#CCCCCC" class="bigjawatan"><?php do { ?>
	                      <div align="center"><span class="ARIALsize14bold"><br /><?php echo "JAWATAN " .$row_rsMohonJawatan['recordID']. ":"; ?> <?php echo $row_rsMohonJawatan['jawatanDesc']; ?> - <?php echo $row_rsMohonJawatan['jawatanGred']; ?><br />
	                        Fakulti : <?php echo $row_rsMohonJawatan['fakulti']; ?> <br />
Bidang : <?php echo $row_rsMohonJawatan['bidang']; ?> <br />
	                        <input name="hdnICNo" type="hidden" id="hdnICNo" value="<?php echo $row_rsUser['f142noIC']; ?>" />
	                        <input name="hdnPeribadiICNO" type="hidden" id="hdnPeribadiICNO" value="<?php echo $row_rsPeribadi['ICNO']; ?>" />
	                        </span><br />
	                        </div>
	                      <?php } while ($row_rsMohonJawatan = mysql_fetch_assoc($rsMohonJawatan)); ?></td>
	                    </tr>
                    </table></td>
                  </tr>
	              <tr>
	                <td height="180" colspan="3">
                     
	                  <table width="100%" border="0">
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">NAMA :</span></td>
						  <td width="80%" height="25" colspan="3" align="left" class="cetak"> <span class="ARIALsize12"><?php echo $row_rsPeribadi['f786desc']; ?> <?php echo $row_rsUser['f142Name']; ?> <?php echo $row_rsPeribadi['JANTINA']; ?></span></td>
	                      </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">NO KAD PENGENALAN :</span></td>
	                      <td width="80%" height="25" colspan="3" align="left" class="cetak"><span class="ARIALsize12"><?php echo $row_rsUser['f142noIC']; ?></span></td>
                        </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">ALAMAT :</span></td>
	                      <td width="80%" height="25" colspan="3" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f630almtsm1']; ?>, <?php echo $row_rsPeribadi['f630almtsm2']; ?>,<?php echo $row_rsPeribadi['f630almtsm3']; ?>,</span></td>
	                      </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">POSKOD :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f630poskods']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">BANDAR :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['BANDAR']; ?></span></td>
	                      </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">NEGERI :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['NEGERISM']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">NEGARA :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['NEGARASM']; ?></span></td>
                        </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">NO TEL (H/P) :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f630hphone']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">NO TEL (PEJABAT) :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f631notelt']; ?></span></td>
                        </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">EMEL :</span></td>
	                      <td width="80%" height="25" colspan="2" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f630email']; ?></span></td>
	                      <td align="left">&nbsp;</td>
                        </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">TARIKH LAHIR :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f630thlahir']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">TEMPAT LAHIR :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['NegeriL']; ?></span></td>
	                      </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">UMUR :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['f630Umur']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">AGAMA :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['AGAMA']; ?></span></td>
	                      </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">WARGANEGARA :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['STATWARGA']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">STATUS BUMIPUTRA :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['BUMIPUTRA']; ?></span></td>
	                      </tr>
	                    <tr>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">BANGSA :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['BANGSA']; ?></span></td>
	                      <td width="20%" height="25" align="right"><span class="ARIALsize12">TARAF  PERKAHWINAN :</span></td>
	                      <td width="30%" height="25" align="left"><span class="ARIALsize12"><?php echo $row_rsPeribadi['KAHWIN']; ?></span></td>
	                      </tr>
	                    </table>
	               </td>
                  </tr>
                  <br /><br />
	              <tr>
	                <td height="64" colspan="3"><fieldset>
                      <legend>MAKLUMAT PENDIDIKAN<strong>
                      <input name="hdnPendidikanICNO" type="hidden" id="hdnPendidikanICNO" value="<?php echo $row_rsAkademik['f100ICNO']; ?>" />
                      </strong>                      </legend>
	                  <table width="100%" border="1" cellspacing="0">
	                    <tr class="ARIALsize14bold" height="30">
	                      <td width="40%" bgcolor="#E9E9E9" class="ARIALsize14bold">SEKOLAH/INSTITUSI/UNIVERSITI</td>
	                      <td width="15%" bgcolor="#E9E9E9">KELULUSAN</td>
	                      <td width="15%" bgcolor="#E9E9E9">KELULUSAN</td>
	                      <td width="15%" bgcolor="#E9E9E9">GRED</td>
	                      <td width="15%" bgcolor="#E9E9E9">TARIKH KURNIA</td>
	                      </tr>
	                    <?php do { ?>
	                    <tr class="ARIALsize12" height="30">
	                      <td class="left" height="12"><?php echo $row_rsAkademik['f777desc']; ?></td>
	                      <td><?php echo $row_rsAkademik['f109desc']; ?></td>
	                      <td><?php echo $row_rsAkademik['f100kdbidang']; ?></td>
	                      <td><?php echo $row_rsAkademik['f100cgpa']; ?></td>
	                      <td><?php echo $row_rsAkademik['f100thkuasa']; ?></td>
	                      </tr>
	                    <?php } while ($row_rsAkademik = mysql_fetch_assoc($rsAkademik)); ?>
	                    </table>
	                </fieldset></td>
                  </tr>
	              <tr>
	                <td height="64" colspan="3"><fieldset>
                      <legend>KEPUTUSAN AKADEMIK <em>(bagi matapelajaran wajib)</em>
                      <input name="hdnResultICNO" type="hidden" id="hdnResultICNO" value="<?php echo $row_rsResult['']; ?>
	                &lt;/strong&gt;&lt;/td&gt;
                  &lt;/tr&gt;
	              &lt;tr&gt;
	                &lt;td height="20" colspan="4" />
	                </legend>
	                  <table width="100%" height="34" border="1" cellspacing="0">
	                    <tr class="ARIALsize14bold" height="30">
	                      <td width="40%" height="16" bgcolor="#E9E9E9">KELULUSAN</td>
	                      <td width="20%" bgcolor="#E9E9E9">BM - Gred</td>
	                      <td width="20%" bgcolor="#E9E9E9">BI -Gred</td>
	                      <td width="20%" bgcolor="#E9E9E9">MATH -Gred</td>
	                      </tr>
	                    <?php do { ?>
	                    <tr class="ARIALsize12" height="30">
	                      <td height="16" align="center" bgcolor="#FFFFFF"><?php echo $row_rsResult['f109keterangan']; ?></td>
	                      <td bgcolor="#FFFFFF" class="cetak"><?php echo $row_rsResult['f100GredBM']; ?></td>
	                      <td bgcolor="#FFFFFF" class="cetak"><?php echo $row_rsResult['f100GredBI']; ?></td>
	                      <td bgcolor="#FFFFFF" class="cetak"><?php echo $row_rsResult['f100GredMath']; ?></td>
	                      </tr>
	                    <?php } while ($row_rsResult = mysql_fetch_assoc($rsResult)); ?>
	                    </table>
	                </fieldset></td>
                  </tr>
	              <tr>
	                <td height="20" colspan="3"><fieldset>
	                  <legend>PENGALAMAN KERJA<span class="cetak"><strong>
	                  <input name="hdnPengalamanICNO" type="hidden" id="hdnPengalamanICNO" value="<?php echo $row_rsPengalaman['']; ?>
                  &lt;/tr&gt;
	              &lt;tr&gt;
	                &lt;td height="20" colspan="4" />
	                  </strong></span></legend>
	                  <table width="100%" border="1" cellspacing="0">
	                    <tr class="ARIALsize14bold" height="30">
	                      <td width="60%" bgcolor="#E9E9E9"><span class="cetak">NAMA ORGANISASI</span></td>
	                      <td width="20%" bgcolor="#E9E9E9">TARIKH BERKHIDMAT</td>
	                      <td width="20%" bgcolor="#E9E9E9">TARIKH TAMAT</td>
	                      </tr>
	                    <?php do { ?>
	                    <tr class="ARIALsize12" height="30">
	                      <td><?php echo $row_rsPengalaman['OrgNm']; ?></td>
	                      <td><span class="cetak"><?php echo $row_rsPengalaman['PrevEmpStartDt']; ?></span></td>
	                      <td><?php echo $row_rsPengalaman['PrevEmpEndDt']; ?></td>
	                      </tr>
	                    <?php } while ($row_rsPengalaman = mysql_fetch_assoc($rsPengalaman)); ?>
	                    </table>
	                </fieldset></td>
                  </tr>
	              <tr>
	                <td height="20" colspan="3"><fieldset>
                      <legend>KEMAHIRAN BAHASA<strong>
                      <input name="hdnBahasaICNO" type="hidden" id="hdnBahasaICNO" value="<?php echo $row_rsBahasa['']; ?>
                  &lt;/tr&gt;
	              &lt;tr&gt;
	                &lt;td height="20" colspan="4" />
	                  </strong>	                  </legend>
	                  <table width="100%" height="38" border="1" cellspacing="0">
	                    <tr height="30">
	                      <td width="60%" height="20" bgcolor="#E9E9E9"><span class="ARIALsize14bold">BAHASA</span></td>
	                      <td width="20%" bgcolor="#E9E9E9"><span class="ARIALsize14bold">TAHAP TULISAN</span></td>
	                      <td width="20%" bgcolor="#E9E9E9"><span class="ARIALsize14bold">TAHAP PERTUTURAN</span></td>
	                      </tr>
	                    <?php do { ?>
	                    <tr class="ARIALsize12" height="30">
	                      <td height="16" align="center" bgcolor="#FFFFFF"><?php echo $row_rsMaklumatBahasa['Lang']; ?></td>
	                      <td align="center" bgcolor="#FFFFFF"><span class="cetak"><?php echo $row_rsMaklumatBahasa['LangSkillWritten']; ?></span></td>
	                      <td align="center" bgcolor="#FFFFFF"><span class="cetak"><?php echo $row_rsMaklumatBahasa['LangSkillOral']; ?></span></td>
	                      </tr>
	                    <?php } while ($row_rsMaklumatBahasa = mysql_fetch_assoc($rsMaklumatBahasa)); ?>
	                    </table>
	                </fieldset></td>
                  </tr>
	             
	              <tr>
	                <td height="26" colspan="3"><fieldset>
                      <legend>KEMAHIRAN KOMPUTER<span class="cetak"><strong>
                      <input name="hdnKomputerICNO" type="hidden" id="hdnKomputerICNO" value="<?php echo $row_rsKomputer['']; ?>
                  &lt;/tr&gt;
	              &lt;tr&gt;
	                &lt;td height="20" colspan="4" />
	                  </strong></span>	                  </legend>
	                  <table width="100%" height="35" border="1" cellspacing="0">
	                    <tr class="ARIALsize14bold" height="30">
	                      <td width="60%" height="17" bgcolor="#E9E9E9"><span class="cetak">JENIS KEMAHIRAN</span></td>
	                      <td width="40%" bgcolor="#E9E9E9"><span class="cetak">TAHAP KEMAHIRAN</span></td>
	                      </tr>
	                    <?php do { ?>
	                    <tr align="center" class="ARIALsize12" height="30">
	                      <td height="16" bgcolor="#FFFFFF"><?php echo $row_rsKomputer['f100KemahiranKomp']; ?></td>
	                      <td bgcolor="#FFFFFF"><?php echo $row_rsKomputer['f100TahapMahir']; ?></td>
	                      </tr>
	                    <?php } while ($row_rsKomputer = mysql_fetch_assoc($rsKomputer)); ?>
	                    </table>
	                </fieldset></td>
                  </tr>
	              <tr>
	                <td height="17" colspan="3"><fieldset>
                      <legend>RUJUKAN <em>( Sila nyatakan nama dan alamat DUA orang yang bukan saudara untuk rujukan) <strong>
                      <input name="hdnRujukanICNO" type="hidden" id="hdnRujukanICNO" value="<?php echo $row_rsRujukan['']; ?>
                  &lt;/tr&gt;
	              &lt;tr&gt;
	                &lt;td height="20" colspan="4" />
                      </strong></em></legend>
	                  <table width="100%" height="45" border="1" cellspacing="0">
	                    <tr class="ARIALsize14bold" height="30">
	                      <td width="60%" height="20" bgcolor="#E9E9E9"><span class="cetak">NAMA RUJUKAN</span></td>
	                      <td width="40%" bgcolor="#E9E9E9"><span class="cetak">ALAMAT</span></td>
	                      </tr>
	                    <?php do { ?>
	                    <tr class="ARIALsize12" height="30">
	                      <td bgcolor="#FFFFFF"><span class="ARIALsize12"><?php echo $row_rsRujukan['f100namarujuk']; ?></span></td>
	                      <td bgcolor="#FFFFFF"><span class="ARIALsize12"><?php echo $row_rsRujukan['f100almtrujuk']; ?></span></td>
	                      </tr>
	                    <?php } while ($row_rsRujukan = mysql_fetch_assoc($rsRujukan)); ?>
	                    </table>
	                </fieldset></td>
                  </tr>
	              <tr>
	                <td height="17" colspan="3"><a href="#" onclick="return cetak()">Cetak Sekarang</a></td>
                  </tr>
                </table>
              </form>
            </div>
          </div></td>
        </tr>
      </table>
<div id="content"></div>
		<!-- end #content -->
	  <div style="clear: both;"></div>
	</div>
	</div>
	<!-- end #page -->
	<div id="footer-bgcontent">
	<div id="footer">
		<p align="center">Hakcipta (c) 2010-2012. Dibangunkan oleh Pusat Teknologi maklumat &amp; komunikasi.<br />
	    Universiti Pertahanan Nasional Malaysia</p>
	</div>
	</div>
	<!-- end #footer -->
</div>


</body>
</html>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsMohonJawatan);

mysql_free_result($rsPeribadi);

mysql_free_result($rsAkademik);

mysql_free_result($rsResult);

mysql_free_result($rsPengalaman);

mysql_free_result($rsMaklumatBahasa);

mysql_free_result($rsTrengkas);

mysql_free_result($rsKomputer);

mysql_free_result($rsLesen);

mysql_free_result($rsRujukan);

mysql_free_result($rsBahasa);

mysql_free_result($rsPenyelidikan);
?>
