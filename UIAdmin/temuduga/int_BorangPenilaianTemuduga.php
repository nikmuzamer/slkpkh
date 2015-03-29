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
.TITLEtable {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	text-transform: uppercase;
	color: #000;
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
		    <h2 class="title"><a href="#" class="title">Penilaian Temuduga</a></h2>
		    <p class="meta">Kemaskini terakhir pada  
		    <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 5:06 PM<!-- #EndDate --></p>
		    <div class="entry">
		      <form name="frmPenilaianTemuduga" id="frmPenilaianTemuduga" method="POST">
<table width="659" height="941">
  <tr>
    <td></td>
          </tr>
  <tr>
    <td><table width="655" height="966" border="1" cellspacing="0">
      <tr>
        <td height="17" colspan="3" bgcolor="#FF9933"><div align="center" class="TITLEtable">interviewer's appraisal form</div></td>
        </tr>
      <tr>
        <td height="128" colspan="3"><fieldset>
          <legend><strong><em>Noor Suhaiza Bt Chulan</em></strong></legend>
          <table width="652" height="107" border="0">
            <tr>
              <td width="135"><div align="right"><strong>Name of Candidate:</strong><br />
                </div></td>
              <td colspan="3">&nbsp;</td>
              </tr>
            <tr>
              <td><div align="right"><strong>Year of Services  :</strong><br />
                </div></td>
              <td width="284">&nbsp;</td>
              <td width="124"><div align="right"><strong>Age of Candidate :</strong><br />
                </div></td>
              <td width="91">&nbsp;</td>
              </tr>
            <tr>
              <td><div align="right"><strong>Closing Date  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              <td><div align="right"><strong>Date Avertised  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              </tr>
            <tr>
              <td><div align="right"><strong>Interview By  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              <td><div align="right"><strong>Last Promotion Date  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              </tr>
            <tr>
              <td><div align="right"><strong>Minimum Salary  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              <td><div align="right"><strong>Conduct/Remark  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              </tr>
            <tr>
              <td><div align="right"><strong>Earliest Starting Date :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              <td><div align="right"><strong>Salary Offered  :</strong><br />
                </div></td>
              <td>&nbsp;</td>
              </tr>
            </table>
          </fieldset></td>
        </tr>
           <tr>
        <td width="139" rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>appearaNce</strong></div></td>
        <td width="511" height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="1" value="1" />
          <label for="1">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="2" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="3" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label> <input type="radio" name="radio" id="12" value="1" />
          <label for="12">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="22" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="32" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="13" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="23" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="33" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3) :</strong><em>Sloppy,Untidy,Poor taste in dressing, faulty</em><br />
          <strong>Average (4-6)  :</strong><em>Generally neat,Well groommed</em></span><br />
          <strong>Above Average (7-9)  :</strong>Very careful of appearance meticulous in dressing</div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>conversational <br /> ability</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="14" value="1" />
          <label for="14">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="24" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="34" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="122" value="1" />
          <label for="122">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="222" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="322" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="132" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="232" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="332" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Disorganized,Confused,Evasive wander &amp; irrelevant</em><br />
          <strong>Average(4-6)  :</strong><em>Logical &amp; clear grammer, Good organization</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Animated,Fluent,Good Vocabulary</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>JOB KNOWLEDGE</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="15" value="1" />
          <label for="15">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="25" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="35" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="123" value="1" />
          <label for="123">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="223" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="323" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="133" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="233" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="333" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Incomplete answer, Very little understanding of job</em><br />
          <strong>Average(4-6)  :</strong><em>Answer most questions, Gives complete explanation</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Through knowledge of the job, Complete exact answers to the question</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable">experience</div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="16" value="1" />
          <label for="16">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="26" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="36" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="124" value="1" />
          <label for="124">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="224" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="324" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="134" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="234" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="334" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Irrelevant, Not directly applicale</em><br />
          <strong>Average(4-6)  :</strong><em>Good background</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Fits job, well suited</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable">
          qualification/<br />
          education
        </div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="17" value="1" />
          <label for="17">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="27" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="37" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="125" value="1" />
          <label for="125">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="225" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="325" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="135" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="235" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="335" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Meets minimum requirement, Not in same discipline</em><br />
          <strong>Average(4-6)  :</strong><em>Meets all requirement in right discipline</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Meets all requirement has additional qualifications</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>INITIATIVE</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="172" value="1" />
          <label for="172">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="272" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="372" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="1252" value="1" />
          <label for="1252">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="2252" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="3252" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="1352" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="2352" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="3352" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>None, Does not ask Questions</em><br />
          <strong>Average(4-6)  :</strong><em>Ask standard routine questions</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Ask good/excellent questions</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>mental alertness</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="1722" value="1" />
          <label for="1722">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="2722" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="3722" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="12522" value="1" />
          <label for="12522">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="22522" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="32522" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="13522" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="23522" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="33522" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Slow to graspe ideas, Understand but little to discuss subject</em><br />
          <strong>Average(4-6)  :</strong><em>Fairly at tentative, Expresses own thought</em></span><br />
          <strong>Above Average(7-9  :</strong><em>Ask intelligent questions, Usually quick thinker, Grasp complex ideas</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>co-operation</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="1723" value="1" />
          <label for="1723">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="2723" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="3723" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="12523" value="1" />
          <label for="12523">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="22523" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="32523" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="13523" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="23523" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="33523" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Hostile, Defensive, Resistant, Antagonistic</em><br />
          <strong>Average(4-6)  :</strong><em>Eager to please, Positive Attitude, Uncomplaining</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Very positive attitude, very adaptable</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>personality</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="17222" value="1" />
          <label for="17222">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="27222" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="37222" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="125222" value="1" />
          <label for="125222">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="225222" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="325222" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="135222" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="235222" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="335222" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="17" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>In at tentative, Over-bearing, smug, not at ease, Temperamental, not friendly</em><br />
          <strong>Average(4-6)  :</strong><em>Slightly nervous, but at tentative and friendly</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Very at tentative , Very friendly, Confident and relax</em></div></td>
        </tr>
      <tr>
        <td rowspan="2" bgcolor="#FF9933"><div align="center" class="TITLEtable"><strong>motivation<br /> &amp; <br />ambition</strong></div></td>
        <td height="17" colspan="2" bgcolor="#FF9933"><input type="radio" name="radio" id="17223" value="1" />
          <label for="17223">1&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="27223" value="2" />
            2&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="37223" value="3" />
            3&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="radio" name="radio" id="125223" value="1" />
          <label for="125223">4&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="225223" value="2" />
            5
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="325223" value="3" />
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="135223" value="1" />
            7&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="235223" value="2" />
            8
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="radio" id="335223" value="3" />
            9 </label></td>
        </tr>
      <tr>
        <td height="49" colspan="2" bgcolor="#FFFFFF"><div align="left"><span class="alt"><strong>Below Average (1-3)   :</strong><em>Fails to give impression of enthusiasm for job</em><br />
          <strong>Average(4-6)  :</strong><em>Plenty of drive, e plans for getting ahead, is excited about job</em></span><br />
          <strong>Above Average(7-9)  :</strong><em>Extremely well motivated for job</em></div></td>
        </tr>
      <tr>
        <td bgcolor="#FF9933"><div align="center" class="TITLEtable">REMARKS</div></td>
        <td height="53" colspan="2" bgcolor="#FFFFFF"><label for="textarea"></label>
          <textarea name="textarea" id="textarea" cols="70" rows="3"></textarea></td>
      </tr>
      </table></td>
  </tr>
  </table>
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
