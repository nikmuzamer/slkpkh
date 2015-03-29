<?php include("Common/common-topmenu.php"); ?>
<?php require_once('Connections/sqlconn.php'); ?>
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
  $MM_dupKeyRedirect="info-exist.php";
  $loginUsername = $_POST['txtIDNo'];
  $LoginRS__query = sprintf("SELECT f142noID FROM t142_akaun WHERE f142noID=%s", GetSQLValueString($loginUsername, "text"));
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

function GetLevel($thestatus){
	$level = 0;
	switch ($thestatus){
		case "pensyarah": 
		$level = 2;
		break;
		case "kb": 
		$level = 3;
		break;
	}
	return $level;
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmRegister")) {
  $insertSQL = sprintf("INSERT INTO t142_akaun (f142password, f142email, f142Name, f142noID, f142idlevel) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(md5($_POST['txtPassword1']), "text"),
                       GetSQLValueString($_POST['txtEmail'], "text"),
                       GetSQLValueString($_POST['txtName'], "text"),
                       GetSQLValueString($_POST['txtIDNo'], "text"),
                       GetSQLValueString(GetLevel($_POST['txtStatus']), "int"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($insertSQL, $sqlconn) or die(mysql_error());

   //START SEND EMAIL
  $STRUNAME = $_POST['txtIDNo'];
  //$STRPWD = md5($_POST['txtPassword1']);
  $STREMAIL = $_POST['txtEmail'];
  $STRMSG1 = "Pendaftaran Sistem e-Recruitment Universiti Pertahanan Nasional Malaysia anda telah berjaya. Sila layari http://erecruitment.upnm.edu.my untuk login menggunakan akses berikut\n\nNo Kad Pengenalan:" .$STRUNAME;
  $STRMSG2 = "\n\nTerima kasih\n\n-Sistem Administrator-";
    
  $to = $STREMAIL ;
  $subject = "[PENDAFTARAN]Sistem e-Recruitment Universiti Pertahanan Nasional Malaysia";
  $message = $STRMSG1.' '.$STRMSG2;
  $from = "perjawatan@upnm.edu.my";
  $headers = "From: $from";
  ini_set('SMTP', 'mail.upnm.edu.my');
  mail($to,$subject,$message,$headers);
  //END SEND EMAIL

  $insertGoTo = "success.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
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
<body >
<div id="wrapper">
  <div id="logo">
<h1>&nbsp;</h1>
		<p>&nbsp;</p>
	</div>
	<hr />
	<!-- end #logo -->
	<div id="header">
	  <div id="menu">
	    <ul>
	      <?php menupublic_utama(); ?>
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
			  <h2 class="title">Borang pendaftaran akaun Pengguna</h2>
				<p class="meta">Kemaskini terakhir pada			    
				  <!-- #BeginDate format:fcSw1a -->Wednesday, 11 March, 2015 7:36 AM<!-- #EndDate -->			
        </p>
				<div class="entry">
				  <form action="<?php echo $editFormAction; ?>" name="frmRegister" id="frmRegister" method="POST">
				    <p><span id="sprytextfield1"><span class="textfieldRequiredMsg">*Wajib Isi</span></span><span id="sprypassword1"><span class="passwordRequiredMsg">*Wajib Isi</span></span></p>
				    <table width="100%" border="0">
				      <tr>
		<td width="40%"><div align="right"><strong><img src="images/pencil.png" alt="" width="16" height="16" /> No Tentera/Staff:</strong></div></td>
		<td width="60%" colspan="2"><span id="sprytextfield2"><span class="textfieldRequiredMsg">*Wajib diisi</span></span><span id="txtSprayICNo">
                        <label for="txtICNo"></label>
                        <input name="txtIDNo" type="text" id="txtIDNo" size="12" maxlength="12" />
                        <span class="textfieldRequiredMsg">*Wajib diisi</span></span></td>
	  </tr>
	  	   <tr>
		<td><div align="right"><strong><img src="images/pencil.png" alt="" width="16" height="16" /> Status: </strong></div></td>
		<td colspan="2"><span id="txtSprayEmail">
                        <label for="txtStatus" onkeydown="f(this)" onkeyup="f(this)" onblur="f(this)" onclick="f(this)"></label>
                        <select name="txtStatus" form="frmRegister">
			  <option value="pensyarah">Pensyarah</option>
			  <option value="kb">Ketua Batalion</option>
			 
			</select>
	  </tr>
				      <tr>
		<td><div align="right"><strong><img src="images/pencil.png" alt="" width="16" height="16" /> Password:</strong></div></td>
		<td colspan="2"><span id="txtSprayPassword">
		  <label>
		    <input name="txtPassword1" type="password" id="txtPassword1" size="20" />
	    </label>
	    <span class="passwordRequiredMsg">*Wajib diisi</span></span></td>
	  </tr>
				      <tr>
		<td><div align="right"><strong><img src="images/pencil.png" alt="" width="16" height="16" /> Sahkan Password:</strong></div></td>
		<td colspan="2"><span id="txtSpraySahPassword">
		  <label>
		    <input name="txtPassword2" type="password" id="txtPassword2" size="20" />
	    </label>
	    <span class="confirmRequiredMsg">*Wajib diisi</span><br /><span class="confirmInvalidMsg"> Ralat : Katalaluan tidak sah!</span></span></td>
	  </tr>
				      <tr>
		<td><div align="right"><strong><img src="images/pencil.png" alt="" width="16" height="16" /> Nama Penuh:</strong></div></td>
		<td colspan="2"><span id="sprytextfield3"><span id="txtSprayNama">
	    <label>
	      <input name="txtName" type="text" id="txtName" size="40" onkeydown="f(this)" onkeyup="f(this)" onblur="f(this)" onclick="f(this)" />
	    </label>
	    <span class="textfieldRequiredMsg">*Wajib diisi</span></span><span class="textfieldRequiredMsg">*Wajib Isi</span></span></td>
	  </tr>
				      <tr>
		<td><div align="right"><strong><img src="images/pencil.png" alt="" width="16" height="16" /> Alamat Emel:</strong></div></td>
		<td colspan="2"><span id="txtSprayEmail">
                        <label for="txtEmail" onkeydown="f(this)" onkeyup="f(this)" onblur="f(this)" onclick="f(this)"></label>
                        <input name="txtEmail" type="text" id="txtEmail" size="40" />
                        <span class="textfieldRequiredMsg">*Wajib diisi</span><span class="textfieldInvalidFormatMsg">*Email tidak sah.</span></span></td>
	  </tr>

				      <tr>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;</td>
	  </tr>
				      <tr>
		<td>&nbsp;</td>
		<td colspan="2"><label>
		  <input type="submit" name="btnDaftar" id="btnDaftar" value="Daftar Akaun" />
		  <!-- <input name="txtLevel" type="hidden" id="txtLevel" value="2" /> -->
		  <!-- <input name="txtKodWarga" type="hidden" id="txtKodWarga" value="0" /> -->
		</label></td>
	  </tr>
	</table>
				    <input type="hidden" name="MM_insert" value="frmRegister" />
				  </form>
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
		      <!-- Histats.com  END  -->	        </li>
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
<!--
var txtSprayICNo = new Spry.Widget.ValidationTextField("txtSprayICNo", "none");
var txtSprayPassword = new Spry.Widget.ValidationPassword("txtSprayPassword");
var txtSpraySahPassword = new Spry.Widget.ValidationConfirm("txtSpraySahPassword", "txtPassword1");
var txtSprayNama = new Spry.Widget.ValidationTextField("txtSprayNama");
var txtSprayEmail = new Spry.Widget.ValidationTextField("txtSprayEmail", "email");



</script>


</body>
</html>

