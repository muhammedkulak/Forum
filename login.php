<?php
ob_start();
session_start();
include ("ayar.php");
error_reporting(0);
if(isset($_SESSION["login"]))
        header('Location: http://www.ssuitiraf.com/index.php');
if(_POST){

    $nick       = $_POST["nick"];
    $sifre      = $_POST["pass"];
    
    $sorgu      = mysql_query("select * from user where nick='$nick' and sifre='$sifre'");
    $verisay    = mysql_num_rows($sorgu);
    $sor        = mysql_fetch_array($sorgu);
    $aktif      = $sor["aktif"];
    $rutbe      = $sor["rutbe"];

    if($nick == "" || $sifre == ""){
        echo "<script> alert('Boş Alan Bırakmayınız!')</script>";
        header ('refresh:0.0; url=http://www.ssuitiraf.com/uyegiris.php');
    }
    else if($sor["nick"] != $nick && $sor["sifre"] != $sifre){
        echo "<script> alert('Kullanıcı adı veya şifre yanlış!!')</script>";
        header('refresh:0.0; url=http://www.ssuitiraf.com/uyegiris.php');
    }
    else if($aktif != 1 ){
        echo "<script> alert('Mail Aktivasyonu Yapınız!')</script>";
        header('refresh:0.0; url=http://www.ssuitiraf.com/uyegiris.php');
    }
    else if($rutbe == 99){
        echo "<script> alert('Üyeliğiniz geçici olarak engellenmiştir!')</script>";
        header('refresh:0.0; url=http://www.ssuitiraf.com/sozlesme.php');
    }
    else {
        if ($nick && $sifre){
//            $nick       = $_SESSION["nick"];
            $sorgu      = mysql_query("select * from user where nick='$nick' and sifre='$sifre'");
            $verisay    = mysql_num_rows($sorgu);
            $sor        = mysql_fetch_array($sorgu);
            if($verisay > 0){
                $_SESSION["login"] = true;
                $_SESSION["nick"]  = $nick;
                $_SESSION["pass"]  = $pass;
                //echo "Başarılı";
                header("refresh: 0.0 ; url=http://www.ssuitiraf.com/index.php");
//                		header("Location: index.php");
//            header( "refresh:0.0;url=uyegiris.php" );
//                header('Location: http://ssuitiraf.com/index222.php');
//                header('refresh:0.0; url=index.php');
            }else{
                echo "<script> alert('Kullanıcı adı veya şifre yanlış!')</script>";
                header('refresh:0.0; url=http://www.ssuitiraf.com/uyegiris.php');
            }
        }
    }
}
?><meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />