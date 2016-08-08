<?php
ob_start();
session_start();
include("ayar.php");
include ("logo.php");

if(_POST){
    
    $email  = $_POST["email"];
    $sql    = mysql_query("select * from user where email='$email' ");
    $satir  = mysql_num_rows($sql);
    
    
    if($satir==0){
        echo "<script> alert('Böyle bir E-Mail bulunmamaktadır!!')";
        header('Location: http://www.ssuitiraf.com/sifremiunuttum.php');

    }else{
    
    function randompass() {
    $semboller = "1234567890abcdefGHIJKLMNOPQRSTUVWxyzABCDEFghijklmnopqrstuvwXYZ1234567890"; 
    $sifre = ''; 
        for($i=0;$i<7;$i++) 
        { 
            $sifre .= $semboller{rand() % 39}; 
        } 
    return $sifre; 
        }
    $sifre=randompass(); 
    
    $update = mysql_query("update user set sifre='$sifre' where email='$email' ");
    
    $user       = mysql_query(" select * from user where email='$email' "); 
    $sor        = mysql_fetch_array($user);
    $nick       = $sor["nick"];
    $pass       = $sor["sifre"];
    
    if($update){
                    
        include "class.phpmailer.php";
        
        $mail = new PHPMailer();

        $mail->IsSMTP();

        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->Username = '';
        $mail->Password = '';

        $mail->SetFrom($mail->Username, 'SSU İtiraf');

        $mail->AddAddress($email, $nick);

        $mail->CharSet = 'UTF-8';

        $mail->Subject = ' Şifremi Unuttum ';

        $content = '<div style="background: #eee; padding: 10px; font-size: 14px">
                    Nick:   '.$nick.'<br>
                    Şifre:  '.$pass.'<br>
                    E-Mail: '.$email.'<br>
                    </div>';
        $mail->MsgHTML($content);

        if($mail->Send()) {
            echo "<div style='text-align:center;'> Yeni Şifreniz E-Mail adresinize gönderilmiştir.</div><br>";
            header('Location: http://www.ssuitiraf.com/uyegiris.php');
        } else {
            echo"<div style='text-align:center;'>Bir Hata Oluştu. Tekrar Deneyin. Hata Devam Ederse Lütfen İletişime Geçiniz.</div> ";
            echo $mail->ErrorInfo;
            }
        }
}
}



?>