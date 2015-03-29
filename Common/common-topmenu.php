<?php
/* 	MENU PUBLIC
	current item - utama)//current item - utama
*/
function menupublic_utama()
{
	echo "<li class='current_page_item'><a href='index.php'>Utama</a></li>";
	echo "<li><a href='login.php'>Login</a>";
	echo "<li><a href='bantuan.php'>Manual Pengguna</a></li>";
	echo "<li><a href='hubungi.php'>Hubungi ALK</a></li>";
}
function menupublic_login()
{
	echo "<li><a href='index.php'>Utama</a></li>";
	echo "<li class='current_page_item'><a href='login.php'>Login</a>";
	echo "<li><a href='bantuan.php'>Manual Pengguna</a></li>";
	echo "<li><a href='hubungi.php'>Hubungi ALK</a></li>";

}
function menupublic_faq()
{
	echo "<li><a href='index.php'>Utama</a></li>";
	echo "<li><a href='login.php'>Login</a>";
	echo "<li class='current_page_item'><a href='bantuan.php'>Manual Pengguna</a></li>";
	echo "<li><a href='hubungi.php'>Hubungi ALK</a></li>";

}
function menupublic_tentang()
{
	echo "<li><a href='index.php'>Utama</a></li>";
	echo "<li><a href='login.php'>Login</a>";
	echo "<li><a href='bantuan.php'>Manual Pengguna</a></li>";
	echo "<li><a href='hubungi.php'>Hubungi ALK</a></li>";
}
function menupublic_hubungikami()
{
	echo "<li><a href='index.php'>Utama</a></li>";
	echo "<li><a href='login.php'>Login</a>";
	echo "<li><a href='bantuan.php'>Manual Pengguna</a></li>";
	echo "<li class='current_page_item'><a href='hubungi.php'>Hubungi ALK</a></li>";
}
/*-------------------------------------------------------------------------------------*/
/* 	MENU KETUA BATALION
	current item - utama
*/
function menupengurus()
{
	echo "<li class='current_page_item'><a href='desktop-manager.php'>Utama</a></li>";
	echo "<li><a href='profile/profile_admin.php'>Profil</a>";
	echo "<li><a href='rs/rs.php'>Papar Kesalahan</a></li>";
	
}

function menustaff()
{
	echo "<li class='current_page_item'><a href='desktop-manager.php'>Utama</a></li>";
	echo "<li><a href='profile/profile_admin.php'>Profil</a>";
	echo "<li><a href='lapor.php'>Lapor Kesalahan</a></li>";
	
}
/* 	MENU KETUA BATALION
	current item - profile
*/
function menupengurus_profile()
{
	echo "<li class='current_page_item'><a href='desktop-manager.php'>Utama</a></li>";
	echo "<li><a href='profile/profile_admin.php'>Profil</a>";
	echo "<li><a href='../lapor.php'>Lapor Kesalahan</a></li>";
	
	
}
/* 	MENU KETUA BATALION
	current item - papar_kesalahan
*/
function menupengurus_rs()
{
	echo "<li><a href='../desktop-manager.php'>Utama</a></li>";
	echo "<li><a href='profile/profile_admin.php'>Profil</a>";
	echo "<li class='current_page_item'><a href='rs.php'>Papar Kesalahan</a></li>";
	
}
/* 	MENU PENGURUS
	current item - TBM
*/
function menupengurus_tbm()
{
	echo "<li><a href='../desktop-manager.php'>Utama</a></li>";
	echo "<li><a href='profile/profile_admin.php'>Profil</a>";
	echo "<li><a href='../rs/rs.php'>Papar Kesalahan</a></li>";
	
}

/* 	MENU PENGGUNA
	current item - Utama
*/
function menupengguna()
{
	echo "<li class='current_page_item'><a href='desktop-user.php'>Utama</a></li>";
	echo "<li><a href='profile/profile_admin.php'>Profil</a>";
	echo "<li><a href='rs/rs.php'>Papar Kesalahan</a></li>";
	
}
function menupengguna_profail()
{
	echo "<li><a href='../desktop-user.php'>Utama</a></li>";
	echo "<li><a href='../profile/profile_admin.php'>Profil</a>";
	echo "<li><a href='../rs/rs.php'>Papar Kesalahan</a></li>";
	
}
function menupengguna_RS()
{
	echo "<li><a href='../desktop-user.php'>Utama</a></li>";
	echo "<li><a href='../profile/profile_admin.php'>Profil</a>";	
	echo "<li class='current_page_item'><a href='../rs/rs.php'>Papar Kesalahan</a></li>";

}
function menupengguna_TBM()
{
	echo "<li><a href='../desktop-user.php'>Utama</a></li>";
	echo "<li><a href='../profile/profile_admin.php'>Profil</a>";	
	echo "<li><a href='../rs/rs.php'>Papar Kesalahan</a></li>";
	
}
?>