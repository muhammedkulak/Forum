<?php
session_start();
include("ayar.php");
include ("logo.php");
ob_start();
if(_POST){
    
    $email  = $_POST["email"];
    $sql    = mysql_query("select * from user where email='$email' ");
    $satir  = mysql_num_rows($sql);
    
    if($satir==0){
        echo "<script> alert('Böyle bir E-Mail bulunmamaktadır!!')";
        header('Location: http://www.ssuitiraf.com/uyegiris.php');

    }else{
        $user       = mysql_query(" select * from user where email='$email' "); 
        $sor        = mysql_fetch_array($user);
        $nick       = $sor["nick"];
        $pass       = $sor["sifre"];
        $kod        = $sor["kod"];
          
        include "class.phpmailer.php";  
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->Username = '';
        $mail->Password = '';
        $mail->SetFrom($mail->Username, 'İtiraf');
        $mail->AddAddress($email, $nick);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = ' Aktivasyon İste ';
        $content = '<meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
        <div style="background: #eee; padding: 10px; font-size: 14px">Merhaba '.$nick.' <br> Kayıt olduğun için teşekkürler. <br> Lütfen aşağıdaki bağlantıyı kullanarak üyeliğini doğrula ve kullanmaya başla!.<br>
            http://ssuitiraf.com/dogrula.php?email='.$email.'&kod='.$kod.'  </div>';
        $mail->MsgHTML($content);
        if($mail->Send()) {
            echo "<script> alert('Aktivasyon maili E-Mail adresinize gönderilmiştir.')";
            header('Location: http://www.ssuitiraf.com/uyegiris.php');
        } else {
            echo"<div style='text-align:center;'>Bir Hata Oluştu. Tekrar Deneyin. Hata Devam Ederse Lütfen İletişime Geçiniz.</div> ";
            echo $mail->ErrorInfo;
            }   
    }
}
ob_end_flush();
?>