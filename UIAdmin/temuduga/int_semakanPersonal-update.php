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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmStatusMohon")) {
  $updateSQL = sprintf("UPDATE t101temuduga SET f101noic=%s, f101kodjawatan=%s, f101semak_tempat=%s, f101semak_tarafjawatan=%s, f101semak_tarikh=%s, f101semak_masa=%s WHERE calonID=%s",
                       GetSQLValueString($_POST['hdnICNO'], "text"),
                       GetSQLValueString($_POST['slctJawatan'], "text"),
                       GetSQLValueString($_POST['slctTmpt'], "text"),
                       GetSQLValueString($_POST['slctTaraf'], "text"),
                       GetSQLValueString($_POST['txtDate'], "text"),
                       GetSQLValueString($_POST['slctMasa'], "text"),
                       GetSQLValueString($_POST['hdnID'], "int"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($updateSQL, $sqlconn) or die(mysql_error());

  $updateGoTo = "int_semakanPersonal.php";
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

$currentPage = $_SERVER["PHP_SELF"];

$colname_rsJawatan = "-1";
if (isset($_GET['ICNO'])) {
  $colname_rsJawatan = $_GET['ICNO'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJawatan = sprintf("SELECT * FROM t144mohon_jawatan WHERE ICNO = %s", GetSQLValueString($colname_rsJawatan, "text"));
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

$colname_rsTetapanTemuduga = "-1";
if (isset($_GET['ICNO'])) {
  $colname_rsTetapanTemuduga = $_GET['ICNO'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTetapanTemuduga = sprintf("SELECT * FROM t101temuduga, t109kod_jawatan WHERE  f101noic = %s ", GetSQLValueString($colname_rsTetapanTemuduga, "text"));
$rsTetapanTemuduga = mysql_query($query_rsTetapanTemuduga, $sqlconn) or die(mysql_error());
$row_rsTetapanTemuduga = mysql_fetch_assoc($rsTetapanTemuduga);
$totalRows_rsTetapanTemuduga = mysql_num_rows($rsTetapanTemuduga);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTempat = "SELECT * FROM t109kod_semaktempat ORDER BY ALAMAT_TEMPAT ASC";
$rsTempat = mysql_query($query_rsTempat, $sqlconn) or die(mysql_error());
$row_rsTempat = mysql_fetch_assoc($rsTempat);
$totalRows_rsTempat = mysql_num_rows($rsTempat);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTemuduga = "SELECT * FROM t109kod_semakstatus ORDER BY f109statusdescription ASC";
$rsTemuduga = mysql_query($query_rsTemuduga, $sqlconn) or die(mysql_error());
$row_rsTemuduga = mysql_fetch_assoc($rsTemuduga);
$totalRows_rsTemuduga = mysql_num_rows($rsTemuduga);

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
<script src="../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script><script language="JavaScript" type="text/JavaScript">
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
<link href="../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
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
		      <form action="<?php echo $editFormAction; ?>" name="frmStatusMohon" id="frmStatusMohon" method="POST">
<table width="643" height="173">
  <tr>
    <td><strong>INFO:</strong></td>
          </tr>
  <tr>
    <td><em>*Sila pilih Kategori Jawatan dan tekan butang Hantar</em></td>
          </tr>
  <tr>
    <td>&nbsp;</td>
          </tr>
  <tr>
    <td align="center"><table width="633" height="300" border="0">
      <tr>
        <td height="17" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Borang Mengisi Jawatan</strong></td>
                </tr>
      <tr>
        <td width="150" height="17" bgcolor="#E7E4E6">&nbsp;</td>
        <td width="473" bgcolor="#E7E4E6">&nbsp;</td>
                </tr>
      <tr>
        <td height="100" colspan="2" bgcolor="#E7E4E6"><fieldset>
	                      <legend>PENGEMASKINIAN STATUS TEMUDUGA</legend>
          <table width="100%" height="70" cellpadding="0" cellspacing="0">
            <tr>
              <td width="150" height="19">&nbsp;</td>
              <td width="316"><input name="hdnICNO" type="hidden" id="hdnICNO" value="<?php echo $row_rsTetapanTemuduga['f101noic']; ?>" />
                <input name="hdnID" type="hidden" id="hdnID" value="<?php echo $row_rsTetapanTemuduga['calonID']; ?>" /></td>
              <td width="164">&nbsp;</td>
                        </tr>
            <tr>
              <td height="18"><div align="right">
                <div align="right"><strong>Nama Pemohon :</strong><br />
                  </div>
        
                </div></td>
              <td><?php echo $row_rsTetapanTemuduga['f101nama']; ?></td>
              <td>&nbsp;</td>
                        </tr>
            <tr>
              <td height="16"><div align="right">
                <div align="right"><strong>No Kad Pengenalan   :</strong><br />
                </div>
              </div></td>
              <td><?php echo $row_rsTetapanTemuduga['f101noic']; ?></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
                      </table>
        </fieldset></td>
                </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Jawatan Yang Layak  :</strong><br />
                        </div></td>
                        <td class="center"><label for="slctJawatan"></label>
                          <span id="spryslctJawatan">
                          <label for="slctJawatan"></label>
                          <select name="slctJawatan" id="slctJawatan">
                            <option value="-1">---SILA PILIH---</option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_rsJawatan['jawatanID']?>"><?php echo $row_rsJawatan['jawatanDesc']?></option>
                            <?php
} while ($row_rsJawatan = mysql_fetch_assoc($rsJawatan));
  $rows = mysql_num_rows($rsJawatan);
  if($rows > 0) {
      mysql_data_seek($rsJawatan, 0);
	  $row_rsJawatan = mysql_fetch_assoc($rsJawatan);
  }
?>
                          </select>
                          <span class="selectInvalidMsg">*Maklumat Salah.</span><span class="selectRequiredMsg">*Sila Pilih.</span></span></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Tempat Temuduga  :</strong></div></td>
                        <td class="center"><label for="slctTmpt"></label>
                          <span id="spryslctTmpt">
                          <label for="slctTmpt"></label>
                          <select name="slctTmpt" id="slctTmpt">
                            <option value="-1">---SILA PILIH---</option>
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
                          </select>
                          <span class="selectInvalidMsg">*Maklumat Salah.</span><span class="selectRequiredMsg">*Sila Pilih.</span></span></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Taraf Jawatan   :</strong></div></td>
                        <td class="center"><label for="slctTaraf"></label>
                          <span id="spryslctTaraf">
                          <label for="slctTaraf"></label>
                          <select name="slctTaraf" id="slctTaraf">
                            <option value="-1">---SILA PILIH---</option>
                            <option value="TETAP">TETAP</option>
                            <option value="KONTRAK">KONTRAK</option>
                            <option value="SAMBILAN">SAMBILAN</option>
                          </select>
                          <span class="selectInvalidMsg">*Maklumat Salah.</span><span class="selectRequiredMsg">*Sila Pilih.</span></span></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Tarikh  :</strong></div></td>
                        <td class="center"><label for="select2"></label>
                          <label for="txtTarikh"></label>
                          <input type="text" name="txtDate" id="txtDate" /></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Masa   :</strong></div></td>
                        <td class="center"><label for="slctMasa"></label>
                          <span id="spryslctMasa">
                          <label for="slctMasa"></label>
                          <select name="slctMasa" id="slctMasa">
                            <option value="-1" selected="selected">---SILA PILIH---</option>
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
                            <option value="02.00 PETANG">02.30 PETANG</option>
                            <option value="03.00 PETANG">03.00 PETANG</option>
                            <option value="03.30 PETANG">03.30 PETANG</option>
                            <option value="04.00 PETANG">04.00 PETANG</option>
                            <option value="04.30 PETANG">04.30 PETANG</option>
                            <option value="05.00 PETANG">05.00 PETANG</option>
                          </select>
                          <span class="selectInvalidMsg">*Maklumat Salah.</span><span class="selectRequiredMsg">*Sila Pilih.</span></span>
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
<input type="hidden" name="MM_update" value="frmStatusMohon" />
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
var spryselect1 = new Spry.Widget.ValidationSelect("spryslctJawatan", {invalidValue:"-1", validateOn:["blur", "change"]});
var spryselect2 = new Spry.Widget.ValidationSelect("spryslctTmpt", {invalidValue:"-1", validateOn:["blur", "change"]});
var spryselect3 = new Spry.Widget.ValidationSelect("spryslctTaraf", {validateOn:["blur", "change"]});
var spryselect5 = new Spry.Widget.ValidationSelect("spryslctMasa", {invalidValue:"-1", validateOn:["blur", "change"]});
</script>

</body>
</html>
<?php
mysql_free_result($rsJawatan);

//mysql_free_result($rsJawatan);

mysql_free_result($rsUser);

mysql_free_result($rsTetapanTemuduga);

mysql_free_result($rsTempat);

mysql_free_result($rsTemuduga);
?>
