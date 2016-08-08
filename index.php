<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Istanbul');

if (isset($_SESSION["login"])) {
include("ayar.php");
$sql = ("select * from user");
//    $sql2=mysql_fetch_array($sql);
    $username = $_SESSION["nick"];
    $sessionID = $_SESSION["id"];
}

include ("logo.php");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!--
*******************************************

      İtiraf v1.3 (07.10.2015)
          
              İtiraf Script
           www.iuhfitiraf.com
          iuhfitiraf@gmail.com

*******************************************
-->
    <!--
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/sections-frmwrk-styles.css" rel="stylesheet">
    <link href="http://maxcdn.bootstrapcdn.com/bootswatch/3.3.0/cosmo/bootstrap.min.css" rel="stylesheet">
    -->
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="style/reset.css" />
    <link rel="stylesheet" type="text/css" href="style/main.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/mobile.css" rel="stylesheet" media="(max-width: 768px)">
<!--    <link rel=stylesheet type=text/css href=mesaj.css>-->
    <script type="text/javascript" src="style/jquery-1.6.4.min.js"></script>
    
    <script type="text/javascript" src="style/jquery.timeago.js"></script>
    <script type="text/javascript" src="style/rgbcolor.js"></script>
    
    <link rel="stylesheet" media="screen" type="text/css" href="style/colorpicker/colorpicker.css" />
    <script type="text/javascript" src="style/colorpicker/colorpicker.js"></script>
    
    <script type="text/javascript" src="style/fancybox/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="style/fancybox/jquery.fancybox.css" media="screen" />
    <!-- baştaki scriptler,js'ler gelecek(77-738arası)..kalsın..--> 
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
    var itg = false;
$(function(){
  $('#itirafgonder #gonder').click(function(){
    //var resimliste = resimler.join(',');
    //$('.form INPUT#resimler').val(resimler);
      
      $('#itirafgonder #gonder').val("Bekleyin");
    itg = true;
      
    $.post("itirafgonder.php", $('FORM#itirafgonder').serialize(), function(cevap) {
        
        itg = false;
        $('#itirafgonder #gonder').val("Gönder");
        
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
    
    <meta name="description" content="iuhfitiraf.com ya da iuhf itiraf ile içinizde kalmasın anlat anlatabilidiğin kadar.. Biz bizeyiz... çekinmeden, sıkılmadan içindekileri dök; itiraf et.. " />
    <meta name="keywords" content="iuhf, iühfitiraf, iühf itiraf, iuhfitiraf.com, iühfitiraf.com, istanbul hukuk, istanbul üniversitesi, iuhfitiraf, istanbul üniversitesi hukuk fakültesi, aksis, iuhf itiraf , istanbul hukuk itiraf, istanbul üniversitesi itiraf sitesi, www.iuhfitiraf.com" />
    <meta name="news_keywords" content="iuhf, iühfitiraf, iühf itiraf, iuhfitiraf.com, iühfitiraf.com, istanbul hukuk, istanbul üniversitesi, iuhfitiraf, istanbul üniversitesi hukuk fakültesi, aksis, iuhf itiraf , istanbul hukuk itiraf, istanbul üniversitesi itiraf sitesi, www.iuhfitiraf.com" />
    <meta name="author" content="İUHF İTİRAF, iuhf itiraf, İÜHF İTİRAF, iühf itiraf, iuhfitiraf, iühfitiraf" />
    <meta http-equiv="refresh" content="300" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Facebook Meta data -->
<meta property="og:title" content="iuhfitiraf.com - iuhf itiraf ">
<meta property="og:description" content="iuhfitiraf.com ya da iuhf itiraf ile içinizde kalmasın anlat anlatabilidiğin kadar.. Biz bizeyiz... çekinmeden, sıkılmadan içindekileri dök; itiraf et.. ">
<meta property='og:url' content='http://www.iuhfitiraf.com'>

    <!-- Google+ Meta Data -->
<meta itemprop="name" content="iuhfitiraf.com - iuhf itiraf">
<meta itemprop="description" content="iuhfitiraf.com ya da iuhf itiraf ile içinizde kalmasın anlat anlatabilidiğin kadar.. Biz bizeyiz... çekinmeden, sıkılmadan içindekileri dök; itiraf et.. ">
    <!-- Twitter Card data -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@iuhfitirafcom">
<meta name="twitter:title" content="iuhfitiraf.com - iuhf itiraf">
<meta name="twitter:description" content="iuhfitiraf.com ya da iuhf itiraf ile içinizde kalmasın anlat anlatabilidiğin kadar.. Biz bizeyiz... çekinmeden, sıkılmadan içindekileri dök; itiraf et..">
<meta name="twitter:creator" content="">
<meta name="twitter:domain" content="iuhfitiraf.com">
<meta name="twitter:app:name:iphone" content="">
<meta name="twitter:app:name:ipad" content="">
<meta name="twitter:app:name:googleplay" content="">
<meta name="twitter:app:url:iphone" content="">
<meta name="twitter:app:url:ipad" content="">
<meta name="twitter:app:url:googleplay" content="">
<meta name="twitter:app:id:iphone" content="">
<meta name="twitter:app:id:ipad" content="">
<meta name="twitter:app:id:googleplay" content="">

    <title>İUHF İtiraf</title>
    <script type="text/javascript" src="style/jquery.animate-colors-min.js"></script>

    </head>
    <body>
        <div class='body-container'>
        <div class='header'>
            <div class='logo' align="center"><?php echo $logo;?> İtiraf</div>
            
            <div class='menu-container'>
            
                <?php
ob_start();
session_start();
include("ayar.php");
    $sql = ("select * from user");
//    $sql2=mysql_fetch_array($sql);
    $username = $_SESSION["nick"];
if (isset($_SESSION["login"]) == false) {
    ?>
    <div class='menu'>  
    <a id='mn-index' href='index.php'>Anasayfa</a>
    <a id='mn-uyegiris' href='uyegiris.php'>Üye Girişi</a>
    <a id='mn-uyeol' href='uyeol.php'>Üye Ol</a>
    <a id='mn-sozlesme' href='sozlesme.php'>Sözleşme</a>
    <a id='mn-bizkimiz' href='bizkimiz.php'>İletişim</a>
    <div class='scrollTop'><a onclick='BasaDon()'>Başa Dön</a></div>
    </div>
    <?php
}else{
    ?>
    <div class='menu'>
    <a id='mn-index' href='index.php'>Anasayfa</a>
    <a id='mn-profil' href='profil.php'>Profil</a>
    <a id='mn-cikis' href='cikis.php'>Çıkış</a>
    <a id='mn-sozlesme' href='sozlesme.php'>Sözleşme</a>
    <a id='mn-bizkimiz' href='bizkimiz.php'>İletişim</a>
    <div class='scrollTop'><a onclick='BasaDon()'>Başa Dön</a></div>
    </div>
                <?php
}
?>
            </div>
            
            
            </div>
            <script>$('#mn-index').addClass('selected');</script>
            <div id='uploader'></div>
            
            <div class="content-container">
                <div class="content">
                <div class='itiraflar'>
                    
                    <div class='form'> 
                        <form id='itirafgonder'>
                        <p><textarea autocomplete="off" onclick='FormGoster()' class='collapsed' name='mesaj' id='mesaj' >Mesajınızı yazın...</textarea> </p>
<!--
                        <p class='formgizle'><b>Kategori</b>
                        <label><input type='radio' name='kategori' value='1'  /> İtiraf</label> <label><input type='radio' name='kategori' value='2'  /> Üniversite Hakkında</label> <label><input type='radio' name='kategori' value='3'  /> Geyik</label> 
                            </p>
-->
                        <p class='formgizle'><br/><b>Dikkat!</b>İleti gönderdiğinizde <a href='sozlesme.php'><u>Kullanım Sözleşmesini </u></a>kabul etmiş sayılırsınız. </p>
                        <p class='formgizle'><input type='button' class='button' id='gonder' value='Gönder'/> </p>
                        </form>
                    </div>
                  
                    <div class='reklamAlan1'>
                       <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- iuhfitiraf -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9849653721691838"
     data-ad-slot="7133388109"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
                    </div>
<!--
                    <div class="reklamAlan2">
                        <p>
                        <li>Hata gördüğünüz takdirde bizimle İletişime geçiniz..</li> 
                        </p>
                    </div>
-->
<!--
                    <div class="reklamAlan3">
                   
                    </div>
-->
                    
                    
        <div class="kategoriler">            
<!--
                    <div class="baslik">Kategoriler</div>
            <div class="kategori-menu">
            <a href="index.php" class="selected">Tümü</a>
            <a href="index.php?k=1">İtiraf</a>
            <a href="index.php?k=2">Üniversite Hakkında</a>
            <a href="index.php?k=3">Geyik</a></div>
-->
        </div>
                    
                  
<?php
include("ayar.php");
$username = $_SESSION['nick'];
                    
$sonuc    = mysql_query("select * from itiraflar order by aktiftarih DESC limit 20");                
$satir    = mysql_num_rows($sonuc);

if($satir!=0)
{
    while($oku = mysql_fetch_array($sonuc))
    {
        $iid = $oku["id"];
        //echo "<div class='itiraflar'>";
        $yorum    = mysql_query("select * from yorumlar as y inner join user as u where u.id=y.yazanID and y.itirafID='$iid' order by y.ytarih ASC ");
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
                <a href='http://www.iuhfitiraf.com/uye.php?nick=<?php echo $oku["gonderen"]; ?>'><span class='gonderen'><?php echo $oku["gonderen"]; ?>:</span></a>
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
                        <a href='http://www.iuhfitiraf.com/uye.php?nick=<?php echo $oku2["yazan"]; ?>'><span class='gonderen'><?php echo $oku2["yazan"]; ?>:</span></a>
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
                    
            </div> <!--  İtiraflar Div Kapama --> 

                    <div id="ekle" style="text-align:center;">
                        <button type="submit" class="button" style="font-size: 13px; cursor:pointer;" onclick="$.ekle()">Daha Fazla Yükle</button>
                        <div id="loader" style="display:none;"><img src="style/loader.gif" alt=""></div>
                    </div>
                    
            </div> 
                    </div> 
    
                  
        </div>
    
<!--        </div>-->
    
<!--        </div>-->
  
        
    </body>
    
    <footer class="sections-bg">
		<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">		
						<p style="margin:0px auto 50px"><?php echo $logo;?> İtiraf </a> (c) 2016 </p><a href="http://twitter.com/iuhfitirafcom" target=new><img src="http://iuhfitiraf.com/images/twitter.png" border="0"></a>	
					</div>
		</div>
	</footer>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67066079-1', 'auto');
  ga('send', 'pageview');

</script>

 
</html>
    