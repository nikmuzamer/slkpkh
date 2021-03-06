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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="../info-exist.php";
  $loginUsername = $_POST['txtKodJawatan'];
  $LoginRS__query = sprintf("SELECT f109jawKod FROM t109kod_jawatan WHERE f109jawKod=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_sqlconn, $sqlconn);
  $LoginRS=mysql_query($LoginRS__query, $sqlconn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmInsert")) {
  $insertSQL = sprintf("INSERT INTO t109kod_jawatan (f109jawKod, f109jawDesc, f109jawGred, f109jawStatus, f109displayStatus) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtKodJawatan'], "text"),
                       GetSQLValueString($_POST['txtNamaJawatan'], "text"),
                       GetSQLValueString($_POST['txtGredJawatan'], "text"),
                       GetSQLValueString($_POST['cmbKategori'], "int"),
                       GetSQLValueString($_POST['cmbStatus'], "int"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($insertSQL, $sqlconn) or die(mysql_error());

  $insertGoTo = "perjawatan.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$currentPage = $_SERVER["PHP_SELF"];

$colname_rsJawatan = "-1";
if (isset($_POST['cmbKategori'])) {
  $colname_rsJawatan = $_POST['cmbKategori'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJawatan = sprintf("SELECT * FROM t109kod_jawatan WHERE f109displayStatus LIKE %s ORDER BY f109jawDesc ASC", GetSQLValueString("%" . $colname_rsJawatan . "%", "text"));
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
		    <h2 class="title"><a href="#" class="title">Konfigurasi Maklumat Perjawatan</a></h2>
		    <p class="meta">Kemaskini terakhir pada  
				  <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 4:23 PM<!-- #EndDate -->
			</p>
		    <div class="entry">
		      <form name="frmInsert" id="frmInsert" method="POST" action="<?php echo $editFormAction; ?>">
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
    <td align="center"><table width="633" height="166" border="0">
      <tr>
        <td height="17" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Borang Mengisi Jawatan</strong></td>
	                  </tr>
      <tr>
        <td width="150" height="17" bgcolor="#E7E4E6">&nbsp;</td>
        <td width="473" bgcolor="#E7E4E6">&nbsp;</td>
	                  </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Kod Jawatan  :</strong><br />
                        </div></td>
                        <td class="center"><label>
                          <input type="text" name="txtKodJawatan" id="txtKodJawatan" />
                        </label></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Nama Jawatan  :</strong></div></td>
                        <td class="center"><input name="txtNamaJawatan" type="text" id="txtNamaJawatan" size="50" /></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Gred Jawatan  :</strong></div></td>
                        <td class="center"><input type="text" name="txtGredJawatan" id="txtGredJawatan" /></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>kategori Jawatan  :</strong></div></td>
                        <td class="center"><label>
                          <select name="cmbKategori" id="cmbKategori">
                            <option value="-1" selected="selected">--Sila Pilih--</option>
                            <option value="1">Akademik</option>
                            <option value="2">Bukan Akademik</option>
                          </select>
                        </label></td>
                      </tr>
                      <tr>
                        <td height="19"><div align="right"><strong>Status  :</strong></div></td>
                        <td class="center"><select name="cmbStatus" id="cmbStatus">
                          <option value="-1" selected="selected">--Sila Pilih--</option>
                          <option value="1">Aktif</option>
                          <option value="2">Tidak Aktif</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td height="19">&nbsp;</td>
                        <td class="center"><label>
                          <input type="submit" name="txtSimpan" id="txtSimpan" value="Simpan" />
                        </label></td>
                      </tr>
                    </table></td>
	              </tr>
	            </table>
<input type="hidden" name="MM_insert" value="frmInsert" />
<input type="hidden" name="MM_insert" value="frmInsert" />
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


</body>
</html>
<?php
mysql_free_result($rsJawatan);

mysql_free_result($rsUser);
?>
