<script type="text/javascript">function myfunction(){
		document.getElementById('#nama').value='nama';} </script>
<?php require_once('../Connections/sqlconn.php'); ?>
<?php include("../Common/common-topmenu.php"); ?>
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
	
  $logoutGoTo = "../login.php";
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

$MM_restrictGoTo = "../UIUser/application/desktop-user.php";
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
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142noID = %s ORDER BY f142idakaun ASC", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

//$colname_rsUser = "-1";
//if (isset($_SESSION['f142username'])) {
//  $colname_rsUser = $_SESSION['f142username'];
//}
//Carry Username and User Level
$username = $_SESSION['MM_Username'];
$userlevel = $_SESSION['MM_UserGroup'];

if (isset($_POST['cari'])) {
	$nomatrik = $_POST['nomatrik'];
	$cari__query="SELECT * FROM `pkdt` WHERE no_matrik = '$nomatrik'"; 
	$carirs = mysql_query($cari__query, $sqlconn) or die(mysql_error());
	// //echo "<script>alert('$cari__query');</script>";

 // 	// $submitrs = mysql_query($submit__query, $sqlconn) or die(mysql_error());
	// // echo "<script>alert('cari!');</script>";
	// echo $carirs;
	$nama = mysql_result($carirs,0,'nama');
	$notentera = mysql_result($carirs,0,'no_tentera');
	$pengambilan = mysql_result($carirs,0,'pengambilan');
	 echo 'nama='.$nama.'no tentera='.$notentera.'pengambilan='.$pengambilan;

	//echo "<script type='text/javascript'>myfunction(); </script>";
	
// echo "<script>alert('test') </script>";
	//echo "Done";

}
if (isset($_POST['submit'])) {
 $kadet= $_POST['nomatrik'];
 $kos= $_POST['kos'];
 $tarikh= $_POST['tarikh'];
 $masa = $_POST['masa'];
 $kesalahan=$_POST['kesalahan'];
 $catatan=$_POST['keterangan'];
 $pelapor=$_SESSION['MM_Username'];
 $submit__query="INSERT INTO t143_laporan(kadet, kos, tarikh, masa, kesalahan, catatan, pelapor)  VALUES ('$kadet','$kos','$tarikh','$masa','$kesalahan','$catatan','$pelapor')"; 
 $submitrs = mysql_query($submit__query, $sqlconn) or die(mysql_error());
 
 if($submitrs){
	echo "<script>alert('Success');</script>";

 }
 // echo $submit__query;
	// echo "<script>alert('$submit__query');</script>";

  //$LoginRS = mysql_query($LoginRS__query, $sqlconn) or die(mysql_error());
}

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
<title>SLKPKH UPNM</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../Common/style-blue.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
<!--
body,td,th {
	color: #000;
}
.redbold {	color: #F00;
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
          <?php menustaff(); ?>
          <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
        </ul>
      </div>
	  <!-- end #menu -->
	  <div id="search">
	    <div id="search2"> <span class="welcome"><br />
	      <a href="profile/profile.php"><img src="../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
      </div>
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
		
        <div id="content">
			
                <br />
                
                <form method="POST" action="">
               	No matrik:<br>
				<input type="text" name="nomatrik" placeholder="" >
				<br>
				<input type="submit" name="cari" value="Cari">
				<br>

					Nama:<br>
				<input type="text" name="nama" id='nama' placeholder="">
				<br>
					No tentera:<br>
				<input type="text" name="notentera">
				<br>
					Pengambilan:<br>
				<input type="text" name="pengambilan">
				<br>
					Mata pelajaran:<br>
				<input type="text" name="kos">
				<br>
					Tarikh:<br>
				<input type="date" name="tarikh">
				<br>
					Masa:<br>
				<input type="time" name="masa">
				<br>
					Nama penasihat:<br>
				<input type="text" name="penasihat" placeholder="">
				<br>
					Kesalahan:<br>
				<select name="kesalahan">
				  <option value="1">Tidak hadir</option>
				  <option value="2">Lapor sakit attend B</option>
				  <option value="3">Tidur waktu kuliah</option>
				  <option value="4">Terlewat</option>
				</select>
				<br>
					Keterangan lanjut:<br>
				<input type="text" name="keterangan">
				<br>
				<input type="submit" name="submit">

                </form>

<!-- 				  <table border="0" cellspacing="0" cellpadding="0" width="643">
        <legend>Laman Utama Staff</legend>
a
				    <tr>
				      <td height="76"><fieldset>
			            <legend>Akaun Saya</legend>
                        <br />
                      <table width="100%" height="44" border="1" align="center">
				          
				            <td height="30" width="60%" ><div class="arial14bold" align="center" bgcolor="#e8eaeb"><strong>Nama</strong></div></td>
				            <td height="30" width="40%" align="right"><div class="arial14bold" align="center" bgcolor="#e8eaeb"><strong>No Staf</strong></div></td>
			              </tr>
                           
				          <tr height="30" align="center">
                            <td align="center" class="arial12"><div  align="center"><?php echo $row_rsUser['f142Name']; ?></div></td>
                                <td  class="arial12"><div  align="center"><?php echo $row_rsUser['f142noID']; ?></div></td>
                          </tr>
                                
                         
                        </table>
                      <p>&nbsp;</p>
                      <p>&nbsp;</p>
                      <p><br />
                      </p>
                      </fieldset>
				      <br />
                      <fieldset>
			            <legend>STATUS PERMOHONAN</legend>
			            <table width="100%" height="44" border="1" align="center">
			              <tr>
			                <td height="30" ><div class="arial14bold" align="center" bgcolor="#e8eaeb">STATUS PERMOHONAN ONLINE</div></td>
		                  </tr>
			              <tr height="30" align="center">
			                <td align="center" class="arial12"><div  align="center">Sedang Dikemaskini</div></td>
		                  </tr>
		                </table>
                      </fieldset>
                      
                      <br />
                      <fieldset>
			          
                      </td>
	</tr>
			      </table> -->

			  </div>
			</div>
	  </div>
        
		<!-- end #content -->
      
<!-- 	  <div id="sidebar">
	  <ul>
	    <li><?php //include("desktop_menu.php"); ?></li>
	  </ul>
	  </div> -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	</div>
	<!-- end #page -->
	<div id="footer-bgcontent">
	<div id="footer">
		<p>.<br />
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
