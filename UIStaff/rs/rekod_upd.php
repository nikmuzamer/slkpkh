<?php require_once('../../Connections/sqlconn.php'); ?>
<?php include("../../Common/common-topmenu.php"); ?>
<?php include("../../Common/common.php"); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmSurat")) {
  $updateSQL = sprintf("UPDATE t100_tbl_surat SET f100_description=%s, f100_date=%s, f100_sender=%s, f100_receiver=%s, f100_remarks=%s, f100_dateupdate=%s, f100_updateby=%s, f100_ipaddress=%s WHERE f100_IDSurat=%s",
                       GetSQLValueString($_POST['txtsuratmasuk'], "text"),
                       GetSQLValueString($_POST['txttarikh'], "date"),
                       GetSQLValueString($_POST['txtPenghantar'], "text"),
                       GetSQLValueString($_POST['txtPenerima'], "text"),
                       GetSQLValueString($_POST['txtCatatan'], "text"),
                       GetSQLValueString($_POST['hdnupdatedate'], "date"),
                       GetSQLValueString($_POST['hdnupdateby'], "text"),
                       GetSQLValueString($_POST['hdnipaddress'], "text"),
                       GetSQLValueString($_POST['hdnIDsurat'], "int"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($updateSQL, $sqlconn) or die(mysql_error());

  $updateGoTo = "semakan.php";
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

$colname_rsSurat = "-1";
if (isset($_GET['ID'])) {
  $colname_rsSurat = $_GET['ID'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsSurat = sprintf("SELECT * FROM t100_tbl_surat WHERE f100_IDSurat = %s", GetSQLValueString($colname_rsSurat, "int"));
$rsSurat = mysql_query($query_rsSurat, $sqlconn) or die(mysql_error());
$row_rsSurat = mysql_fetch_assoc($rsSurat);
$totalRows_rsSurat = mysql_num_rows($rsSurat);

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
.tengah {	text-align: center;
	vertical-align: middle;
}
-->
</style>
<script src="../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
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
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
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
	      <?php menupengurus_rs(); ?>
	      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
        </ul>
      </div>
	  <!-- end #menu -->
	  <div id="search">
	    <div id="search2"> <span class="welcome"><br />
         <a href="rs.php"><img src="../../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
      </div>
	  <br />
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
<div id="page-bgtop">
		<div id="content">

		    <tr>
		      <td><table width="640" height="108">
<tr>
  <td height="102"><fieldset>
    <legend><strong>Tatacara Permohonan</strong></legend>
    <br />
        - <img src="../../images/penting.gif" alt="" width="45" height="14" />&nbsp;Gambar yang dimasukkan akan digunakan dalam proses permohonan.<br /><br />
        - <img src="../../images/penting.gif" alt="" width="45" height="14" />&nbsp;Pemohon dinasihatkan menggunakan gambar rasmi semasa proses muat naik gambar dibuat<br />
  </fieldset></td>
	            </tr>
</table>
		        <form action="<?php echo $editFormAction; ?>" name="frmSurat" id="frmSurat" method="POST">
		          <table width="100%" border="0">
		            <tr>
		              <td width="21%" valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Surat Masuk :</strong></div></td>
		              <td width="79%" valign="top"><span id="spryselect1">
		                <label for="slcJawatan"></label>
		                <label for="textfield"></label>
                <span id="sprysuratmasuk">
		                <label for="txtsuratmasuk"></label>
                  <input name="txtsuratmasuk" type="text" id="txtsuratmasuk" value="<?php echo $row_rsSurat['f100_description']; ?>" />
                <span class="textfieldRequiredMsg">*Wajib isi</span></span></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Tarikh :</strong></div></td>
		              <td valign="top"><span id="sprytarikh">
		                <label for="txttarikh"></label>
		                <input name="txttarikh" type="text" id="txttarikh" value="
						
						<?php echo $row_rsSurat['f100_date']; ?>"/>
	                  <span class="textfieldRequiredMsg">*Wajib isi</span></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Penghantar :</strong></div></td>
		              <td valign="top"><span id="spryPenghantar">
		                <label for="txtPenghantar"></label>
		                <input name="txtPenghantar" type="text" id="txtPenghantar" value="<?php echo $row_rsSurat['f100_sender']; ?>" size="50" />
	                  <span class="textfieldRequiredMsg">*Wajib isi</span></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Penerima :</strong></div></td>
		              <td valign="top"><span id="spryPenerima">
		                <label for="txtPenerima"></label>
		                <input name="txtPenerima" type="text" id="txtPenerima" value="<?php echo $row_rsSurat['f100_receiver']; ?>" size="50" />
	                  <span class="textfieldRequiredMsg">*Wajib isi</span></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Catatan :</strong></div></td>
		              <td valign="top"><span id="sprycatatan">
		                <label for="txtCatatan"></label>
		                <textarea name="txtCatatan" id="txtCatatan" cols="45" rows="5"><?php echo $row_rsSurat['f100_remarks']; ?></textarea>
</span></td>
	                </tr>
		            <tr>
		              <td valign="top">&nbsp;</td>
		              <td valign="top"><input type="submit" name="button" id="button" value="Kemaskini" />
	                  </td>
	                </tr>
		            <tr>
		              <td>&nbsp;</td>
		              <td><input name="hdnupdateby" type="hidden" id="hdnupdateby" value="<?php echo $row_rsUser['f142Name']; ?>" />
	                  <input name="hdnupdatedate" type="hidden" id="hdnupdatedate" value="<?php datetime_time(); ?>" />
	                  <input name="hdnipaddress" type="hidden" id="hdnipaddress" value="<?php getIP(); ?>" />
	                  <input name="hdnIDsurat" type="hidden" id="hdnIDsurat" value="<?php echo $row_rsSurat['f100_IDSurat']; ?>" /></td>
	                </tr>
	              </table>
		          <input type="hidden" name="MM_update" value="frmSurat" />
                </form>
<p>&nbsp;</p>
<ul type="disc">
  
              </ul></td>
	        </tr>
	      </table>
	  </div>
		<!-- end #content -->
				<div id="sidebar"> 
        <ul>
		    	<li><?php include("rs_menu.php"); ?></li>
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
var sprysuratmasuk = new Spry.Widget.ValidationTextField("sprysuratmasuk", "none", {validateOn:["change"]});
var sprytarikh = new Spry.Widget.ValidationTextField("sprytarikh", "none", {validateOn:["change"]});
var spryPenghantar = new Spry.Widget.ValidationTextField("spryPenghantar", "none", {validateOn:["change"]});
var spryPenerima = new Spry.Widget.ValidationTextField("spryPenerima", "none", {validateOn:["change"]});
var sprycatatan = new Spry.Widget.ValidationTextarea("sprycatatan", {validateOn:["change"], isRequired:false});
</script>

</body>
</html>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsSurat);
?>
