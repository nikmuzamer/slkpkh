<?php require_once('../Connections/sqlconn.php'); ?>
<?php require_once('../Connections/sqlconn.php'); ?>
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

$MM_restrictGoTo = "../noaccess.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmLesen")) {
  $insertSQL = sprintf("INSERT INTO t108kemahiranlesen (f108username, f108jenislesen, f108lesenLain, f108tarikhTamat) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['hiddenUsername'], "text"),
                       GetSQLValueString($_POST['selectLesen'], "text"),
                       GetSQLValueString($_POST['textLain2'], "text"),
                       GetSQLValueString($_POST['textDateTamat'], "text"));

  mysql_select_db($database_sqlconn, $sqlconn);
  $Result1 = mysql_query($insertSQL, $sqlconn) or die(mysql_error());

  $insertGoTo = "../Portal/Lesen.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

/*$colname_esUser = "-1";
if (isset($_SESSION['f142username'])) {
  $colname_esUser = $_SESSION['f142username'];
}*/
//Carry Username and User Level
$username = $_SESSION['MM_Username'];
$userlevel = $_SESSION['MM_UserGroup'];

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsBangsa = "SELECT * FROM t109kod_bangsa";
$rsBangsa = mysql_query($query_rsBangsa, $sqlconn) or die(mysql_error());
$row_rsBangsa = mysql_fetch_assoc($rsBangsa);
$totalRows_rsBangsa = mysql_num_rows($rsBangsa);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsperingkat = "SELECT * FROM t109kod_peringkat";
$rsperingkat = mysql_query($query_rsperingkat, $sqlconn) or die(mysql_error());
$row_rsperingkat = mysql_fetch_assoc($rsperingkat);
$totalRows_rsperingkat = mysql_num_rows($rsperingkat);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rssubjek = "SELECT * FROM t109kod_subjek";
$rssubjek = mysql_query($query_rssubjek, $sqlconn) or die(mysql_error());
$row_rssubjek = mysql_fetch_assoc($rssubjek);
$totalRows_rssubjek = mysql_num_rows($rssubjek);

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsbahasa = "SELECT * FROM t109kod_bahasa";
$rsbahasa = mysql_query($query_rsbahasa, $sqlconn) or die(mysql_error());
$row_rsbahasa = mysql_fetch_assoc($rsbahasa);
$totalRows_rsbahasa = mysql_num_rows($rsbahasa);

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsUser = sprintf("SELECT * FROM t142_akaun WHERE f142username = %s ORDER BY f142username ASC", GetSQLValueString($colname_rsUser, "text"));
$rsUser = mysql_query($query_rsUser, $sqlconn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$colname_rsMohonJawatan = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsMohonJawatan = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsMohonJawatan = sprintf("SELECT * FROM t144mohon_jawatan WHERE username = %s", GetSQLValueString($colname_rsMohonJawatan, "text"));
$rsMohonJawatan = mysql_query($query_rsMohonJawatan, $sqlconn) or die(mysql_error());
$row_rsMohonJawatan = mysql_fetch_assoc($rsMohonJawatan);
$totalRows_rsMohonJawatan = mysql_num_rows($rsMohonJawatan);

$maxRows_rsLesen = 10;
$pageNum_rsLesen = 0;
if (isset($_GET['pageNum_rsLesen'])) {
  $pageNum_rsLesen = $_GET['pageNum_rsLesen'];
}
$startRow_rsLesen = $pageNum_rsLesen * $maxRows_rsLesen;

$colname_rsLesen = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsLesen = $_SESSION['MM_Username'];
}
mysql_select_db($database_sqlconn, $sqlconn);
$query_rsLesen = sprintf("SELECT * FROM t108kemahiranlesen, t109kod_lesen WHERE t108kemahiranlesen.f108jenislesen = t109kod_lesen.f109kodlesen AND t108kemahiranlesen.f108username = %s ", GetSQLValueString($colname_rsLesen, "text"));
$query_limit_rsLesen = sprintf("%s LIMIT %d, %d", $query_rsLesen, $startRow_rsLesen, $maxRows_rsLesen);
$rsLesen = mysql_query($query_limit_rsLesen, $sqlconn) or die(mysql_error());
$row_rsLesen = mysql_fetch_assoc($rsLesen);

if (isset($_GET['totalRows_rsLesen'])) {
  $totalRows_rsLesen = $_GET['totalRows_rsLesen'];
} else {
  $all_rsLesen = mysql_query($query_rsLesen);
  $totalRows_rsLesen = mysql_num_rows($all_rsLesen);
}
$totalPages_rsLesen = ceil($totalRows_rsLesen/$maxRows_rsLesen)-1;

mysql_select_db($database_sqlconn, $sqlconn);
$query_rsKodLesen = "SELECT * FROM t109kod_lesen";
$rsKodLesen = mysql_query($query_rsKodLesen, $sqlconn) or die(mysql_error());
$row_rsKodLesen = mysql_fetch_assoc($rsKodLesen);
$totalRows_rsKodLesen = mysql_num_rows($rsKodLesen);

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
<!-- TemplateBeginEditable name="doctitle" -->
<title>Sistem Pengurusan E-Pejabat, Universiti Pertahanan Nasional Malaysia</title>
<!-- TemplateEndEditable -->
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../Common/style.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
<!--
body,td,th {
	color: #000;
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
<SCRIPT language=javascript>
<!--
<!-- Begin  //place these scripts within BODY tag if you are using IE 4.0 or below.
//****************************************************************************
// PopCalendar 3.30, Emailware(please mail&commend me if u like it)
// Originally coded by Liming(Victor) Weng, email: victorwon@netease.com
// Release date: 2000.3.7
// Anyone may modify it to satify his needs, but please leave this comment ahead.
//****************************************************************************

var gdCtrl = new Object();
var goSelectTag = new Array();
var gcGray = "#808080";
var gcToggle = "#ffff00";
var gcBG = "#cccccc";

var gdCurDate = new Date();
var giYear = gdCurDate.getFullYear();
var giMonth = gdCurDate.getMonth()+1;
var giDay = gdCurDate.getDate();

function fSetDate(iYear, iMonth, iDay){
  VicPopCal.style.visibility = "hidden";
  gdCtrl.value = iDay+"/"+iMonth+"/"+iYear; //Here, you could modify the locale as you need !!!!
  for (i in goSelectTag)
  	goSelectTag[i].style.visibility = "visible";
  goSelectTag.length = 0;
}

function fSetSelected(aCell){
  var iOffset = 0;
  var iYear = parseInt(tbSelYear.value);
  var iMonth = parseInt(tbSelMonth.value);

  self.event.cancelBubble = true;
  aCell.bgColor = gcBG;
  with (aCell.children["cellText"]){
  	var iDay = parseInt(innerText);
  	if (color==gcGray)
		iOffset = (Victor<10)?-1:1;
	iMonth += iOffset;
	if (iMonth<1) {
		iYear--;
		iMonth = 12;
	}else if (iMonth>12){
		iYear++;
		iMonth = 1;
	}
  }
  fSetDate(iYear, iMonth, iDay);
}

function Point(iX, iY){
	this.x = iX;
	this.y = iY;
}

function fBuildCal(iYear, iMonth) {
  var aMonth=new Array();
  for(i=1;i<7;i++)
  	aMonth[i]=new Array(i);

  var dCalDate=new Date(iYear, iMonth-1, 1);
  var iDayOfFirst=dCalDate.getDay();
  var iDaysInMonth=new Date(iYear, iMonth, 0).getDate();
  var iOffsetLast=new Date(iYear, iMonth-1, 0).getDate()-iDayOfFirst+1;
  var iDate = 1;
  var iNext = 1;

  for (d = 0; d < 7; d++)
	aMonth[1][d] = (d<iDayOfFirst)?-(iOffsetLast+d):iDate++;
  for (w = 2; w < 7; w++)
  	for (d = 0; d < 7; d++)
		aMonth[w][d] = (iDate<=iDaysInMonth)?iDate++:-(iNext++);
  return aMonth;
}

function fDrawCal(iYear, iMonth, iCellHeight, iDateTextSize) {
  var WeekDay = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
  var styleTD = " bgcolor='"+gcBG+"' bordercolor='"+gcBG+"' valign='middle' align='center' height='"+iCellHeight+"' style='font:bold "+iDateTextSize+" verdana;";            //Coded by Liming Weng(Victor Won)  email:victorwon@netease.com

  with (document) {
	write("<tr>");
	for(i=0; i<7; i++)
		write("<td "+styleTD+"color:#990099' >" + WeekDay[i] + "</td>");
	write("</tr>");

  	for (w = 1; w < 7; w++) {
		write("<tr>");
		for (d = 0; d < 7; d++) {
			write("<td id=calCell "+styleTD+"cursor:hand;' onMouseOver='this.bgColor=gcToggle' onMouseOut='this.bgColor=gcBG' onclick='fSetSelected(this)'>");
			write("<font id=cellText Victor='Liming Weng'> </font>");
			write("</td>")
		}
		write("</tr>");
	}
  }
}

function fUpdateCal(iYear, iMonth) {
  myMonth = fBuildCal(iYear, iMonth);
  var i = 0;
  for (w = 0; w < 6; w++)
	for (d = 0; d < 7; d++)
		with (cellText[(7*w)+d]) {
			Victor = i++;
			if (myMonth[w+1][d]<0) {
				color = gcGray;
				innerText = -myMonth[w+1][d];
			}else{
				color = ((d==0)||(d==6))?"red":"black";
				innerText = myMonth[w+1][d];
			}
		}
}

function fSetYearMon(iYear, iMon){
  tbSelMonth.options[iMon-1].selected = true;
  for (i = 0; i < tbSelYear.length; i++)
	if (tbSelYear.options[i].value == iYear)
		tbSelYear.options[i].selected = true;
  fUpdateCal(iYear, iMon);
}

function fPrevMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (--iMon<1) {
	  iMon = 12;
	  iYear--;
  }

  fSetYearMon(iYear, iMon);
}

function fNextMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (++iMon>12) {
	  iMon = 1;
	  iYear++;
  }

  fSetYearMon(iYear, iMon);
}

function fToggleTags(){
  with (document.all.tags("SELECT")){
 	for (i=0; i<length; i++)
 		if ((item(i).Victor!="Won")&&fTagInBound(item(i))){
 			item(i).style.visibility = "hidden";
 			goSelectTag[goSelectTag.length] = item(i);
 		}
  }
}

function fTagInBound(aTag){
  with (VicPopCal.style){
  	var l = parseInt(left);
  	var t = parseInt(top);
  	var r = l+parseInt(width);
  	var b = t+parseInt(height);
	var ptLT = fGetXY(aTag);
	return !((ptLT.x>r)||(ptLT.x+aTag.offsetWidth<l)||(ptLT.y>b)||(ptLT.y+aTag.offsetHeight<t));
  }
}

function fGetXY(aTag){
  var oTmp = aTag;
  var pt = new Point(0,0);
  do {
  	pt.x += oTmp.offsetLeft;
  	pt.y += oTmp.offsetTop;
  	oTmp = oTmp.offsetParent;
  } while(oTmp.tagName!="BODY");
  return pt;
}

// Main: popCtrl is the widget beyond which you want this calendar to appear;
//       dateCtrl is the widget into which you want to put the selected date.
// i.e.: <input type="text" name="dc" style="text-align:center" readonly><INPUT type="button" value="V" onclick="fPopCalendar(dc,dc);return false">
function fPopCalendar(popCtrl, dateCtrl){
  gdCtrl = dateCtrl;
  fSetYearMon(giYear, giMonth);
  var point = fGetXY(popCtrl);
  with (VicPopCal.style) {
  	left = point.x;
	top  = point.y+popCtrl.offsetHeight+1;
	width = VicPopCal.offsetWidth;
	height = VicPopCal.offsetHeight;
	fToggleTags(point);
	visibility = 'visible';
  }
  VicPopCal.focus();
}

function fHideCal(){
  var oE = window.event;
  if ((oE.clientX>0)&&(oE.clientY>0)&&(oE.clientX<document.body.clientWidth)&&(oE.clientY<document.body.clientHeight)) {
	var oTmp = document.elementFromPoint(oE.clientX,oE.clientY);
	while ((oTmp.tagName!="BODY") && (oTmp.id!="VicPopCal"))
		oTmp = oTmp.offsetParent;
	if (oTmp.id=="VicPopCal")
		return;
  }
  VicPopCal.style.visibility = 'hidden';
  for (i in goSelectTag)
	goSelectTag[i].style.visibility = "visible";
  goSelectTag.length = 0;
}

var gMonths = new Array("January","February","March","April","May","June","July","August","September","October","November","December");

with (document) {
write("<DIV id='VicPopCal' onblur='fHideCal()' onclick='focus()' style='POSITION:absolute;visibility:hidden;border:1px ridge;height:10;width:10;z-index:100;'>");
write("<table border='0' bgcolor='#6699cc'>");
write("<TR>");
write("<td valign='middle' align='center'><input type='button' name='PrevMonth' value='<' style='height:20;width:20;FONT:16 Fixedsys' onClick='fPrevMonth()' onblur='fHideCal()'>");
write("&nbsp;&nbsp;<select name='tbSelMonth' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won' onclick='self.event.cancelBubble=true' onblur='fHideCal()'>");
for (i=0; i<12; i++)
	write("<option value='"+(i+1)+"'>"+gMonths[i]+"</option>");
write("</SELECT>");
write("&nbsp;&nbsp;<SELECT name='tbSelYear' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won' onclick='self.event.cancelBubble=true' onblur='fHideCal()'>");
for(i=1940;i<2015;i++)
	write("<OPTION value='"+i+"'>&nbsp;&nbsp;"+i+"&nbsp;&nbsp;</OPTION>");
write("</SELECT>");
write("&nbsp;&nbsp;<input type='button' name='PrevMonth' value='>' style='height:20;width:20;FONT:16 Fixedsys' onclick='fNextMonth()' onblur='fHideCal()'>");
write("</td>");
write("</TR><TR>");
write("<td align='center'>");
write("<DIV style='background-color:teal;'><table width='100%' height='100%' border='0'>");
fDrawCal(giYear, giMonth, 10, 11);
write("</table></DIV>");
write("</td>");
write("</TR><TR><TD align='center'>");
write("<font face=Verdana size=1><B style='cursor:hand' onclick='fSetDate(giYear,giMonth,giDay); self.event.cancelBubble=true' onMouseOver='this.style.color=gcToggle' onMouseOut='this.style.color=0'>Today:&nbsp;&nbsp;"+gMonths[giMonth-1]+"&nbsp;"+giDay+",&nbsp;&nbsp;"+giYear+"</B></font>");
write("</TD></TR>");write("</TD></TR>");
write("</TABLE></Div>");
}
// End -- Coded by Liming Weng, email: victorwon@netease.com -->

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</SCRIPT>
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
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
	      <li ><a href="../UIUser/application/desktop-user.php">Utama</a></li>
	      <li class="current_page_item"><a href="../Portal/resume.php">Resume</a></li>
	      <li><a href="../Portal/profile.php">Profail</a>
	      </li><li><a href="../Portal/password.php">Password</a></li>
	      <li><a href="../Portal/Bantuan.php">FAQs</a></li>
	      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
	      <!--<li><span class="current_page_item"><a href="<?php echo $logoutAction ?>" onClick="return validate_logout()">Logout</a></span></li>-->
        </ul>
      </div>
	  <!-- end #menu -->
	  <br />
	  <div id="search"><span class="welcome"><?php echo $row_rsUser['f142Name']; ?></span></div>
	  <!-- end #search -->
  </div>
	<!-- end #header -->
	<!-- end #header-wrapper -->
	<div id="page">
	<div id="page-bgtop">
		<div id="content">
		  <div class="post">
		    <h2 class="title"><a href="#" class="title">LESEN MEMANDU (Jawatan Pemandu dan Pembantu Am Rendah)</a></h2>
		    <p class="meta">Created  Jumaat, Januari 29, 2009 11.44 AM Maintain by <a href="#">Unit Aplikasi Staf &amp; Web</a>		    </p>
		    <div class="entry">
		      <form name="frmLesen" id="frmLesen" method="POST" action="<?php echo $editFormAction; ?>">
<table width="658" height="90" border="1">
  <tr>
    <td width="146" bgcolor="#CCCCCC"><div align="right"><strong>Nama:</strong></div></td>
    <td width="496" bgcolor="#CCCCCC"><?php echo $row_rsUser['f142Name']; ?></td>
	              </tr>
  <tr>
    <td bgcolor="#CCCCCC" ><div align="right"><strong>No. Kad Pengenalan:</strong></div></td>
    <td bgcolor="#CCCCCC"><?php echo $row_rsUser['f142noIC']; ?></td>
	              </tr>
  <tr>
    <td valign="top" bgcolor="#CCCCCC" ><div align="right"><strong>Jawatan Yang Dimohon :</strong></div></td>
    <td bgcolor="#CCCCCC"><?php do { ?>
      <?php echo $row_rsMohonJawatan['jawatanDesc']; ?> <?php echo $row_rsMohonJawatan['jawatanGred']; ?><br />
	                <?php } while ($row_rsMohonJawatan = mysql_fetch_assoc($rsMohonJawatan)); ?></td>
	              </tr>
	            </table>
<table width="657" border="0">
  <tr>
    <td width="150">&nbsp;</td>
    <td width="331"><input name="hiddenUsername" type="hidden" id="hiddenUsername" value="<?php echo $row_rsUser['f142username']; ?>" />
	                <input name="hdnFieldLesenID" type="hidden" id="hdnFieldLesenID" value="<?php echo $row_rsLesen['f108lesenID']; ?>" /></td>
    <td width="35">&nbsp;</td>
    <td width="123">&nbsp;</td>
	              </tr>
  <tr>
    <td bgcolor="#FFFFFF"><div align="right">Jenis Lesen:</div></td>
    <td bgcolor="#FFFFFF"><select name="selectLesen" size="1" id="selectLesen">
      <option value="0">---Sila Pilih---</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsKodLesen['f109kodlesen']?>"><?php echo $row_rsKodLesen['f109kelaslesen']?></option>
      <?php
} while ($row_rsKodLesen = mysql_fetch_assoc($rsKodLesen));
  $rows = mysql_num_rows($rsKodLesen);
  if($rows > 0) {
      mysql_data_seek($rsKodLesen, 0);
	  $row_rsKodLesen = mysql_fetch_assoc($rsKodLesen);
  }
?>
	                </select></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>&nbsp;</td>
	              </tr>
  <tr>
    <td><div align="right">Lain-lain Lesen yang : Ditauliahkan </div></td>
    <td><input name="textLain2" type="text" id="textLain2" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	              </tr>
  <tr>
    <td><div align="right">Tarikh Tamat :</div></td>
    <td><input type="text" name="textDateTamat" id="textDateTamat" onclick="fPopCalendar(textDateTamat,textDateTamat); return false" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	              </tr>
	            </table>
<table width="655" border="0">
  <tr>
    <td width="151">&nbsp;</td>
    <td width="494"><strong>
      <input type="submit" name="btnTambah" id="btnTambah" value="Tambah" />
    </strong></td>
	              </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
	              </tr>
  <tr>
    <td colspan="2"><fieldset>
	                  <legend>Paparan Maklumat Lesen</legend>
      <table width="647" height="41">
        <tr>
          <td width="256" bgcolor="#CCCCCC"><strong>Jenis Lesen</strong></td>
          <td width="146" bgcolor="#CCCCCC"><strong>Tarikh tamat </strong></td>
          <td width="143" bgcolor="#CCCCCC"><strong>Lain-Lain lesen</strong></td>
          <td width="82" bgcolor="#CCCCCC"><strong>Tindakan</strong></td>
	                    </tr>
        <?php do { ?>
          <tr>
<td><?php echo $row_rsLesen['f109kelaslesen']; ?></td>
            <td><?php echo $row_rsLesen['f108tarikhTamat']; ?></td>
            <td><?php echo $row_rsLesen['f108lesenLain']; ?></td>
            <td><!-- TemplateBeginEditable name="EditRegion1" --><a href="../Portal/Lesen-edit.php?ID=<?php echo $row_rsLesen['f108lesenID']; ?>"><img src="../images/database-process-icon.png" alt="edit" width="24" height="24" /></a><!-- TemplateEndEditable --><a href="../Portal/Lesen-delete.php?lesenID=<?php echo $row_rsLesen['f108lesenID']; ?>"onClick="return confirm_delete()"><img src="../images/database-remove-icon.png" alt="" width="16" height="16" /></a></td>
	                      </tr>
          <?php } while ($row_rsLesen = mysql_fetch_assoc($rsLesen)); ?>
                      </table>
    </fieldset></td>
	              </tr>
	            </table>
<input type="hidden" name="MM_insert" value="frmLesen" />
              </form>
		    </div>
	      </div>
	  </div>
		<!-- end #content -->
		<div id="sidebar">
		  <ul>
		    <li>
		      <h2>Menu Resume</h2>

		      <ul>
<li><a href="#"></a><a href="../Portal/peribadi.php">Peribadi</a></li>
<li ><a href="../Portal/akademik.php">Akademik</a></li>
<li ><a href="../Portal/akademik-result.php">Keputusan Akademik</a></a></li>
<li><a href="../Portal/pengalaman.php">Pengalaman Kerja</a></li>
<li><a href="../Portal/pengalaman_tambahan.php">Pengalaman Kerja (Tambahan)</a></li>
<li><a href="../Portal/Kemahiran_Bahasa.php">Kemahiran Bahasa</a></li>
<li><a href="../Portal/Kemahiran_Trengkas.php">Kemahiran Menaip & Trengkas</a></li>
<li><a href="../Portal/Kemahiran_komputer.php">Kemahiran Komputer</a></li>
<li><a href="../Portal/Lesen.php">Kemahiran Lesen</a></li>
<li><a href="../Portal/rujukan.php">Rujukan</a></li>
<li><a href="../Portal/Pengakuan.php">Pengakuan</a></li>
	          </ul>
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
mysql_free_result($rsBangsa);

mysql_free_result($rsperingkat);

mysql_free_result($rssubjek);

mysql_free_result($rsbahasa);

mysql_free_result($rsUser);

mysql_free_result($rsMohonJawatan);

mysql_free_result($rsLesen);

mysql_free_result($rsKodLesen);
?>
