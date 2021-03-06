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
$MM_authorizedUsers = "2";
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

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142noID = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

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
	      <?php menupengguna_RS(); ?>
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
		        <form id="frmSurat" method="post" action="">
		          <table width="100%" border="0">
		            <tr>
		              <td width="21%" valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />No Surat :</strong></div></td>
		              <td width="79%" valign="top"><span id="spryselect1">
		                <label for="slcJawatan"></label>
		                <label for="textfield"></label>
	                  <span id="sprysuratmasuk">
		                <label for="txtsuratmasuk"></label>
		                <input type="text" name="txtsuratmasuk" id="txtsuratmasuk" />
	                  <span class="textfieldRequiredMsg">A value is required.</span></span></span></td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Tarikh Surat:</strong></div></td>
		              <td valign="top">&nbsp;</td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Penghantar :</strong></div></td>
		              <td valign="top">&nbsp;</td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Penerima :</strong></div></td>
		              <td valign="top">&nbsp;</td>
	                </tr>
		            <tr>
		              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Catatan :</strong></div></td>
		              <td valign="top">&nbsp;</td>
	                </tr>
		            <tr>
		              <td valign="top">&nbsp;</td>
		              <td valign="top"><input type="submit" name="button" id="button" value="Submit" /></td>
	                </tr>
		            <tr>
		              <td>&nbsp;</td>
		              <td>&nbsp;</td>
	                </tr>
	              </table>
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
		    	<li>menu</li>
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprysuratmasuk", "none", {validateOn:["change"]});
</script>

</body>
</html>
<?php
mysql_free_result($rsUser);
?>
