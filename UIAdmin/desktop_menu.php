<?php require_once('../Connections/sqlconn.php'); ?>
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

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTotalPemohon = "SELECT count(f142noIC) as totalrecord FROM t142_akaun";
$rsTotalPemohon = mysql_query($query_rsTotalPemohon, $sqlconn) or die(mysql_error());
$row_rsTotalPemohon = mysql_fetch_assoc($rsTotalPemohon);
$totalRows_rsTotalPemohon = mysql_num_rows($rsTotalPemohon);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTotalAkademik = "select count(distinct a.f142idakaun) as totalakademik from t142_akaun a, t144mohon_jawatan b where a.f142noIC=b.ICNO and b.jaw_kategori=1";
$rsTotalAkademik = mysql_query($query_rsTotalAkademik, $sqlconn) or die(mysql_error());
$row_rsTotalAkademik = mysql_fetch_assoc($rsTotalAkademik);
$totalRows_rsTotalAkademik = mysql_num_rows($rsTotalAkademik);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsTotalBukanAkademik = "select count(distinct a.f142idakaun) as totalbukanakademik from t142_akaun a, t144mohon_jawatan b where a.f142noIC=b.ICNO and b.jaw_kategori=2";
$rsTotalBukanAkademik = mysql_query($query_rsTotalBukanAkademik, $sqlconn) or die(mysql_error());
$row_rsTotalBukanAkademik = mysql_fetch_assoc($rsTotalBukanAkademik);
$totalRows_rsTotalBukanAkademik = mysql_num_rows($rsTotalBukanAkademik);
?>
<?php include('../Connections/conn-common.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script src="../SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />

<body>
<div id="menuAdministrator" class="Accordion" tabindex="0">
  <div class="AccordionPanel">
    <div class="AccordionPanelTab">Menu Utama</div>
    <div class="AccordionPanelContent">
      <ul>
        <li>
          <?php
								$str_link = "profile/profile_avatar.php";
							?>
          <a href="<?php echo $str_link; ?>" target="_self">
            <?php
                            $str = $row_rsUser['f142photo'];
							$strnoimage = "noimage.png";

                            if ($str == "")
							{
								echo"<img src=\"../Passport/$strnoimage\" width=\"82\" height=\"100\" >";
								echo "<br>";
								echo "Kemaskini Gambar <br>";
							}
                            else 
							{
								echo"<img src=\"../Passport/$str\" width=\"82\" height=\"100\" >";
								echo "<br>";
								echo "Kemaskini Gambar <br>";
							}
                            ?>
          </a></li>
			<li>Jumlah Pendaftaran E-Recruitment <br /><strong><img src="../images/arrow.png" /><?php echo $row_rsTotalPemohon['totalrecord']; ?> Permohonan</strong></li>
			<li>Pemohonan Akademik <br /><strong><img src="../images/arrow.png" /><?php echo $row_rsTotalAkademik['totalakademik']; ?> Pemohon</strong></li>
			<li>Pemohonan Bukan Akademik <br /><strong><img src="../images/arrow.png" /><?php echo $row_rsTotalBukanAkademik['totalbukanakademik']; ?> Pemohon</strong></li>
                      
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("menuAdministrator");
</script>
</body>
</html>
<?php
mysql_free_result($rsTotalPemohon);

mysql_free_result($rsTotalAkademik);

mysql_free_result($rsTotalBukanAkademik);
?>
