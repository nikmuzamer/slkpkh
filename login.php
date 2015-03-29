<?php require_once('Connections/sqlconn.php'); ?>
<?php include("Common/common-topmenu.php"); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['txtIDNo'])) {
  $loginUsername=$_POST['txtIDNo'];
  $password=md5($_POST['txtPassword']);
  $MM_fldUserAuthorization = "f142idlevel";
  $MM_redirectLoginSuccessAdmin = "UIAdmin/desktop-admin.php";
  $MM_redirectLoginSuccessStaff = "UIStaff/desktop-staff.php";
  $MM_redirectLoginSuccessTentera = "UITentera/desktop-user.php";

  $MM_redirectLoginFailed = "noaccess.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_sqlconn, $sqlconn);
  	
  $LoginRS__query=sprintf("SELECT f142noID, f142password, f142idlevel FROM t142_akaun WHERE f142noID=%s AND f142password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $sqlconn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'f142idlevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
  }
    switch($loginStrGroup){
  		case 1: header("Location: " . $MM_redirectLoginSuccessAdmin );
  		break;
      case 2: header("Location: " . $MM_redirectLoginSuccessTentera );
      break;
  		case 3: header("Location: " . $MM_redirectLoginSuccessStaff );
  		break;
  		
  }}
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>SLKPKH UPNM</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="Common/style-blue.css" rel="stylesheet" type="text/css" media="screen" />

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">
function validate_login(frmLogin) 
		{
			if (document.frmLogin.txtIDNo.value == "")
			{
				alert("Sila masukkan ID Pengguna!");
   			    frmLogin.txtIDNo.focus();
				return false;
			}	
	
			if (document.frmLogin.txtPassword.value == "")
			{
				alert("Sila masukkan Katalaluan!");
				frmLogin.txtPassword.focus();
				return false;
			}	
		}
</script>

<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
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
	      <?php menupublic_login(); ?>
        </ul>
      </div>
	  <!-- end #menu -->
	  <div id="search">
	    <form method="get" action="">
	      <fieldset>
          </fieldset>
        </form>
      </div>
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
		<div id="content">
			<div class="post">
				<h2 class="title">Login sistem</h2>
				<p class="meta">Kemaskini terakhir pada  
				  <!-- #BeginDate format:fcSw1a -->Wednesday, 11 March, 2015 7:34 AM<!-- #EndDate -->
                  <br />
				<!--<SCRIPT LANGUAGE="JAVASCRIPT" type="text/javascript" src="JS/close.js" ></SCRIPT> -->
                </p>
				<div class="entry">
					<form ACTION="<?php echo $loginFormAction; ?>" id="frmLogin" method="POST">
					  <table width="657" border="0">
					    <tr>
					      <td align="right"><div align="right" class="unbold"><strong><img src="images/pencil.png" alt="" width="16" height="16" />No Staf / No Tentera :</strong></div></td>
					      <td width="497"><span id="sprytextfield3">
			<label for="txtIDNo"></label>
		  <span class="textfieldRequiredMsg">Sila isikan No ID (Staff/Tentera)</span><span id="txtSprayIDNo">
                          <label for="txtIDNo"></label>
                          <input name="txtIDNo" type="text" id="txtIDNo" size="20" maxlength="20" />
                          <span class="textfieldRequiredMsg">*Wajib diisi.</span></span></span></td>
		</tr>
					    <tr>
					      <td align="right"><div align="right" class="unbold"><strong><img src="images/pencil.png" alt="" width="16" height="16" />Password :</strong></div></td>
					      <td><span id="sprytextfield4">
			<label for="txtPassword"></label>
		  <span class="textfieldRequiredMsg">Sila isikan Kata Laluan.</span><span id="txtSprayPassword">
		  <label for="txtPassword"></label>
		  <input type="password" name="txtPassword" id="txtPassword" />
		  <span class="textfieldRequiredMsg">*Wajib diisi.</span></span></span></td>
		</tr>
					    <tr>
					      <td>&nbsp;</td>
					      <td><label>
			<input name="button" type="submit" id="button"  value="Login" />
			<input type="reset" name="btnReset" id="btnReset" value="Reset" />
					      </label></td>
		</tr>
					    <tr>
					      <td>&nbsp;</td>
					      <td><a href="forget.php">Lupa Katalaluan?</a> <a href="license.php" title="Daftar Akaun Baru">Daftar Akaun Baru</a></td>
		</tr>
					  
				      </table>
					  <strong><br />
                      </strong>
				  </form>
                    <fieldset>
                      <strong>
                      <legend></legend>
                      </strong>
                      <table width="655" height="78">
                        <tr>
                          <td width="634"><strong> Prosedur penggunaan :</strong></td>
                          <td width="10">&nbsp;</td>
                        </tr>
                        <tr>
                          <td><ul>
                            <li>Masukkan No Staf bagi kakitangan Fakulti - Cth: 0000-00</li>
                            <li>Masukkan No Tentera bagi pegawai Akademik Latihan Kententeraan - Cth: 3010000</li>
                          </ul></td>
                          <td></td>
                        </tr>
                        </table>
                  </fieldset>
              </div>
			</div>
	  </div>
		<!-- end #content -->
		<div id="sidebar">
		  <ul>
		    <li>
		      <h2>Capaian Pantas</h2>
		      <ul>
		        <!--<li><a href="Semakantemuduga/semakanstatus.php"><img src="images/baru.gif" alt="" width="25" height="10" /> Semak Status Temuduga</a></li>-->
		        <!--<li><a href="cara-pohon.php"><img src="images/baru.gif" alt="" width="25" height="10" /></a> 
<a href="syarat/Iklanjawatan2011.pdf" target="_blank">Permohonan Jawatan</a></li>-->
		        <!--<li><a href="Semakanberjaya/semakanstatus.php"><img src="images/baru.gif" alt="" width="25" height="10" /> Semak Berjaya Temuduga</a></li>-->
		       <li><a href="http://www.upnm.edu.my/" target="_blank">Portal Rasmi UPNM</a></li>
	          <li><a href="http://ptmk.upnm.edu.my/index.php/en/" target="_blank">Pusat Teknologi Maklumat dan Komunikasi UPNM</a></li>
		        
	          </ul>
		      <!-- Histats.com  END  -->
	        </li>
	      </ul>
    </div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	</div>
	<!-- end #page -->
	<div id="footer-bgcontent">
	<div id="footer">
    
		<p>Universiti Pertahanan Nasional Malaysia</br></p>
        <p>Kem Perdana Sungai Besi</p>
        <p>57000 Kuala Lumpur</p>
	</div>
	</div>
    
	<!-- end #footer -->
</div>


<script type="text/javascript">
var txtSprayICNo = new Spry.Widget.ValidationTextField("txtSprayICNo", "none");
var txtSprayPassword = new Spry.Widget.ValidationTextField("txtSprayPassword");
</script>
</body>
</html>
