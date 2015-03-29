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
<title>Untitled Document</title>
<style type="text/css">
@import url("Common/style-blue.css");
</style>
</head>

<body>
<div id="header">
  <p>&nbsp;</p>
  <div id="menu">
    <ul>
      <?php menupengguna(); ?>
      <li class="current_page_item"><a href="<?php echo $logoutAction ?>" onclick="return validate_logout()">Keluar</a></li>
    </ul>
  </div>
  <!-- end #menu -->
  <div id="search">
    <div id="search2"> <span class="welcome"><br />
      <a href="profile/profile.php"><img src="../images/icon-16-contacts.png" alt="" width="16" height="16" /></a> <?php echo $row_rsUser['f142Name']; ?></span></div>
  </div>
  <br />
  <!-- end #search -->
</div>
</body>
</html>