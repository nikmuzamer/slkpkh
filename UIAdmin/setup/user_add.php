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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmAdduser")) {
  $insertSQL = sprintf("INSERT INTO t142_akaun (f142password, f142email, f142Name, f142noIC, f142idlevel) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(md5($_POST['txtPassword1']), "text"),
                       GetSQLValueString($_POST['txtEmail'], "text"),
                       GetSQLValueString($_POST['txtName'], "text"),
                       GetSQLValueString($_POST['cmbLevel'], "text"),
                       GetSQLValueString($_POST['cmbLevel'], "int"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($insertSQL, $sqlconn) or die(mysql_error());

  $insertGoTo = "user_manage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142username = %s ORDER BY f142username ASC", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsLevel = "SELECT * FROM t141_level";
$rsLevel = mysql_query($query_rsLevel, $sqlconn) or die(mysql_error());
$row_rsLevel = mysql_fetch_assoc($rsLevel);
$totalRows_rsLevel = mysql_num_rows($rsLevel);

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142noIC = %s", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

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
<script src="../../JavascriptFiles/mootools.js" type="text/javascript"></script>
<script src="../../JavascriptFiles/sortableTable.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
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
<link href="../CSSFiles/sortableTable.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" language="javascript">
                function f(o){o.value=o.value.toUpperCase().replace(/([^`@_()'"-/<>;:,.0-9A-Z])/g," ");}
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
	      <?php menuadmin_setup(); ?>
	      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
	      
        </ul>
      </div>
	  <!-- end #menu -->
	  <div id="search">
	    <div id="search2"> <span class="welcome"><br />
	      <a href="../../Administrator/setup/profile.php"><img src="../../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
      </div>
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
	  <div id="content">
        <div class="post">
          <h2 class="title">Borang pendaftaran Pengguna sistem e-recruitment</h2>
          <p class="meta">Kemaskini terakhir pada
            <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 4:36 PM<!-- #EndDate -->
          </p>
          <div class="entry">
            <form name="frmAdduser" action="<?php echo $editFormAction; ?>" id="frmAdduser" method="POST">
              <table width="100%" border="0">
                <tr>
                  <td><div align="right"><strong>*No Kad Pengenalan:</strong></div></td>
                  <td colspan="2"><span id="txtSprayICNo">
                    <label for="txtICNo"></label>
                    <span id="txtspryICNo">
                    <label>
                    <input type="text" name="txtICNo" id="txtICNo" />
                    </label>
                    <span class="textfieldRequiredMsg">*Wajib diisi.</span><span class="textfieldInvalidFormatMsg">*Nombor sahaja.</span></span></span></td>
                </tr>
                <tr>
                  <td><div align="right"><strong>*Katalaluan:</strong></div></td>
                  <td colspan="2"><span id="txtSprayPassword">
                    <label></label>
                    <span id="txtspryPassword1">
                    <label>
                    <input type="password" name="txtPassword1" id="txtPassword1" />
                    </label>
                  <span class="textfieldRequiredMsg">*Wajib diisi.</span></span></span></td>
                </tr>
                <tr>
                  <td><div align="right"><strong>Nama Penuh:</strong></div></td>
                  <td colspan="2"><span id="sprytextfield3"><span id="txtSprayNama">
                    <label></label>
                    <span id="txtspryName">
                    <label>
                    <input name="txtName" type="text" id="txtName" size="50" onkeydown="f(this)" onkeyup="f(this)" onblur="f(this)" onclick="f(this)"/>
                    </label>
                  <span class="textfieldRequiredMsg">*Wajib diisi.</span></span></span></span></td>
                </tr>
                <tr>
                  <td><div align="right"><strong>Alamat Emel:</strong></div></td>
                  <td colspan="2"><span id="txtSprayEmail">
                    <label for="txtEmail" onkeydown="f(this)" onkeyup="f(this)" onblur="f(this)" onclick="f(this)"></label>
                    <span id="txtspryEmail">
                    <label>
                    <input type="text" name="txtEmail" id="txtEmail" />
                    </label>
                    <span class="textfieldRequiredMsg">*Wajib diisi.</span><span class="textfieldInvalidFormatMsg">*salah format.</span></span></span></td>
                </tr>
                <tr>
                  <td><div align="right"><strong>Level:</strong></div></td>
                  <td colspan="2"><span id="cmbSprayLevel">
                    <label for="cmbLevel"></label>
                    <select name="cmbLevel" id="cmbLevel">
                      <option value="0" selected="selected" <?php if (!(strcmp(0, $row_rsLevel['f140level']))) {echo "selected=\"selected\"";} ?>>-Sila Pilih-</option>
                      <?php
do {  
?>
                      <option value="<?php echo $row_rsLevel['f140level']?>"<?php if (!(strcmp($row_rsLevel['f140level'], $row_rsLevel['f140level']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsLevel['f140penerangan']?></option>
                      <?php
} while ($row_rsLevel = mysql_fetch_assoc($rsLevel));
  $rows = mysql_num_rows($rsLevel);
  if($rows > 0) {
      mysql_data_seek($rsLevel, 0);
	  $row_rsLevel = mysql_fetch_assoc($rsLevel);
  }
?>
                    </select>
                    <span class="selectInvalidMsg">Please select a valid item.</span></span></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td colspan="2"><label>
                    <input type="submit" name="btnDaftar" id="btnDaftar" value="Daftar Akaun" />
                    <input name="txtKodWarga" type="hidden" id="txtKodWarga" value="0" />
                  </label></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="frmAdduser" />
            </form>
          </div>
        </div>
      </div>
	  <!-- end #content -->
				<div id="sidebar">
		  <ul>
		     <li><?php include("setup_menu.php"); ?></li>
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
var spryselect1 = new Spry.Widget.ValidationSelect("cmbSprayLevel", {validateOn:["change"], isRequired:false, invalidValue:"-1"});
var sprytextfield1 = new Spry.Widget.ValidationTextField("txtspryICNo", "integer");
var sprytextfield2 = new Spry.Widget.ValidationTextField("txtspryPassword1");
var sprytextfield4 = new Spry.Widget.ValidationTextField("txtspryName");
var sprytextfield5 = new Spry.Widget.ValidationTextField("txtspryEmail", "email");
</script>
</body>
</html>
<?php
mysql_free_result($rsLevel);

mysql_free_result($rsUser);
?>
