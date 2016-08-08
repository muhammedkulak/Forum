<?php
ob_start();
session_start();
include("ayar.php");
date_default_timezone_set('Europe/Istanbul');
function GetIP(){
	if(getenv("HTTP_CLIENT_IP")) {
 		$ip = getenv("HTTP_CLIENT_IP");
 	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
 		$ip = getenv("HTTP_X_FORWARDED_FOR");
 		if (strstr($ip, ',')) {
 			$tmp = explode (',', $ip);
 			$ip = trim($tmp[0]);
 		}
 	} else {
 	$ip = getenv("REMOTE_ADDR");
 	}
	return $ip;
}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="style/reset.css" />
    <link rel="stylesheet" type="text/css" href="style/main.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/mobile.css" rel="stylesheet" media="(max-width: 768px)">
    
    <script type="text/javascript" src="style/jquery-1.6.4.min.js"></script>
    
    <!-- ne olduğunu anlayana kadar yorum kalsın..
    <link type="text/css" src="cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8" />
    <script type="text/javascript" src="cometchat/cometchatjs.php" charset="utf-8"></script>
    -->
    
<!--    <script type="text/javascript" src="style/jquery.timeago.js"></script>-->
    <script type="text/javascript" src="style/rgbcolor.js"></script>
    
    <link rel="stylesheet" media="screen" type="text/css" href="style/colorpicker/colorpicker.css" />
    <script type="text/javascript" src="style/colorpicker/colorpicker.js"></script>
    
    <script type="text/javascript" src="style/fancybox/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="style/fancybox/jquery.fancybox.css" media="screen" />
    </head>
    <body>
    </body>
<?php
include("ayar.php");
$tarih     = date("Y-m-d H:i:s"); 


if (isset($_POST)){
    // Jquery $.ajax() fonskiyonundan gelen veriler
    // $_POST dizinde saklanıyor.
    $yorum      = $_POST['yorummsj'];
    $yorum     = addslashes($yorum);
    $nick       = $_SESSION['nick'];
    $iid        = $_POST["itirafid"];

    $userid     = mysql_query("select * from user where nick='$nick' ");
    $wt         = mysql_fetch_array($userid);
    $yazanid    = $wt["id"];
    $cinsiyet   = $wt["cinsiyet"];

    
    if(trim($yorum) == "" || strlen(trim($yorum)) < 4){
        echo "<script> alert('Mesaj Çok Kısa!')</script>";
    }elseif(!isset($_SESSION["login"])){
            echo "<script> alert('Üye Ol!')</script>"; 
            break;
    }else {
        $ip = GetIP();

        $ekle = mysql_query(" insert into yorumlar (id, yazan, yorum, ytarih, itirafID, yazanID, ip, cinsiyet)
                            values ( 'NEW.id' , '$nick' , '$yorum' , '$tarih' , '$iid' , '$yazanid' , '$ip' , '$cinsiyet' ) ");
        
        $updateitiraf = mysql_query(" update itiraflar set aktiftarih='$tarih' where id='$iid' ");
        
        echo "<div class='itiraflar'>";
        echo "<div class='itiraf'>";
        echo "<div class='yorumlar'";
        echo "<div class='gizliyorum'";
        echo "<div class='mesaj'>";
        echo "<span class='gonderen'>".$nick."</span>: ";
        echo "<span class='mesajicerik'>".stripslashes($yorum)."</span>";
        echo "<span class='info'>".$tarih."</span>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
            
    }
}




?>
</html>
<?php
ob_end_flush();
?>