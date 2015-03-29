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

$currentPage = $_SERVER["PHP_SELF"];

$colname_rsJawatan = "-1";
if (isset($_POST['txtNamaJawatan'])) {
  $colname_rsJawatan = $_POST['txtNamaJawatan'];
}
$stt_rsJawatan = "-1";
if (isset($_POST['radStatus'])) {
  $stt_rsJawatan = $_POST['radStatus'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsJawatan = sprintf("SELECT * FROM t109kod_jawatan WHERE f109jawDesc LIKE %s AND f109displayStatus = %s", GetSQLValueString("%" . $colname_rsJawatan . "%", "text"),GetSQLValueString($stt_rsJawatan, "int"));
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
-->
</style>
<script src="../../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
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
<link href="../../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
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
			  <!-- #BeginDate format:fcSw1a -->Thursday, 4 October, 2012 4:30 PM<!-- #EndDate -->
			</p>
		    <div class="entry">
		      <form id="form1" method="post" action="perjawatan.php">
<table width="643" height="198">
  <tr>
    <td><strong>INFO:</strong></td>
          </tr>
  <tr>
    <td><em>*Sila masukkan Nama Jawatan untuk Carian dan tekan butang Cari</em></td>
          </tr>
  <tr>
    <td>&nbsp;</td>
          </tr>
  <tr>
    <td height="51" align="center" valign="top"><table width="650" height="47">
      <tr>
        <td><fieldset>
          <legend>Borang Carian</legend>
          <table width="100%" border="0">
            <tr>
              <td><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Carian Nama Jawatan :</strong></div></td>
              <td><input name="txtNamaJawatan" type="text" id="txtNamaJawatan" size="50" />
                <span id="sprytextfield1">
                <label for="text1"></label>
                </span></td>
            </tr>
            <tr>
              <td valign="top"><div align="right" class="unbold"><strong><img src="../../images/featured.png" alt="" width="16" height="16" />Status Jawatan :</strong></div></td>
              <td valign="top"><span id="spryradio1">
                <label>
                  <input type="radio" name="radStatus" value="1" id="radStatus_0" />
                  Aktif</label>
                <br />
                <label>
                  <input type="radio" name="radStatus" value="2" id="radStatus_1" />
                  Tidak Aktif</label>
                <br />
                <span class="radioRequiredMsg">Please make a selection.</span></span></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="submit" name="cmdSubmit" id="cmdSubmit" value="Cari" /></td>
            </tr>
          </table>
        </fieldset></td>
                </tr>
      </table></td>
          </tr>
  <tr>
    <td align="center"><table width="100%" border="0">
      <tr>
        <td colspan="5" align="center" valign="middle" bgcolor="#CCCCCC"><strong>PAPARAN JAWATAN PILIHAN PENGGUNA</strong></td>
                </tr>
      <tr>
        <td width="298" bgcolor="#E7E4E6"><strong>Nama Jawatan</strong></td>
        <td width="130" bgcolor="#E7E4E6"><strong>Kategori Jawatan</strong></td>
        <td width="80" bgcolor="#E7E4E6"><strong>Status</strong></td>
        <td width="60" bgcolor="#E7E4E6"><strong>Syarat</strong></td>
        <td width="60" bgcolor="#E7E4E6"><strong>Tindakan</strong></td>
                </tr>
                      <?php do { ?>
                      <tr>
                        <td> <?php echo $row_rsJawatan['f109jawKod']; ?> 
                        
                        <?php
						$tanda = '<html><head><title></title>
</head><body> - </body></html>';
						if  ($totalRows_rsJawatan == "")
						{
							echo "";}else{echo $tanda;}
								?>
                         
						
						
						<?php echo $row_rsJawatan['f109jawDesc']; ?> <?php echo $row_rsJawatan['f109jawGred']; ?><br /></td>
                        <td class="center">
						<?php switch ($row_rsJawatan['f109jawStatus'])
						{
							case 0: //No Value In Database
							  echo "";
							  break;
							case 1: //Value 1 If Academic
							  echo "Akademik";
							  break;
							case 2: //Value 2 If Non Academic
							  echo "Bukan Akademik";
							  break;
							default: //No Value
							  echo "";
						} 
						?>
						</td>
                        <td class="center">
						<?php switch ($row_rsJawatan['f109displayStatus'])
						{
							case 0: //No Value In Database
							  echo "";
							  break;
							case 1: //Value 1 If Academic
							  echo "Aktif";
							  break;
							case 2: //Value 2 If Non Academic
							  echo "Tidak Aktif";
							  break;
							default: //No Value
							  echo "";
						} 
						?>                        
                        </td>
                        <td class="center">
                        
                        
                        
                        
                        
                        <a href="upload.php?jawKod=<?php echo $row_rsJawatan['f109jawKod']; ?>&amp;jawDesc=<?php echo $row_rsJawatan['f109jawDesc']; ?>">
                        
                        
                        
                        <?php
						$adelink = '<html><head><title></title>
</head><body>Muat Naik</body></html>';
						if  ($totalRows_rsJawatan == "")
						{
							echo "";}else{echo $adelink;}
								?>
                        </a>
                        
                        
                        
                        
                        </td>
                        <td class="center">
                        
                        <a href="jawatan_add.php">
                        
                        
                         <?php
						$adeicon = '<html><head><title></title>
</head><body><img src="../../images/database-add-icon.png" alt="" width="16" height="16" /></body></html>';
						if  ($totalRows_rsJawatan == "")
						{
							echo "";}else{echo $adeicon;}
								?>
                        
                        
                        </a>
                        
                        <a href="jawatan_edit.php?ID=<?php echo $row_rsJawatan['f109jawKod']; ?>">
                        
                        <?php
						$adeicon = '<html><head><title></title>
</head><body><img src="../../images/database-process-icon.png" alt="" width="16" height="16" /></body></html>';
						if  ($totalRows_rsJawatan == "")
						{
							echo "";}else{echo $adeicon;}
								?>
                        
                        </a>
                        
                        <a href="jawatan_delete.php?ID=<?php echo $row_rsJawatan['f109jawKod']; ?>" onClick="return confirm_delete()">
                        
                        <?php
						$adeicon = '<html><head><title></title>
</head><body><img src="../../images/database-remove-icon.png" alt="" width="16" height="16" /></body></html>';
						if  ($totalRows_rsJawatan == "")
						{
							echo "";}else{echo $adeicon;}
								?>
                        
                        </a></td>
                      </tr>
                      <?php } while ($row_rsJawatan = mysql_fetch_assoc($rsJawatan)); ?>
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
		     <li><?php include("setup_menu.php"); ?>
<?php
mysql_free_result($rsJawatan);

mysql_free_result($rsUser);
?>
</li>
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
//mysql_free_result($rsJawatan);

//mysql_free_result($rsUser);
?>
