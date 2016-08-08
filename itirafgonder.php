<?php
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
<?php
session_start();
if(isset($_POST['login'])){
    require 'ayar.php';
    $username = $_POST['nick'];
    $password = $_POST['pass'];
    $result   = mysqli_query($con, 'select * from user where nick="'.$username.'" and sifre="'.$password.'"');
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
session_start();
ob_start();
include ("ayar.php");
$hata = "<script> alert('Mesaj Çok Kısa!')</script>";
$hata2 = "<script> alert('Üye Ol!')</script>";
$tarih     = date("Y-m-d H:i:s"); 

    if ($_POST){
        $mesaj      = $_POST["mesaj"];
        $mesaj      = addslashes($mesaj);
        $nick       = $_SESSION["nick"];
        $id         = $_SESSION["id"];
        $userid     = mysql_query("select * from user where nick='$nick' ");
        $wt         = mysql_fetch_array($userid);
        $yazan      = $wt["id"];
        $cinsiyet   = $wt["cinsiyet"];

        
        if (trim($mesaj)=="" || strlen(trim($mesaj))<4){
            echo $hata;
        }
        else {
            if(!isset($_SESSION["login"])){
                echo $hata2; 
            } 
            else{
                $ip = GetIP();

                $ekle = mysql_query(" insert into itiraflar (id, mesaj, itarih, gonderen, gonderen_id, ip, aktiftarih, cinsiyet) 
                                values ('NEW.id' , '$mesaj' , '$tarih' , '$nick' , '$yazan' , '$ip' , '$tarih', '$cinsiyet' ) ");
            ?>
    
                <div class='itiraflar'>
                <div class='itiraf'>
                <?php if($cinsiyet == 1){?>
                <div class='profilresim'><a><img src='images/e.png'/></a>
                    <div class='clear'></div>
                </div>
            <?php }else{ ?>
                <div class='profilresim'><a><img src='images/k.png'/></a>
                    <div class='clear'></div>
                </div>
        <?php    } ?>
                <div class='mesaj'>
                <a href='http://www.iuhfitiraf.com/uye.php?nick=<?php echo $nick; ?>'><span class='gonderen'><?php echo $nick;?></span></a>:
                <span class='mesajicerik'><?php echo stripslashes($mesaj);?></span>
                </div>
                    <div class="info">
                        <abbr><?php echo $tarih;?></abbr>
                </div>
                <div class='butonlar'> 
                    <a class='YorumGosterbtn' onclick='YorumFormGoster("<?php echo $iid; ?>")'> <img src='images/comment_reply.png'/> Yorum Yaz </a> 
                </div>
                </div>
                </div>
    <?php        
    }   
        }
    }

?>
    </html>