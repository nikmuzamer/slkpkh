<?php
//CREATED BY: UNIT APLIKASI WEB
//Copyright Protected 2011
?>
<?php require_once('../../Connections/sqlconn.php'); ?>
<?php include('../../Connections/conn-common.php'); ?>

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

$colname_rsMohon = "-1";
if (isset($_GET['ICNO'])) {
  $colname_rsMohon = $_GET['ICNO'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsMohon = sprintf("SELECT * FROM t142_akaun WHERE f142noIC = %s", GetSQLValueString($colname_rsMohon, "text"));
$rsMohon = mysql_query($query_rsMohon, $sqlconn) or die(mysql_error());
$row_rsMohon = mysql_fetch_assoc($rsMohon);
$totalRows_rsMohon = mysql_num_rows($rsMohon);
?>

<?php
	
	//value sent from form
	//$kategorijawatan=$_GET['JAWKAT']; 	
	$icno=$_GET['ICNO'];
	$nama=$row_rsMohon['f142Name'];
	
	//Connection ke Pangkalan Data					
	_dbConnect();
	
	//jawatan akademik
	$sql1="select * from t144mohon_jawatan where ICNO = '$icno' and jaw_kategori=1";//akademik
	$result1=mysql_query($sql1);
	$count1=mysql_num_rows($result1);
					
	//jawatan bukan akademik
	$sql2="select * from t144mohon_jawatan where ICNO = '$icno' and jaw_kategori=2";//bukan akademik
	$result2=mysql_query($sql2);
	$count2=mysql_num_rows($result2);
									
	if (($count1!="") && ($count2!=""))
	{
		echo "<fieldset>";
		echo "<legend align=\"center\">CETAKAN BORANG PERMOHONAN JAWATAN - UPNM/PEND-01</legend>";
		echo "<h2 align=\"center\"<br>Nama Pemohon: $nama<br>No K/P Pemohon: $icno<br><br> Pemohon diatas membuat permohonan bagi 2 kategori Jawatan. <br>Klik link dibawah untuk paparan Borang Permohonan</h2>";	
		echo "<hr>";				
		echo "<a href=\"int_cetak-akademik.php?icno=$icno\" target=\"_blank\"><h3 align=\"center\"><img src=\"../../images/arrow.png\"/>Cetak Borang Akademik</h3></a>";
		echo "<a href=\"int_cetak-BukanAkademik.php?icno=$icno\" target=\"_blank\"><h3 align=\"center\"><img src=\"../../images/arrow.png\"/>Cetak Borang Bukan Akademik</h3></a><br>";
		echo "</fieldset>";
		}
	elseif ($count1!="")
	{
		echo "<fieldset>";
		echo "<legend align=\"center\">CETAKAN BORANG PERMOHONAN JAWATAN - UPNM/PEND-01</legend>";
		echo "<h2 align=\"center\"><br>Nama Pemohon: $nama<br>No K/P Pemohon: $icno<br><br> Pemohon diatas membuat permohonan bagi kategori Jawatan Akademik sahaja. <br>Klik link dibawah untuk paparan Borang Permohonan</h2>";	
		echo "<hr>";				
		echo "<a href=\"int_cetak-akademik.php?icno=$icno\" target=\"_blank\"><h3 align=\"center\"><img src=\"../../images/arrow.png\"/>Cetak Borang Akademik</h3></a>";
		echo "</fieldset>";
		}
	elseif ($count2!="")
	{
		echo "<fieldset>";
		echo "<legend align=\"center\">CETAKAN BORANG PERMOHONAN JAWATAN - UPNM/PEND-01</legend>";
		echo "<h2 align=\"center\"><br>Nama Pemohon: $nama<br>No K/P Pemohon: $icno<br><br> Pemohon diatas membuat permohonan bagi kategori Jawatan Bukan Akademik sahaja. <br>Klik link dibawah untuk paparan Borang Permohonan</h2>";	
		echo "<hr>";				
		echo "<a href=\"int_cetak-BukanAkademik.php?icno=$icno\" target=\"_blank\"><h3 align=\"center\"><img src=\"../../images/arrow.png\"/>Cetak Borang Bukan Akademik</h3></a><br>";
		echo "</fieldset>";
		}
	else
	{
		echo "Tiada Rekod.";
		}

mysql_free_result($rsMohon);
?>
