<?php
ob_start();
session_start();
include("ayar.php");

if(isset($_SESSION["login"])==false){
        header('Location: http://www.ssuitiraf.com/index.php');}

if(_POST){
    
    $nick1  = $_SESSION["nick"];
    $durum   = $_POST["durum"];
    
    if($durum == ""){
        echo "<script> alert('Boşluğu Doldurun!!')</script>";
        header ('Location: http://www.ssuitiraf.com/profil.php');
    }
    else{
    $sor    = mysql_query("select * from user where nick='$nick1' ");
    $sql    = mysql_fetch_array($sor);
    $email  = $sql["email"]; 
    $update = mysql_query("update user set durum='$durum' where email='$email' ");
    
    if($update){
        echo "<script> alert('İşlem Başarılı..')</script>";
        header ('Location: http://www.ssuitiraf.com/profil.php');
    }else{
        echo "<script> alert('İşlem Başarısız!!')</script>";
    }   
}
}
ob_end_flush();
?>