<?php
function resumeMessage()
{
	$strmessage='Sistem Pengurusan E-Mail, Universiti Pertahanan Nasional Malaysia';
	echo $strmessage;
}
function generateRandpassword($size=9, $power=0) {
    $vowels = 'aeuy';
    $randconstant = 'bdghjmnpqrstvz';
    if ($power & 1) {
        $randconstant .= 'BDGHJLMNPQRSTVWXZ';
    }
    if ($power & 2) {
        $vowels .= "AEUY";
    }
    if ($power & 4) {
        $randconstant .= '23456789';
    }
    if ($power & 8) {
        $randconstant .= '@#$%';
    }

    $Randpassword = '';
    $alt = time() % 2;
    for ($i = 0; $i < $size; $i++) {
        if ($alt == 1) {
            $Randpassword .= $randconstant[(rand() % strlen($randconstant))];
            $alt = 0;
        } else {
            $Randpassword .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $Randpassword;
}
function datetime_time()
{
	date_default_timezone_set ("Asia/Kuala_Lumpur"); 
	$date = new DateTime(); 
	echo $date->format('Y-m-d H:i:s');
}
function getIP() 
{ 
	$ip; 
		if (getenv("HTTP_CLIENT_IP")) 
	$ip = getenv("HTTP_CLIENT_IP"); 
		else if(getenv("HTTP_X_FORWARDED_FOR")) 
	$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if(getenv("REMOTE_ADDR")) 
	$ip = getenv("REMOTE_ADDR"); 
		else 
	$ip = "UNKNOWN";
		echo $ip; 
}
?>