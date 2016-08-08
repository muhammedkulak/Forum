<?php
ob_start();
session_start();
include ("ayar.php");
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
    
<!--    <script type="text/javascript" src="style/jquery.timeago.js"></script>-->
    <script type="text/javascript" src="style/rgbcolor.js"></script>
    
    <link rel="stylesheet" media="screen" type="text/css" href="style/colorpicker/colorpicker.css" />
    <script type="text/javascript" src="style/colorpicker/colorpicker.js"></script>
    
    <script type="text/javascript" src="style/fancybox/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="style/fancybox/jquery.fancybox.css" media="screen" />
    
        <script>
/* SCROLL KONTROL */
/* ---------------------------------------------------*/
$(function(){
  $(window).scroll(function() {
    var pos = $(window).scrollTop();
    var limit = 50;
    if (pos <= limit) $('.header').css({marginTop:"-"+pos+"px"});
    else $('.header').css({marginTop:"-"+limit+"px"});
  });
});

function BasaDon() {
  $("html,body").animate({ scrollTop: 0 }, 150);
}

function clientHeight() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerHeight ) == 'number' ) {
    //Non-IE
    myHeight = window.innerHeight;
  } else if( document.documentElement && document.documentElement.clientHeight ) {
    //IE 6+ in 'standards compliant mode'
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && document.body.clientHeight ) {
    //IE 4 compatible
    myHeight = document.body.clientHeight;
  }
  return myHeight;
}

var sort = "d";
var DevamLimit = 20;
var Devam = DevamLimit;
var SorguIslemde = false;
function ScrollKontrol() {
  var sctop = $(window).scrollTop();
  
  if (sctop > 400)
    $('.scrollTop').fadeIn('slow');
  else
    $('.scrollTop').fadeOut('slow');
  
  if (sctop > ($("body").height() - clientHeight()) - 300) {
    if (SorguIslemde == false) {
      SorguIslemde = true;
      $('.loader').fadeIn('fast');
      
      $.get("/ajax/devamiste.php?sort="+sort, "kategori=0&bas="+Devam, function(data){
        $('.loader').fadeOut('fast', function() {
          
          $(".loader").before(data);
          //itirafBind();

          Devam += DevamLimit;
          $("abbr.timeago").timeago();
          
          if (data != "<div class='itiraf son'></div>") {
            SorguIslemde = false;
          }
        });
      });
      
    }
  }
  
    
  setTimeout('ScrollKontrol()', 100);
}


/* ON LOAD */
/* ---------------------------------------------------*/
$(function() {
  setTimeout('ScrollKontrol()', 100);
  
  // form blur
  $('.form').focusout(function(){
    setTimeout('itirafFormKapat()', 500);
  });
  
  // time ago plugin
  $("abbr.timeago").timeago();
});

/* Yeni Mesaj Kontrol */
/* ---------------------------------------------------*/
var sonMesaj;
function yeniMesajKontrol() {
  $.get('ajax/yeniMesajKontrol.php', 'kategori=0&son='+sonMesaj, function(cevap){
    if (cevap) {
      $('.kategoriler').after(cevap);
      sonMesaj = $('.itiraf:first').data("id");

      $("abbr.timeago").timeago();
    }
  });
}

$(function() {
  $.get('ajax/sonmesaj.php', 'kategori=0', function(cevap) {
    sonMesaj = cevap;
    setInterval('yeniMesajKontrol()', 7000);
  });

  
});

// ...
function unixTimestamp() {
  return Math.round((new Date()).getTime() / 1000);
}

/* Diğer Butonları Gizle/Göster */
/* ---------------------------------------------------*/
function digerButonlariGoster(id) {
  $('#digerButonlar-'+id).slideToggle('fast');
}
function digerButonlariGosterYrm(id) {
  $('#digerButonlarYrm-'+id).slideToggle('fast');
}


/* Diger Btnlar - Mouse Out */
/* ---------------------------------------------------*/
$(function(){
  $('.itiraf').bind("mouseleave",function(){
    $(this).find(".digerButonlar").slideUp('fast');
  });
});

/* İtiraf Like */
/* ---------------------------------------------------*/
var LikeInPrg = false;
var sonLikeZaman = unixTimestamp();
function Like(id, skipPrg) {
  if (!skipPrg && LikeInPrg) return;

  var curVal = parseInt($('#likeCount-'+id).html());
  $('#likeCount-'+id).html("...")

  // flood engelle
  if ((unixTimestamp() - sonLikeZaman) < 3) {
    LikeInPrg = true;
    setTimeout("Like("+id+", true)", 2000);
    return;
  }

  LikeInPrg = true;
  
  $.post("/ajax/like.php", "id="+id, function(cevap) {
    LikeInPrg = false;
    sonLikeZaman = unixTimestamp();

    if (isNaN(cevap)) {
      $('#likeCount-'+id).html(curVal);
      alert(cevap);
    }else{
      $('#likeCount-'+id).html(cevap);
    }
  });
}

/* Mesaj Sil */
/* ---------------------------------------------------*/
var silInPrg = false;
function MesajSil(id){
  if (silInPrg) return;
  silInPrg = true;

  $.post("/ajax/mesajsil.php", "id="+id, function(cevap) {
    silInPrg = false;
    
    if (cevap != 'ok') {
      alert(cevap);
    }else{
      $("#itiraf-"+id).slideUp("fast", function(){
        suankiPos.removeClass('secili');
        suankiPos = suankiPos.next();
        suankiPos.addClass('secili');
        $(this).remove();
      });
    }
  });
}

/* Mesaj Sil ve Ban */
/* ---------------------------------------------------*/
function MesajSilveBan(id, uye){
  if (confirm("Bu üyeyi banlamak istediğinize emin misiniz?")) {
    MesajSil(id);
    
    $.post("/ajax/banla.php", "id="+uye, function(cevap) {
      if (cevap != 'ok') {
        alert(cevap);
      }
    });
  }
}

/* Yorum Sil */
/* ---------------------------------------------------*/
var silInPrg = false;
function YorumSil(id){
  if (silInPrg) return;
  silInPrg = true;

  $.post("/ajax/yorumsil.php", "id="+id, function(cevap) {
    silInPrg = false;
    
    if (cevap != 'ok') {
      alert(cevap);
    }else{
      $("#yorum-"+id).slideUp("fast");
    }
  });
}

/* Yorum Sil ve Ban */
/* ---------------------------------------------------*/
function YorumSilveBan(id, uye){
  if (confirm("Bu üyeyi banlamak istediğinize emin misiniz?")) {
    YorumSil(id);
    
    $.post("/ajax/banla.php", "id="+uye, function(cevap) {
      if (cevap != 'ok') {
        alert(cevap);
      }
    });
  }
}

/* İtiraf Düzene */
/* ---------------------------------------------------*/
function itirafDuzenle(id) {
  digerButonlariGoster(id); // yonetim menusunu kapat

  var itiraf = $('#itiraf-'+id);
  var mesaj = itiraf.children(".mesaj");
  var mesajicerik = mesaj.children(".mesajicerik").data('mesaj');

  // duzenleme bolumunu hazirla
  var input = "<div class='itirafduzenle' style='display:none' id='itirafduzenle-"+id+"'>";
  input += "<form id='itirafduzenleform-"+id+"'><input type='hidden' name='id' value='"+id+"'/>";
  input += "<p><textarea name='yenimesaj'>"+mesajicerik+"</textarea></p>";
  input += "<p class='btnlar'><input type='button' class='coolbtn' onclick='itirafDuzenleKaydet("+id+")' value='Kaydet'/>";
  input += "<input type='button' class='coolbtn' onclick='itirafDuzenleIptal("+id+")' value='İptal'/></p>";
  input += "</form></div>";

  // sayfaya ekle
  itiraf.prepend(input);
  $('#itirafduzenle-'+id).slideDown('fast');

  //mesaj.hide();
  mesaj.slideUp('fast');
}

/* İtiraf Düzene İptal */
/* ---------------------------------------------------*/
function itirafDuzenleIptal(id) {
  $('#itirafduzenle-'+id).slideUp('fast', function(){ $(this).remove() });
  $('#itiraf-'+id).children(".mesaj").slideDown('fast');
}

/* İtiraf Düzene Kaydet */
var duzenleKaydetInPrg = false;
/* ---------------------------------------------------*/
function itirafDuzenleKaydet(id) {
  if (duzenleKaydetInPrg) return;
  duzenleKaydetInPrg = true;
  
  var itiraf = $('#itiraf-'+id);

  $.post('/ajax/itirafDuzenle.php', $('#itirafduzenleform-'+id).serialize(), function(cevap) {
    duzenleKaydetInPrg = false;
    if (cevap.substr(0, 5) == 'Hata:') {
      alert(cevap);
    }else{
      itiraf.after(cevap);
      itiraf.attr('id', '#itirafSil-'+id);
      var itirafYeni = $('#itiraf-'+id);
      itirafYeni.hide();
      itiraf.slideUp('fast', function(){ $(this).remove() });
      itirafYeni.slideDown('fast');
      $("abbr.timeago").timeago();
    }
  });
}

/* Yorumları Gizle/Göster */
/* ---------------------------------------------------*/
var SwitchState = [];
function tumYorumlariKapat(except) {
  for(var i in SwitchState) {
    if (SwitchState[i] == true && i != except) {
      YorumFormGoster(i);
    }
  }
}

function YorumSwitch(id, hizli) {
  if (!SwitchState[id]) SwitchState[id] = true; else SwitchState[id] = false;
  
  var speed;
  if (hizli) speed = 0; else speed = 150; 

  if (SwitchState[id]) { // yorumlari ac
  
    tumYorumlariKapat(id);
    
    $('#gizliyorum-'+id).slideDown(speed, function(){
      if (!hizli) {
        // ekrani yorumlara kaydir
        var yorumPos = $('#gizliyorum-'+id).offset();
        $("html,body").animate({ scrollTop: yorumPos.top - 150 }, 100);
      }

      // yorum kutusuna focus yap
      //$('#yorummsj-'+id).focus();
    });
    
    //$('#switchstate-'+id).html("u tekrar gizlemek için tıklayın");
    
  }else{ // yorumlari kapa
    disableJK = false; // JK kisayollarini aktif et.
    $('#gizliyorum-'+id).slideUp(150);
    //$('#switchstate-'+id).html(" gizleniyor, görmek için tıklayın");
  }
}

function YorumFormGoster(id, hizli) {
  YorumSwitch(id, hizli);

  var speed;
  if (hizli) speed = 0; else speed = 150; 

  $('#yorumyaz-'+id).slideToggle(speed, function(){
    //$('#yorummsj-'+id).focus();
  });
}


/* j ve k kısayolları */
/* ---------------------------------------------------*/
var suankiPos;
var disableJK = false;

function suankiPosDegis(obje) {
  suankiPos = obje;
  suankiPos.addClass('secili');

  var resimList = suankiPos.children('.resimList');
  if (resimList.length > 0) {
    var actualh = resimList[0].scrollHeight;
    resimList.animate({"height": actualh+"px"}, 100).children('.fader').hide();
  }
}

function sonrakiniSec() {
  if (suankiPos.data('id') == $('.itiraf:last').data('id')) return;
  suankiPos.removeClass('secili');
  suankiPosDegis(suankiPos.next('.itiraf'));
  var itirafPos = suankiPos.offset();
  //var speed = Math.abs(((itirafPos.top - 105) - $(window).scrollTop())*2);
  $("html,body").animate({ scrollTop: itirafPos.top - 105 }, 100);
}

function oncekiniSec(){
  if (suankiPos.data('id') == $('.itiraf:first').data('id')) return;
  suankiPos.removeClass('secili');
  suankiPosDegis(suankiPos.prev('.itiraf'));
  var itirafPos = suankiPos.offset();
  //var speed = Math.abs(((itirafPos.top - 105) - $(window).scrollTop())*2);
  $("html,body").animate({ scrollTop: itirafPos.top - 105 }, 100);
}

$(function(){
  suankiPos = $(".itiraf:first");
  //suankiPosDegis($(".itiraf:first"));

  $(document).keydown(function(event) {
    if (disableJK) return;
    
    // j tuşu
    if (event.keyCode == 74) {
      sonrakiniSec();
    }

    // k tuşu
    if (event.keyCode == 75) {
      oncekiniSec();
    }

    // y tuşu
    if (event.keyCode == 89) {
      YorumFormGoster(suankiPos.data("id"));
    }

    // l tuşu
    if (event.keyCode == 76) {
      Like(suankiPos.data("id"));
    }

    
  });
});

/* sayfa yenilendiginde yeni gelen itiraflara bind etmek icin fonksyon */
/* ---------------------------------------------------*/
function itirafBind() {
  // enter tusu ile yorum gondermek icin
  $('.itiraf .yorummsj').live('keydown', function(event) {
    if (event.keyCode == 13) {
      var id = $(this).data('yrmid');
      yorumGonder(id);
      return false;
    }
  });

  // klavye kisayollari icin, tiklayinca secme
  $(".itiraf").live('click', function() {
    suankiPos.removeClass('secili');
    suankiPosDegis($(this));
  });


  $("INPUT[type='text'],TEXTAREA").live('focus', function() {
    disableJK = true;
  });
  $("INPUT[type='text'],TEXTAREA").live('blur', function() {
    disableJK = false;
  });
}

$(function(){
  itirafBind();
});


/* Birdan fazla ayni ID dekileri sondan temizle */
/* ---------------------------------------------------*/
function duplicateCheck() {
  var i;
  $('.itiraf[id]').each(function(){
    var ids = $('[id="'+this.id+'"]');
    if(ids.length>1 && ids[0]==this) {
      for(i=1; i<ids.length; i++) {
        $(ids[i]).remove();
      }
    }
  });
}

/* Abonelik */
/* ---------------------------------------------------*/
var aboneInPrg = false;
function Abonelik(id) {
  if (aboneInPrg) return;
  aboneInPrg = true;

  $.get("/ajax/abonelik.php", "id="+id, function(cevap) {
    aboneInPrg = false;
    alert(cevap);
  });
}

/* Bildir Butonu */
/* ---------------------------------------------------*/
$(function(){
  $('#bildir FORM INPUT[type=radio]').live('change', function() {
    if ($(this).val() == 'diger') {
      $('#bildir #digerneden').removeAttr('disabled');
    }else{
      $('#bildir #digerneden').attr('disabled', true);
    }
  });
});

function BildirForm(id) {
  $('#bildir').remove();

  $.get("/ajax/bildirform.php", function(data) {
    $('.content-container').before(data);

    $.fancybox({
      'href' : '#bildir',
      'type' : 'inline',
    });

    $('#bildirid').val(id);
  });
};

var bildirInPrg = false;
function BildirGonder(i) {
  if (bildirInPrg) return;
  bildirInPrg = true;
  $(i).val('Bekleyin...');

  $.post('/ajax/bildir.php', $('#bildirform').serialize(), function(cevap) {
    $(i).val('Gönder');
    bildirInPrg = false;

    if (cevap == 'ok') {
      alert("Şikayetiniz alındı. En kısa sürede mesaj silinecektir.");
      $.fancybox.close();
    }else
      alert(cevap);
  });
}
</script>
    
<script>
/* ITIRAF GONDERME FORMUNU KAPAT / GOSTER */
/* ---------------------------------------------------*/
var formState = false;
var formOver = false;

$(function(){
  $('#itirafgonder').mouseenter(function() {
    formOver = true;
  }).mouseleave(function() {
    formOver = false;
    setTimeout('itirafFormKapat()', 1000);
  });
});

function itirafFormKapat() {
  if ( $('#mesaj').val() == '' && !$("#nick").is(":focus") && !$("#mesaj").is(":focus") && formOver == false && uploaderAktif == false ) {
    $('.formgizle').slideUp('fast');
    $('#mesaj').addClass('collapsed');
    $('#mesaj').val('Mesajınızı yazın...');
    formState = false;
  }
}

function FormGoster() {
  if (formState) return;
  $('.formgizle').slideDown('fast');
  $('#mesaj').removeClass('collapsed');
  $('#mesaj').val('');
  formState = true;
}

/* ITIRAF GONDER */
/* ---------------------------------------------------*/
//$(function(){
//  $('#itirafgonder #gonder').click(function(){
//      var degerler = $("#itirafgonder").serialize();
//      
//      $.ajax({
//          type:     "post",
//          url:      "itirafgonder.php",
//          data:     degerler,
//          success:  function(cevap){
//              if(cevap.substr(0,6)=='Hata: '){
//                  alert(cevap);
//              }else{
//                  $('#itirafgonder #mesaj').val("");         
//                  $('.kategoriler').after(cevap);
//                  //$("abbr.timeago").timeago();
//              }
//              //$("#sonuc").html(cevap);
//              
//          }
//      });
//  });
//});

    
/* Yorum Gönder */
/* ---------------------------------------------------*/
var YorumGonderiliyor = false;

function yorumGonder(id) {
  if (YorumGonderiliyor) return;
  
  $('#yrmgonder-'+id).val("Bekleyin");
    YorumGonderiliyor = true;
  
  $.post("yorumgonder.php", $('FORM#yorumform-'+id).serialize(), function(cevap) {
    
    YorumGonderiliyor = false;
    $('#yrmgonder-'+id).val("Gönder");
    
    if (cevap.substr(0, 6) == 'Hata: ') {
      alert(cevap);
    }else{
      $('#yorummsj-'+id).val("").blur();
      
      $('#gizliyorum-'+id).append(cevap);
      //$("abbr.timeago").timeago();
    }
  });
}
    
    /* ITIRAF GONDER */
/* ---------------------------------------------------*/
$(function(){
  $('#itirafgonder #gonder').click(function(){
    //var resimliste = resimler.join(',');
    //$('.form INPUT#resimler').val(resimler);
    $.post("itirafgonder.php", $('FORM#itirafgonder').serialize(), function(cevap) {
      if (cevap.substr(0, 6) == 'Hata: ') {
        alert(cevap);
      }else{
        $('#itirafgonder #mesaj').val("");
        itirafFormKapat();
      //  resimler = [];
        //$('.form .resim').remove();

        $('.kategoriler').after(cevap);
    //    $("abbr.timeago").timeago();
        
        // slide
        var yeniMesaj = $('.itiraf:first');
        yeniMesaj.hide().slideDown('fast');
        sonMesaj = yeniMesaj.data("id");
      }
    });
  });
});

//      *--*-*-*-*-SCROLL MESELESİ*-*-*-*-*-*-*--*-*-*-*-*-
    $(function(){
       $.ekle = function(){
           var id = $('.itiraf:last').attr("id");
           $(".button").hide();
           $("#loader").show();
           
           $.ajax({
               type:    "post",
               url:     "scroll.php",
               data:    {"id":id},
               success: function(cevap){
                   $('.itiraflar').append(cevap);
                   $(".button").show();
                   $("#loader").hide();
               }
           });
       } 
    });
    
    
</script>
    
    <script type="text/javascript" src="includes/imgUpload/jquery.flash.js"></script>
    <script type="text/javascript" src="includes/imgUpload/jquery.jqUploader.js"></script>
    
    <script type="text/javascript" src="includes/highslide/highslide.js"></script>
    <link rel="stylesheet" type="text/css" href="includes/highslide/highslide.css" />
    
    <script type="text/javascript">
//<![CDATA[
hs.registerOverlay({
  html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
  position: 'top right',
  fade: 2 // fading the semi-transparent overlay looks bad in IE
});

hs.graphicsDir = 'includes/highslide/graphics/';
hs.wrapperClassName = 'borderless';
//]]>
</script>

    
    </head>
    
    <body>
    
<? 
        $cek = $_GET['nick']; 
        $row = mysql_query("SELECT * FROM user WHERE nick = '$cek'"); 
        $uye = mysql_fetch_assoc($row);
        $username = $_SESSION['nick'];

        $erkek = "Erkek";
        $kadin = "Kadın";
?>
        
        <div class="body-container">
        <div class="header">
            
            <div class="logo" align="center"><?php echo $logo;?> İtiraf</div>
            <div class="menu-container">
<?php
            if (isset($_SESSION["login"]) == false) {

    echo"<div class='menu'>  
    <a id='mn-index' href='index.php'>Anasayfa</a>
    <a id='mn-uyegiris' href='uyegiris.php'>Üye Girişi</a>
    <a id='mn-uyeol' href='uyeol.php'>Üye Ol</a>
    <a id='mn-sozlesme' href='sozlesme.php'>Sözleşme</a>
    <a id='mn-bizkimiz' href='bizkimiz.php'>İletişim</a>
    <div class='scrollTop'><a onclick='BasaDon()'>Başa Dön</a></div>
    </div>"; 

}else{
    
    echo "<div class='menu'>
    <a id='mn-index' href='index.php'>Anasayfa</a>
    <a id='mn-profil' href='profil.php'>Profil</a>
    <a id='mn-cikis' href='cikis.php'>Çıkış</a>
    <a id='mn-sozlesme' href='sozlesme.php'>Sözleşme</a>
    <a id='mn-bizkimiz' href='bizkimiz.php'>İletişim</a> 
    <div class='scrollTop'><a onclick='BasaDon()'>Başa Dön</a></div>
    </div>";
}
?>
    </div>
    </div>

            <div id='uploader'></div>

        
<div class="content-container">
    <div class="content">
        
        <div class="itiraflar">
        
            <div class="form uyeprofil">
                <?php if($uye["cinsiyet"]==1){?>
                <div class='profilresim'><a><img src='images/e2.png'/></a>
                    <div class='clear'></div>
                </div>
            <?php }else{ ?>
                <div class='profilresim'><a><img src='images/k2.png'/></a>
                    <div class='clear'></div>
                </div>
        <?php    } ?>
            
            <div class="profilkisa">
                <p><span class="nick"> <?=$uye['nick']?> </span></p>
                <p class='durumMsj'><?=$uye['durum']?></p>
            <div class="clear"></div>
            </div>
            
               <?php if($uye["cinsiyet"]==1){ ?>
            <div class="profildetay">
                <p> <b> Cinsiyet: </b> <?=$erkek?>     </p>
                <p> <b> Üyelik Tarihi: </b> <?=$uye['utarih']?>  </p>
                <div class="clear"></div>
            </div>
              <?php  }else{ ?>
                <div class="profildetay">
                <p> <b> Cinsiyet: </b> <?=$kadin?>     </p>
                <p> <b> Üyelik Tarihi: </b> <?=$uye['utarih']?>  </p>
                <div class="clear"></div>
            </div>
                <?php } ?>
            
            <div class="clear"></div>   
            </div>
            
            <div class="uyeitiraflari">
                <?php 
                $sonuc    = mysql_query("select * from itiraflar where gonderen='$cek' order by aktiftarih DESC");                
                $satir    = mysql_num_rows($sonuc);
                
                if($satir!=0)
                {
                    while($oku = mysql_fetch_array($sonuc))
                    {
                        $iid = $oku["id"];
                        $yorum    = mysql_query("select * from yorumlar as y inner join user as u where u.id=y.yazanID and y.itirafID='$iid'  ");
                        $satir2   = mysql_num_rows($yorum);
         
                ?>
                
                
            <div class='itiraf' id='<?php echo $iid; ?>' data-id=<?php echo $iid; ?>>
            
            <?php if($oku["cinsiyet"] == 1){?>
                <div class='profilresim'><a><img src='images/e.png'/></a>
                    <div class='clear'></div>
                </div>
            <?php }else{ ?>
                <div class='profilresim'><a><img src='images/k.png'/></a>
                    <div class='clear'></div>
                </div>
        <?php    } ?>
            
            <div class='mesaj' >
                <a href='http://www.ssuitiraf.com/uye.php?nick=<?php echo $oku["gonderen"]; ?>'><span class='gonderen'><?php echo $oku["gonderen"]; ?></span></a>
                <span class='mesajicerik'> <?php echo stripslashes($oku["mesaj"]); ?></span>
            </div> <!--    mesaj div kapama   -->
            
            <div class='info'>
                <abbr> <?php echo $oku["itarih"]; ?></abbr>               

            </div>
            
            <div class='butonlar'>
                <a class='YorumGosterbtn' onclick='YorumFormGoster("<?php echo $iid; ?>")' ><img src='images/comment_reply.png'/> <?php echo $satir2; ?> Yorum Yaz </a>  
<!--                <a onclick="" ><img style="" src="style/delete.png">Sil </a>-->

            </div> 
            
            <div class='yorumlar'>
                <div class='gizliyorum' id='gizliyorum-<?php echo $iid; ?>'> 
                    <?php while($oku2 = mysql_fetch_array($yorum)){  ?>
<!--                yorum divi - profil,mesaj,info    -->
                    <div class='yorum' id='yorum-<?php echo $oku2["id"];?>'>
                    <?php if($oku2["cinsiyet"] == 1){?>
                <div class='profilresim'><a><img src='images/e.png'/></a>
                    <div class='clear'></div>
                </div>
            <?php }else{ ?>
                <div class='profilresim'><a><img src='images/k.png'/></a>
                    <div class='clear'></div>
                </div>
        <?php    } ?>
                    
                    <div class='mesaj' >
                        <a href='http://www.ssuitiraf.com/uye.php?nick=<?php echo $oku2["yazan"]; ?>'><span class='gonderen'><?php echo $oku2["yazan"]; ?></span></a>
                        <?php echo stripslashes($oku2["yorum"]); ?>
                    </div> <!--    mesaj div kapama   -->
            
                    <div class='info'>
                        <abbr> <?php echo $oku2["ytarih"]; ?></abbr>               
                    </div>
                    </div>
<?php } ?>

                </div>
                
                <div id='yorumyaz-<?php echo $iid; ?>' class='yorumyaz' style="display:none">
                    <form id='yorumform-<?php echo $iid; ?>' >
                        <input type='hidden' name='itirafid' value='<?php echo $iid; ?>'/>
                        <div class='textarea'> <span class='yorumnick'> <?php echo $username; ?> </span>:
                        <input type='text' style="width:650px;" data-yrmid='<?php echo $iid; ?>' class='yorummsj' id='yorummsj-<?php echo $iid; ?>' placeholder="Yorumunuzu Yazınız..." name='yorummsj' autocomplete="off"/>
                        <input type='button' onclick='yorumGonder("<?php echo $iid; ?>")' class='yrmgonder' id='yrmgonder-<?php echo $iid; ?>' name='yrmgonder' value='Gönder'/>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- itirafdiv kapama -->
      
    <?php    } // itiraf while kapama ?>

                    <?php }else{
    echo "Hiç kayıt yok!";
}?>
                
            
            
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