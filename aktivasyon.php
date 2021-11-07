<?php
require_once("ayarlar/ayar.php");
require_once("ayarlar/fonksiyonlar.php");

if(isset($_GET["aktivasyonKodu"])){
    $gelenaktivasyonKodu           =   guvenlik($_GET["aktivasyonKodu"]);
}else{
    $gelenaktivasyonKodu            =    "";
}
if(isset($_GET["email"])){
    $gelenemail      =   guvenlik($_GET["email"]);
}else{
    $gelenemail      =    "";
}

if(($gelenaktivasyonKodu!="") and ($gelenemail!="")){
    $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi = ? and aktivasyonKodu = ? and durumu = ?");
    $kontrolSorgusu->execute([$gelenemail, $gelenaktivasyonKodu, 0]);
    $kullaniciSayisi         =   $kontrolSorgusu->rowCount();

    if($kullaniciSayisi>0){
        $uyeGuncellemeSorgusu       =   $baglanti->prepare("UPDATE uyeler SET durumu = 1");
        $uyeGuncellemeSorgusu->execute();
        $kontrol               =   $uyeGuncellemeSorgusu->rowCount();

        if($kontrol>0){
            header("Location:index.php?sk=30");
            exit();        
        }else{
            header("Location:" . $siteLinki);
            exit();
        }
    }else{
        header("Location:" . $siteLinki);
        exit();
    } 
}else{
    header("Location:" . $siteLinki);
    exit();
}

$baglanti   =   null;
?>