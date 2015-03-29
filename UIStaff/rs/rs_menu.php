<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script src="../../SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />

<body>
<div id="menuPortal" class="Accordion" tabindex="0">
  <div class="AccordionPanel">
    <div class="AccordionPanelTab">Menu Profail Pengguna</div>
    <div class="AccordionPanelContent">
      <ul>
        <li>
                      <?php
								$str_link = "../profile/profile_avatar.php";
								//$str_link = "#";
							?>
                      <a href="<?php echo $str_link; ?>" target="_self">
                        <?php
                            $str = $row_rsUser['f142photo'];
							$strnoimage = "noimage.png";

                            if ($str == "")
							{
								echo "<img src=\"../../Passport/$strnoimage\" width=\"82\" height=\"100\" >";
								echo "<br>";
								echo "Kemaskini Gambar";
							}
                            else 
							{
								echo "<img src=\"../../Passport/$str\" width=\"82\" height=\"100\" >";
								echo "<br>";
								echo "Kemaskini Gambar";
							}
                            ?>
          </a></li>
		<li><img src="../../images/arrow.png" /><a href="rekod_ins.php">Merekod Surat</a></li>
		<li><img src="../../images/arrow.png" /><a href="semakan.php">Rekod Keluar/Masuk Surat</a></li>
        <!--<li><img src="../../images/arrow.png" /><a href="status.php">Status Penghantaran</a></li>-->
        <li><img src="../../images/arrow.png" /><a href="statistik.php">Statistik</a></li>      
      </ul>
    </div>
  </div>
<div class="AccordionPanel">
    <div class="AccordionPanelTab">Bantuan Pengguna</div>
    <div class="AccordionPanelContent">
    <ul>
    	<li><img src="../../images/help.png" /> <a href="../../Docs/manual.pdf" target="_blank">Manual Pengguna</a></li>
       </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("menuPortal");
</script>
</body>
</html>