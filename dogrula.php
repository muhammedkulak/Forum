<?php
ob_start();
include("ayar.php");
?>
<meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
<?php
$email  = $_GET['email'];
$kod    = $_GET['kod'];
$ara    = mysql_query(" select email, kod, aktif from user where email='$email' and kod='$kod' and aktif='0' ");
$kayit  = mysql_query(" update user set aktif=1  where email='$email' and kod='$kod' ");
if($kayit){
    echo " Kayıt Başarılı. ";
    header ('Location: http://www.ssuitiraf.com/uyegiris.php');
}else{
    echo " Kayıt Başarısız!! ";
}
ob_end_flush();
?>