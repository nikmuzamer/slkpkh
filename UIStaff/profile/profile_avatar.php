<?php require_once('../../Connections/sqlconn.php'); ?>
<?php include("../../Common/common-topmenu.php"); ?>
<?php include('../../Connections/conn-common.php'); ?>
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
.tengah {text-align: center;
	vertical-align: middle;
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
	      <?php menupengurus_profile(); ?>
	      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
        </ul>
      </div>
	  <!-- end #menu -->
	  <div id="search">
	    <div id="search2"> <span class="welcome"><br />
	      <a href="profile.php"><img src="../../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
      </div>
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
	  <div id="content">
	    <div class="post">
	      <h2 class="title"><a href="#">Kemaskini Avatar</a></h2>
	      <p class="meta">Kemaskini terakhir pada
	        <!-- #BeginDate format:fcSw1a -->Wednesday, 23 April, 2014 9:55 AM<!-- #EndDate -->
          </p>
	      <div class="entry">
	        <?php
       $upload_dir = "../../Passport/"; //upload folder..chmod to 777 - _photo/

      $_i = "1"; //number of files to upload at one time
      if($_POST['Submit'] == 'Upload')
      {
	      For($i=0; $i <= $_i-1; $i++)
      {

      $target_path = $upload_dir . basename($_FILES['file' . $i]['name']);
      $target_path = str_replace (" ", "", $target_path);
      $_file_name = basename($_FILES['file' . $i]['name']);
//      $_file_name = str_replace (" ", "", $_file_name);
	  $_file_name = str_replace (" ", "", $_file_name);
//	  $_file_name = str_replace (" ", "", "qfirdaus");
      $photo = $_file_name;  

      if(basename($_FILES['file' . $i]['name']) != '')
      {
	      if(move_uploaded_file($_FILES['file' . $i]['tmp_name'], $target_path))
      	  { $_uploaded=1;}
      	  else
	      { $_error=1;}
      }
      else
      	{ $_check=$_check+1;}
      }
      if($_uploaded == '1')
      {
		  $_uploaded=0;
		  
         //Connection ke Pangkalan Data
		 _dbConnect();

		  $strupd = "update t142_akaun set f142photo = '$photo' where f142noID = '$username'";
		  $strres = mysql_query($strupd) or die(mysql_error());	

  		  echo "Avatar telah berjaya dikemaskini. Terima Kasih";
      }

		  if($_error == '1')
		  {
			  $_error=0;
			  echo "<div class=redtext>ERROR! Sila cuba lagi, File mungkin melebihi 100KB. Maximum saiz fail adalah 100KB</div>";
		  }

		  if($_check == $_i)
		  {
			  $_check=0;
			  echo "<div class=redtext>Sila pilih avatar untuk muatnaik</div>";
		  }
      }
      echo "</td></tr>";
      ?>
	        <table width="100%" border="0" cellpadding="0" cellspacing="0">
	          <tr>
	            <td><form enctype='multipart/form-data' action='' method='post' style=\"margin: 0px;\">
              
	          <tr>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td class="redtext">
                <ul>
                <li>Saiz gambar berukuran <strong>82 x 100 pixels</strong> dan menggunakan format <strong>JPG</strong> / <strong>GIF</strong> / <strong>PNG</strong> sahaja. </li>
                <li>Sila <i><strong>Rename</strong></i> gambar kepada No Kad Pengenalan Sebelum Muat Naik.</li>
                </ul>
                </td>
              </tr>
	          <tr>
	            <td>&nbsp;</td>
              </tr>
	          <tr>
	            <td class="bodytext">Sila muatnaik gambar anda:<br />
	              <?php

      For($i=0; $i <= $_i-1;$i++)

      {

      echo "<input name='file" . $i . "' type='file'></td></tr>";

      }

      echo "<tr><td class=bodytext><input type=submit name=Submit value='Upload' style=\"font-family: Verdana; font-size: 8pt; font-weight: bold; BACKGROUND-COLOR: #5E6456; COLOR: #ffffff;\"></td></tr>";

      ?></td>
              </tr>
            </table>
	        <input type='hidden' name='MAX_FILE_SIZE' value='51200' />
          </div>
        </div>
      </div>
	  <!-- end #content -->
	  	<div id="sidebar">
	  <ul>
	    <li><?php include("profile_menu.php"); ?></li>
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
mysql_free_result($rsUser);
?>
