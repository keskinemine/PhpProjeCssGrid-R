<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


session_start(); ob_start();
require_once("ayarlar/ayar.php");
require_once("ayarlar/fonksiyonlar.php");
require_once("ayarlar/siteSayfalari.php");

if(isset($_REQUEST["sk"])){
    $sayfaKoduDegeri    =   sayiliIcerikleriFiltrele($_REQUEST["sk"]);
}else{
    $sayfaKoduDegeri    =   0;
}

if(isset($_REQUEST["syf"])){
    $sayfalama    =   sayiliIcerikleriFiltrele($_REQUEST["syf"]);
}else{
    $sayfalama    =   1;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="revisit-after" content="7 Days">
    <title><?php echo donusumleriGeriDondur($siteTitle); ?></title>
    <link type="image/png" rel="icon" href="resimler/Favicon.png">
    <meta name="description" content="<?php echo donusumleriGeriDondur($siteDescription); ?>">
    <meta name="keywords" content="<?php echo donusumleriGeriDondur($siteKeywords); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ayarlar/style.css">
    <link rel="stylesheet" media="screen and (max-width:530px)" href="ayarlar/mobilstyle.css">
    <link rel="stylesheet" media="screen and (max-width:992px) and (min-width:531px)" href="ayarlar/tablet.css">
    <script type="text/javascript" src="frameworks/jQuery/jquery-3.6.0.min.js" language="javascript"></script>
    <script type="text/javascript" src="ayarlar/fonksiyonlar.js" language="javascript"></script>
</head>
<body>
<div id="header">
    <div class="headerimg"><img src="resimler/HeaderMesajResmi.png" alt=""></div>
    <div class="uyeliknav">
        <ul>
        <?php
        if(isset($_SESSION["kullanici"])){
        ?>
            <li><a href="index.php?sk=50"><img src="resimler/KullaniciBeyaz16x16.png"></a></li>
            <li><a href="index.php?sk=50">Hesabım</a></li>
            <li><a href="index.php?sk=49"><img src="resimler/CikisBeyaz16x16.png"></a></li>
            <li><a href="index.php?sk=49">Çıkış Yap</a></li>
        <?php
        }else{
        ?>
            <li><a href="index.php?sk=31"><img src="resimler/KullaniciBeyaz16x16.png"></a></li>
            <li><a href="index.php?sk=31">Giriş Yap</a></li>
            <li><a href="index.php?sk=22"><img src="resimler/KullaniciEkleBeyaz16x16.png"></a></li>
            <li><a href="index.php?sk=22">Yeni Üye Ol</a></li>
        <?php
        }
        ?>
            <?php if(isset($_SESSION["kullanici"])){  ?> <li><a href="index.php?sk=94"><img src="resimler/SepetBeyaz16x16.png"></a></li> <?php }else{ ?> <li><a href="index.php?sk=31"><img src="resimler/SepetBeyaz16x16.png"></a></li> <?php } ?>            
            <?php if(isset($_SESSION["kullanici"])){  ?> <li><a href="index.php?sk=94">Alışveriş Sepeti</a></li> <?php }else{ ?> <li><a href="index.php?sk=31">Alışveriş Sepeti</a></li> <?php } ?>            
        </ul>
    </div>
    <div class="mainav">
        <a href="index.php"><img src="resimler/<?php echo donusumleriGeriDondur($siteLogosu); ?>"></a>
        <ul>
            <li><a href="index.php">Ana Sayfa</a></li>
            <li><a href="index.php?sk=84">Erkek Ayakkabıları</a></li>
            <li><a href="index.php?sk=85">Kadın Ayakkabıları</a></li>
            <li><a href="index.php?sk=86">Çocuk Ayakkabıları</a></li>
        </ul> 
    </div>
</div>
<div id="content"><?php
    if((!$sayfaKoduDegeri) or ($sayfaKoduDegeri=="") or ($sayfaKoduDegeri==0)){
        include($sayfa[0]);
    }else{
        include($sayfa[$sayfaKoduDegeri]);
    }
?>
</div>
<div id="footer">
    <div class="footercontainer">
        <div class="footerblog">
            <h4>Kurumsal</h4>
            <ul>
                <li><a href="index.php?sk=1">Hakkımızda</a></li>
                <li><a href="index.php?sk=8">Banka Hesaplarımız</a></li>
                <li><a href="index.php?sk=9">Havale Bildirim Formu</a></li>
                <li><a href="index.php?sk=14">Kargom Nerede?</a></li>
                <li><a href="index.php?sk=16">İletişim</a></li>
            </ul>
        </div>
        <div class="footerblog">
            <h4>Üyelik & Hizmetler</h4>
            <ul>
            <?php
            if(isset($_SESSION["kullanici"])){
            ?>
                <li><a href="index.php?sk=50">Hesabım</a></li>
            <?php
            }else{
            ?>
                <li><a href="index.php?sk=31">Giriş Yap</a></li>
            <?php
            }
            ?>
            <?php
            if(isset($_SESSION["kullanici"])){
            ?>
                <li><a href="index.php?sk=49">Çıkış Yap</a></li>
            <?php
            }else{
            ?>
                <li><a href="index.php?sk=22">Yeni Üye Ol</a></li>
            <?php
            }
            ?>  
                <li><a href="index.php?sk=21">Sık Sorulan Sorular</a></li>
            </ul>
        </div>
        <div class="footerblog">
            <h4>Sözleşmeler</h4>
            <ul>
                <li><a href="index.php?sk=2">Üyelik Sözleşmesi</a></li>
                <li><a href="index.php?sk=3">Kullanım Koşulları</a></li>
                <li><a href="index.php?sk=4">Gizlilik Sözleşmesi</a></li>
                <li><a href="index.php?sk=5">Mesafeli Satış Sözleşmesi</a></li>
                <li><a href="index.php?sk=6">Teslimat</a></li>
                <li><a href="index.php?sk=7">İptal & İade & Değişim</a></li>
            </ul>
        </div>
        <div class="footerblog">
            <h4>Bizi Takip Edin</h4>
            <ul class="social">
                <li><a href="<?php echo donusumleriGeriDondur($sosyalLinkInstagram); ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i>Instagram</a></li>
                <li><a href="<?php echo donusumleriGeriDondur($sosyalLinkFacebook); ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i>Facebook</a></li>
                <li><a href="<?php echo donusumleriGeriDondur($sosyalLinkYoutube); ?>" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i>Youtube</a></li>
                <li><a href="<?php echo donusumleriGeriDondur($sosyalLinkTwitter); ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i>Twitter</a></li>
                <li><a href="<?php echo donusumleriGeriDondur($sosyalLinkLinkedin); ?>" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i>LinkedIn</a></li>
                <li><a href="<?php echo donusumleriGeriDondur($sosyalLinkPinterest); ?>" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i>Pinterest</a></li>
            </ul>
        </div>
    </div>
    <div class="copyright"><?php echo donusumleriGeriDondur($siteCopyrightMetni); ?></div>
    <div class="footerbankalar"><img src="resimler/RapidSSL32x12.png"><img src="resimler/InternetteGuvenliAlisveris28x12.png"><img src="resimler/3DSecure14x12.png"><img src="resimler/BonusCard41x12.png"><img src="resimler/MaximumCard46x12.png"><img src="resimler/WorldCard48x12.png"><img src="resimler/CardFinans78x12.png"><img src="resimler/AxessCard46x12.png"><img src="resimler/OdemeSecimiParafCard.png"><img src="resimler/VisaCard37x12.png"><img src="resimler/MasterCard21x12.png"><img src="resimler/AmericanExpiress20x12.png">
    </div>
</div>
</body>
</html>

<?php
$baglanti   =   null;
ob_end_flush();
?>