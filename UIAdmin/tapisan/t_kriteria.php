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

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsListJawatan = "SELECT * FROM t109kod_jawatan WHERE t109kod_jawatan.f109jawStatus = 2 AND t109kod_jawatan.f109displayStatus = 1 ORDER BY f109jawDesc ASC";
$rsListJawatan = mysql_query($query_rsListJawatan, $sqlconn) or die(mysql_error());
$row_rsListJawatan = mysql_fetch_assoc($rsListJawatan);
$totalRows_rsListJawatan = mysql_num_rows($rsListJawatan);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsLayak = "SELECT * FROM t109kod_layak ORDER BY f109desc ASC";
$rsLayak = mysql_query($query_rsLayak, $sqlconn) or die(mysql_error());
$row_rsLayak = mysql_fetch_assoc($rsLayak);
$totalRows_rsLayak = mysql_num_rows($rsLayak);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsInstitusi = "SELECT * FROM t109kod_institusi ORDER BY f777desc ASC";
$rsInstitusi = mysql_query($query_rsInstitusi, $sqlconn) or die(mysql_error());
$row_rsInstitusi = mysql_fetch_assoc($rsInstitusi);
$totalRows_rsInstitusi = mysql_num_rows($rsInstitusi);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsBidang = "SELECT * FROM t109kod_majorminor ORDER BY MajorMinor ASC";
$rsBidang = mysql_query($query_rsBidang, $sqlconn) or die(mysql_error());
$row_rsBidang = mysql_fetch_assoc($rsBidang);
$totalRows_rsBidang = mysql_num_rows($rsBidang);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPeringkat = "SELECT * FROM t109kod_peringkat";
$rsPeringkat = mysql_query($query_rsPeringkat, $sqlconn) or die(mysql_error());
$row_rsPeringkat = mysql_fetch_assoc($rsPeringkat);
$totalRows_rsPeringkat = mysql_num_rows($rsPeringkat);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsSektorPekerjaan = "SELECT * FROM t109kod_occsector ORDER BY OccSector ASC";
$rsSektorPekerjaan = mysql_query($query_rsSektorPekerjaan, $sqlconn) or die(mysql_error());
$row_rsSektorPekerjaan = mysql_fetch_assoc($rsSektorPekerjaan);
$totalRows_rsSektorPekerjaan = mysql_num_rows($rsSektorPekerjaan);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJenisMajikan = "SELECT * FROM t109kod_corpbody ORDER BY CorpBodyType ASC";
$rsJenisMajikan = mysql_query($query_rsJenisMajikan, $sqlconn) or die(mysql_error());
$row_rsJenisMajikan = mysql_fetch_assoc($rsJenisMajikan);
$totalRows_rsJenisMajikan = mysql_num_rows($rsJenisMajikan);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTempohKhidmat = "SELECT * FROM t109kod_tempohkhidmat";
$rsTempohKhidmat = mysql_query($query_rsTempohKhidmat, $sqlconn) or die(mysql_error());
$row_rsTempohKhidmat = mysql_fetch_assoc($rsTempohKhidmat);
$totalRows_rsTempohKhidmat = mysql_num_rows($rsTempohKhidmat);

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
.redfont {	font-weight: bold;
	color: #900;
}
.redfont {	color: #FF8080;
}
.redfont {	font-weight: bold;
	color: #FF8080;
}
.redfont {	font-weight: bold;
	color: #900;
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
		    <h2 class="title"><a href="#" class="title">Tetapan Kreteria Untuk Jawatan Bukan Akademik</a><a href="#" class="title"></a></h2>
		    <p class="meta">Kemaskini terakhir pada  
		      <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 4:48 PM<!-- #EndDate -->
				</p>
		    <div class="entry">
		      <form id="form1" method="post" action="../../Administrator/tapisan/statusPemohon.php">
<table width="643" height="419">
  <tr>
    <td height="17"><strong>INFO:</strong></td>
                </tr>
  <tr>
    <td height="28"><em>*Sila masukkan jawatan untuk Carian dan tekan butang Cari</em></td>
  </tr>
  <tr>
    <td height="28"><div align="center"></div>
      <label for="slctJawatan"></label>
      <div align="center">Pilihan Jawatan :

        <select name="slctJawatan" id="slctJawatan">
          <option value="0">Sila Pilih</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsListJawatan['f109jawKod']?>"><?php echo $row_rsListJawatan['f109jawDesc']?></option>
          <?php
} while ($row_rsListJawatan = mysql_fetch_assoc($rsListJawatan));
  $rows = mysql_num_rows($rsListJawatan);
  if($rows > 0) {
      mysql_data_seek($rsListJawatan, 0);
	  $row_rsListJawatan = mysql_fetch_assoc($rsListJawatan);
  }
?>
        </select>
      </div></td>
  </tr>
  <tr>
    <td height="259" align="center" valign="top"><table width="100" height="47">
      <tr>
        <td><fieldset>
          <legend><strong>Kriteria Maklumat Pendidikan</strong>            </legend><br />
          <table width="637" border="0">
            <tr>
              <td width="180"><div align="right"><strong>Tahap Kelulusan  :</strong></div></td>
              <td width="447"><label for="slctLayak"></label>
                <select name="slctLayak" id="slctLayak">
                  <option value="0">Tidak Berkenaan</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_rsLayak['f109kod']?>"><?php echo $row_rsLayak['f109desc']?></option>
                  <?php
} while ($row_rsLayak = mysql_fetch_assoc($rsLayak));
  $rows = mysql_num_rows($rsLayak);
  if($rows > 0) {
      mysql_data_seek($rsLayak, 0);
	  $row_rsLayak = mysql_fetch_assoc($rsLayak);
  }
?>
                  </select></td>
            </tr>
            <tr>
              <td><div align="right"><strong>Nama Kelulusan  :</strong></div></td>
              <td><label for="textfield"></label>
                <input name="textfield" type="text" id="textfield" size="40" /></td>
            </tr>
            <tr>
              <td><div align="right"><strong>Gred Keseluruhan  :</strong></div></td>
              <td><label for="txtCGPA"></label>
                <input name="txtCGPA" type="text" id="txtCGPA" size="15" /></td>
  </tr>
            <tr>
              <td><div align="right"><strong>Bidang Major :</strong></div></td>
              <td><label for="slctBidang"></label>
                <select name="slctBidang" id="slctBidang">
                  <option value="0">Tidak Berkenaan</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_rsBidang['MajorMinorCd']?>"><?php echo $row_rsBidang['MajorMinor']?></option>
                  <?php
} while ($row_rsBidang = mysql_fetch_assoc($rsBidang));
  $rows = mysql_num_rows($rsBidang);
  if($rows > 0) {
      mysql_data_seek($rsBidang, 0);
	  $row_rsBidang = mysql_fetch_assoc($rsBidang);
  }
?>
                </select></td>
            </tr>
            </table>
          <label>
            </label>
          </p>
          </fieldset></td>
      </tr>
      </table>
      <fieldset>
        <legend><strong>Kriteria Keputusan Akademik</strong>
        </legend><br />
        <table width="100%" border="0">
          <tr>
            <td width="180"><div align="right"><strong>Tahap Kelulusan  :</strong></div></td>
            <td width="447"><label for="slctLayak"></label>
              <select name="slctLayak2" id="slctLayak">
                <option value="0">Tidak Berkenaan</option>
                <?php
do {  
?>
                <option value="<?php echo $row_rsPeringkat['f109kdperingkat']?>"><?php echo $row_rsPeringkat['f109keterangan']?></option>
                <?php
} while ($row_rsPeringkat = mysql_fetch_assoc($rsPeringkat));
  $rows = mysql_num_rows($rsPeringkat);
  if($rows > 0) {
      mysql_data_seek($rsPeringkat, 0);
	  $row_rsPeringkat = mysql_fetch_assoc($rsPeringkat);
  }
?>
              </select></td>
          </tr>
          <tr>
            <td><div align="right"><strong>Bahasa Melayu :</strong></div></td>
            <td><label for="textfield"></label>
             <select name="cmbBM" id="cmbBM">
               <option value="-1">Tidak Berkenaan</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="A+">A+</option>
        <option value="A">A</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B">B</option>
        <option value="C+">C+</option>
        <option value="C">C</option>
        <option value="D">D</option>
        <option value="E">E</option>
        <option value="F">F</option>
        </select></td>
          </tr>
          <tr>
            <td><div align="right"><strong>Bahasa Inggeris  :</strong></div></td>
            <td><label for="txtCGPA"></label>
              <select name="cmbBI" id="cmbBI">
                <option value="-1">Tidak Berkenaan</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="A+">A+</option>
                <option value="A">A</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B">B</option>
                <option value="C+">C+</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
              </select></td>
          </tr>
          <tr>
            <td><div align="right"><strong>Matematik :</strong></div></td>
            <td><label for="slctBidang"></label>
              <select name="cmbMATH" id="cmbMATH">
                <option value="-1">Tidak Berkenaan</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="A+">A+</option>
                <option value="A">A</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B">B</option>
                <option value="C+">C+</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
              </select></td>
          </tr>
        </table><br />
      </fieldset>
       <fieldset>
         <legend><strong>Kriteria Pengalaman</strong></legend><br />
         <table width="100%" border="0">
           <tr>
             <td width="29%"><div align="right"><strong>Sektor Pekerjaan  :</strong></div></td>
             <td width="71%"><label for="slctLayak"></label>
               <select name="slctLayak3" id="slctLayak">
                 <option value="0">Tidak Berkenaan</option>
                 <?php
do {  
?>
                 <option value="<?php echo $row_rsSektorPekerjaan['OccSectorCd']?>"><?php echo $row_rsSektorPekerjaan['OccSector']?></option>
                 <?php
} while ($row_rsSektorPekerjaan = mysql_fetch_assoc($rsSektorPekerjaan));
  $rows = mysql_num_rows($rsSektorPekerjaan);
  if($rows > 0) {
      mysql_data_seek($rsSektorPekerjaan, 0);
	  $row_rsSektorPekerjaan = mysql_fetch_assoc($rsSektorPekerjaan);
  }
?>
               </select></td>
           </tr>
           <tr>
             <td><div align="right"><strong>Jenis Majikan :</strong></div></td>
             <td><label for="textfield"></label>
               <select name="slctLayak4" id="slctLayak2">
                 <option value="0">Tidak Berkenaan</option>
                 <?php
do {  
?>
                 <option value="<?php echo $row_rsJenisMajikan['CorpBodyTypeCd']?>"><?php echo $row_rsJenisMajikan['CorpBodyType']?></option>
                 <?php
} while ($row_rsJenisMajikan = mysql_fetch_assoc($rsJenisMajikan));
  $rows = mysql_num_rows($rsJenisMajikan);
  if($rows > 0) {
      mysql_data_seek($rsJenisMajikan, 0);
	  $row_rsJenisMajikan = mysql_fetch_assoc($rsJenisMajikan);
  }
?>
               </select></td>
           </tr>
           <tr>
             <td><div align="right"><strong>Tempoh Khidmat  :</strong></div></td>
             <td><label for="txtCGPA">
               <select name="slctLayak5" id="slctLayak3">
                 <option value="0">Tidak Berkenaan</option>
                 <?php
do {  
?>
                 <option value="<?php echo $row_rsTempohKhidmat['f109kdtempoh']?>"><?php echo $row_rsTempohKhidmat['f109tempohDesc']?></option>
                 <?php
} while ($row_rsTempohKhidmat = mysql_fetch_assoc($rsTempohKhidmat));
  $rows = mysql_num_rows($rsTempohKhidmat);
  if($rows > 0) {
      mysql_data_seek($rsTempohKhidmat, 0);
	  $row_rsTempohKhidmat = mysql_fetch_assoc($rsTempohKhidmat);
  }
?>
               </select>
             </label></td>
           </tr>
           </table><br />
       </fieldset>
     <fieldset>
         <legend><strong>Kriteria Tambahan</strong></legend><table width="100%" border="0">
           <tr>
             <td width="29%"><div align="right"><strong>Kemahiran Komputer  :</strong></div></td>
             <td width="71%">
               <label>
                 <input type="checkbox" name="CheckboxGroup1" value="checkbox" id="CheckboxGroup1_0" />
               </label>
               <br />
                      <label for="slctLayak"></label></td>
           </tr>
           <tr>
             <td><div align="right"><strong>Sukan :</strong></div></td>
             <td><label for="textfield">
               <input type="checkbox" name="CheckboxGroup1_" value="checkbox" id="CheckboxGroup1_" />
             </label></td>
           </tr>
           <tr>
             <td><div align="right"><strong>Bakat :</strong></div></td>
             <td><input type="checkbox" name="CheckboxGroup1_2" value="checkbox" id="CheckboxGroup1_2" /></td>
           </tr>
         </table>
         <br />
         </fieldset>
      </td>
  </tr>
  </table>
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
<script src="http://cdn.wibiya.com/Toolbars/dir_0781/Toolbar_781680/Loader_781680.js" type="text/javascript"></script>
<script type="text/javascript">
var _cmo = {form: '4d9ef09163b3d34f2200e573', text: 'Hubungi', align: 'left', valign: 'top', lang: 'en', background_color: '#003C68'}; (function() {var cms = document.createElement('script'); cms.type = 'text/javascript'; cms.async = true; cms.src = ('https:' == document.location.protocol ? 'https://d1uwd25yvxu96k.cloudfront.net' : 'http://static.contactme.com') + '/widgets/tab/v1/tab.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(cms, s);})();
</script>

</body>
</html>
<?php
mysql_free_result($rsJawatan);

mysql_free_result($rsUser);

mysql_free_result($rsListJawatan);

mysql_free_result($rsLayak);

mysql_free_result($rsInstitusi);

mysql_free_result($rsBidang);

mysql_free_result($rsPeringkat);

mysql_free_result($rsSektorPekerjaan);

mysql_free_result($rsJenisMajikan);

mysql_free_result($rsTempohKhidmat);
?>
