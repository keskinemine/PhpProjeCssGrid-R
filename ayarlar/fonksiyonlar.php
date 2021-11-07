<?php
$IPAdresi                   =    $_SERVER["REMOTE_ADDR"];
$zamanDamgasi               =    time();
$tarihSaat                  =    date("d.m.Y H:i:s", $zamanDamgasi);

function tarihBul($deger){
    $cevir                  =    date("d.m.Y", $deger);
    $sonuc                  =    $cevir;
    return $sonuc;
}

function ucGunIleriTarihBul(){
    global $zamanDamgasi;
    $birGun                 =    86400;
    $hesapla                =    $zamanDamgasi+(3*$birGun);
    $cevir                  =    date("d.m.Y", $hesapla);
    $sonuc                  =    $cevir;
    return $sonuc;
}

function rakamlarHaricTumKarakterleriSil($deger){
    $islem                  =    preg_replace("/[^0-9]/", "", $deger);
    $sonuc                  =    $islem;
    return $sonuc;
}

function tumBosluklariSil($deger){
    $islem                  =    preg_replace("/\s|&nbsp;/", "", $deger);
    $sonuc                  =    $islem;
    return $sonuc;
}

function donusumleriGeriDondur($deger){
    $geriDondur             =   htmlspecialchars_decode($deger, ENT_QUOTES);
    $sonuc                  =    $geriDondur;
    return $sonuc;
}

function guvenlik($deger){
    $boslukSil              =   trim($deger);
    $taglariTemizle         =   strip_tags($boslukSil);
    $etkisizYap             =   htmlspecialchars($taglariTemizle, ENT_QUOTES);
    $sonuc                  =   $etkisizYap;
    return $sonuc;
}

function sayiliIcerikleriFiltrele($deger){
    $boslukSil              =   trim($deger);
    $taglariTemizle         =   strip_tags($boslukSil);
    $etkisizYap             =   htmlspecialchars($taglariTemizle);
    $temizle                =   rakamlarHaricTumKarakterleriSil($etkisizYap, ENT_QUOTES);
    $sonuc                  =   $temizle;
    return $sonuc;
}

function IbanBicimlendir($deger){
    $boslukSil              =   trim($deger);
    $tumBoslukSil           =   tumBosluklariSil($boslukSil);
    $birinciBlok            =   substr($tumBoslukSil, 0, 4);
    $ikinciBlok             =   substr($tumBoslukSil, 4, 4);
    $ucuncuBlok             =   substr($tumBoslukSil, 8, 4);
    $dorduncuBlok           =   substr($tumBoslukSil, 12, 4);
    $besinciBlok            =   substr($tumBoslukSil, 16, 4);
    $altinciBlok            =   substr($tumBoslukSil, 20, 4);
    $yedinciBlok            =   substr($tumBoslukSil, 24, 2);
    $duzenle                =   $birinciBlok . " " . $ikinciBlok . " " . $ucuncuBlok . " " . $dorduncuBlok . " " . $besinciBlok . " " . $altinciBlok . " " . $yedinciBlok;
    $sonuc                  =   $duzenle;
    return $sonuc;
}

function aktivasyonKoduUret(){
    $ilkBes                 =    rand(10000, 99999);
    $ikinciBes              =    rand(10000, 99999);
    $ucuncuBes              =    rand(10000, 99999);
    $dorduncuBes            =    rand(10000, 99999);
    $kod                    =    $ilkBes . "-" . $ikinciBes . "-" . $ucuncuBes . "-" . $dorduncuBes;
    $sonuc                  =    $kod;
    return  $sonuc;
}

function fiyatBicimlendir($deger){
    $bicimlendir            =   number_format($deger, "2", ",", ".");
    $sonuc                  =   $bicimlendir;
    return $sonuc;
}
?>