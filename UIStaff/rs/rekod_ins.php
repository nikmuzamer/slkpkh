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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmSurat")) {
  $insertSQL = sprintf("INSERT INTO t100_tbl_surat (f100_description, f100_status, f100_category, f100_noic_duty, f100_date, f100_sender, f100_receiver, f100_remarks, f100_dateupdate, f100_updateby, f100_ipaddress) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtsuratmasuk'], "text"),
                       GetSQLValueString($_POST['hdnstatus'], "int"),
                       GetSQLValueString($_POST['slcKategori'], "int"),
                       GetSQLValueString($_POST['slcPetugas'], "text"),
                       GetSQLValueString($_POST['txttarikh'], "date"),
                       GetSQLValueString($_POST['txtPenghantar'], "text"),
                       GetSQLValueString($_POST['txtPenerima'], "text"),
                       GetSQLValueString($_POST['txtCatatan'], "text"),
                       GetSQLValueString($_POST['hdnupdatedate'], "date"),
                       GetSQLValueString($_POST['hdnupdateby'], "date"),
                       GetSQLValueString($_POST['hdnipaddress'], "text"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($insertSQL, $sqlconn) or die(mysql_error());

  $insertGoTo = "../info-add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsUser = "-1";
if (isset($_SESSION['statusCode'])) {
  $colname_rsUser = $_SESSION['statusCode'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT statusID FROM t143_kod_status WHERE statusCode = %s ORDER BY statusDescription ASC", GetSQLValueString($colname_rsUser, "int"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsPetugas = "SELECT a.f142noID as NOIC, a.f142Name as NAMA FROM t142_akaun a WHERE a.f142idlevel=2";
$rsPetugas = mysql_query($query_rsPetugas, $sqlconn) or die(mysql_error());
$row_rsPetugas = mysql_fetch_assoc($rsPetugas);
$totalRows_rsPetugas = mysql_num_rows($rsPetugas);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsKategoriSurat = "SELECT * FROM t143_kod_category ORDER BY categoryCode ASC";
$rsKategoriSurat = mysql_query($query_rsKategoriSurat, $sqlconn) or die(mysql_error());
$row_rsKategoriSurat = mysql_fetch_assoc($rsKategoriSurat);
$totalRows_rsKategoriSurat = mysql_num_rows($rsKategoriSurat);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsStatusSurat = "SELECT * FROM t143_kod_status ORDER BY statusID";
$rsStatusSurat = mysql_query($query_rsStatusSurat, $sqlconn) or die(mysql_error());
$row_rsStatusSurat = mysql_fetch_assoc($rsStatusSurat);
$totalRows_rsStatusSurat = mysql_num_rows($rsStatusSurat);

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
<script src="../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
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
<link href="../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
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
		        <form name="frmSurat" id="frmSurat" method="POST" action="<?php echo $editFormAction; ?>">
		          <table width="100%" border="0">
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Petugas :</strong></div></td>
		              <td valign="top"><span id="spryselect2">
		                <label for="slcPetugas"></label>
		                <select name="slcPetugas" id="slcPetugas">
		                  <option value="-1">SILA PILIH</option>
		                  <?php
do {  
?>
		                  <option value="<?php echo $row_rsPetugas['NOIC']?>"><?php echo $row_rsPetugas['NAMA']?></option>
		                  <?php
} while ($row_rsPetugas = mysql_fetch_assoc($rsPetugas));
  $rows = mysql_num_rows($rsPetugas);
  if($rows > 0) {
      mysql_data_seek($rsPetugas, 0);
	  $row_rsPetugas = mysql_fetch_assoc($rsPetugas);
  }
?>
                      </select>
	                  <span class="selectRequiredMsg">Please select an item.</span></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Kategori Surat :</strong></div></td>
		              <td valign="top"><span id="spryselect3">
		                <label for="slcKategori"></label>
		                <select name="slcKategori" id="slcKategori">
		                  <option value="-1">SILA PILIH</option>
		                  <?php
do {  
?>
		                  <option value="<?php echo $row_rsKategoriSurat['categoryCode']?>"><?php echo $row_rsKategoriSurat['categoryDescription']?></option>
		                  <?php
} while ($row_rsKategoriSurat = mysql_fetch_assoc($rsKategoriSurat));
  $rows = mysql_num_rows($rsKategoriSurat);
  if($rows > 0) {
      mysql_data_seek($rsKategoriSurat, 0);
	  $row_rsKategoriSurat = mysql_fetch_assoc($rsKategoriSurat);
  }
?>
                      </select>
	                  <span class="selectRequiredMsg">Please select an item.</span></span></td>
	                </tr>
		            <tr>
		              <td width="25%" valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />No. Surat  :</strong></div></td>
		              <td width="75%" valign="top"><span id="spryselect1">
		                <label for="slcJawatan"></label>
	                  <label for="textfield"></label>
	                  <span id="sprysuratmasuk">
		                <label for="txtsuratmasuk"></label>
		                <input type="text" name="txtsuratmasuk" id="txtsuratmasuk" />
                      <em><span class="textfieldRequiredMsg">*Wajib isi</span></em></span><em>*No rujukan surat</em></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />
                      
                      
                      Tarikh Surat :</strong></div></td>
		              <td valign="top"><span id="sprytarikh">
		                <label for="txttarikh"></label>
                      
                    
                        
		                <input type="text" name="txttarikh" id="txttarikh" />
                       <em>*<span class="textfieldRequiredMsg">*Wajib isi</span><span class="textfieldInvalidFormatMsg">Invalid format</span></em></span><em>(00-00-0000) Tarikh penerimaan surat</em></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Pengirim :</strong></div></td>
		              <td valign="top"><span id="spryPenghantar">
		                <label for="txtPenghantar"></label>
		                <input name="txtPenghantar" type="text" id="txtPenghantar" size="50" />
                      <em><span class="textfieldRequiredMsg">*Wajib isi</span></em></span><em>*Nama penghantar surat</em></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Penerima :</strong></div></td>
		              <td valign="top"><span id="spryPenerima">
		                <input name="txtPenerima" type="text" id="txtPenerima" size="50" />
	                  <span class="textfieldRequiredMsg">*Wajib isi</span></span><em>*Nama pemilik surat</em></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Catatan :</strong></div></td>
		              <td valign="top"><span id="sprycatatan">
		                <label for="txtCatatan"></label>
		                <textarea name="txtCatatan" id="txtCatatan" cols="45" rows="5"></textarea>
</span></td>
	                </tr>
		            <tr>
		              <td valign="top">&nbsp;</td>
		              <td valign="top"><input type="submit" name="button" id="button" value="Submit" /></td>
	                </tr>
		            <tr>
		              <td>&nbsp;</td>
		              <td><input name="hdnupdateby" type="hidden" id="hdnupdateby" value="<?php echo $row_rsUser['f142Name']; ?>" />
	                  <input name="hdnupdatedate" type="hidden" id="hdnupdatedate" value="<?php datetime_time(); ?>" />
	                  <input name="hdnipaddress" type="hidden" id="hdnipaddress" value="<?php getIP(); ?>" />
	                  <input name="hdnstatus" type="hidden" id="hdnstatus" value="1" /></td>
	                </tr>
	              </table>
		          <input type="hidden" name="MM_insert" value="frmSurat" />
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


<script type="text/javascript">
var sprysuratmasuk = new Spry.Widget.ValidationTextField("sprysuratmasuk", "none", {validateOn:["change"]});
var sprytarikh = new Spry.Widget.ValidationTextField("sprytarikh", "date", {validateOn:["change"], format:"dd-mm-yy"});
var spryPenghantar = new Spry.Widget.ValidationTextField("spryPenghantar", "none", {validateOn:["change"]});
var spryPenerima = new Spry.Widget.ValidationTextField("spryPenerima", "none", {validateOn:["change"]});
var sprycatatan = new Spry.Widget.ValidationTextarea("sprycatatan", {validateOn:["change"], isRequired:false});
</script>

</body>
</html>
<?php
mysql_free_result($rsUser);

mysql_free_result($rsPetugas);

mysql_free_result($rsKategoriSurat);

mysql_free_result($rsStatusSurat);
?>
