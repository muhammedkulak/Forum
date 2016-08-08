<?php
ob_start();
session_start();
if(isset($_SESSION["login"]))
        header('Location: http://www.ssuitiraf.com/index.php');
?>

<?php
session_start();
if(isset($_POST['login'])){
    require 'ayar.php';
    $username = $_POST['nick'];
    $password = $_POST['pass'];
    $result   = mysqli_query($con, 'select * from user where nick="'.$username.'" and sifre="'.$password.'"');
    if(mysqli_num_rows($result) == 1){
        $_SESSION['nick'] = $username;
        header('Location: http://www.ssuitiraf.com/index.php');
    }
    else
        echo 'Wrong!!!!!';
}
include ("logo.php");
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
    
    <script type="text/javascript" src="style/jquery.timeago.js"></script>
    <script type="text/javascript" src="style/rgbcolor.js"></script>
    
    <link rel="stylesheet" media="screen" type="text/css" href="style/colorpicker/colorpicker.css" />
    <script type="text/javascript" src="style/colorpicker/colorpicker.js"></script>
    
    <script type="text/javascript" src="style/fancybox/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="style/fancybox/jquery.fancybox.css" media="screen" />
        <meta name="description" content="<?php echo $logo;?> itiraf Üye Ol" />
        <meta name="keywords" content="<?php echo $logo;?> itiraf,üyelik" />
        <title><?php echo $logo;?> İtiraf - Üye Girişi</title>
    
    </head>
    
    <body>
        <div class='body-container'>

<div class='header'>
  
  <div class='logo'><?php echo $logo;?> İtiraf</div>
  
  <div class='menu-container'>
    <div class='menu'>
        <a id='mn-index' href='index.php'>Anasayfa</a>
<!--        <a id='mn-arama' href='arama.php'>Arama</a>-->
        <a id='mn-uyegiris' href='uyegiris.php'>Üye Girişi</a>
        <a id='mn-uyeol' href='uyeol.php'>Üye Ol</a>
        <a id='mn-sozlesme' href='sozlesme.php'>Sözleşme</a>
        <a id='mn-bizkimiz' href='bizkimiz.php'>İletişim</a>
            
      <div class='scrollTop'><a onclick='BasaDon()'>Başa Dön</a></div>
    </div>
  </div>
  
</div>

<script>$('#mn-uyegiris').addClass('selected');</script>

<div class='content-container'>
  <div class='content'>
    <div class='sayfa'>
      
            
            <div class='uyelik'>
                <form action='sifremiunuttum2.php' method='post'>
        <p>Şifre sıfırlama maili gönderilecek adres:</p>
        <p><span>Email:</span>  <span class='sag'><input type='text' id='email' name='email' value='' /></span></p>
        
        <p>
        <input type='submit' name='gonder' id='gonder' value='Gönder'/>
        </p>
                </form>
        </div>
      
    </div>
  
  </div>
</div>
        </div>
    </body>
</html>

<?php
ob_end_flush();
?>