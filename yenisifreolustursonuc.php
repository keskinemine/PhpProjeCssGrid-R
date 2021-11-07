<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'frameworks/PHPMailer/src/Exception.php';
require 'frameworks/PHPMailer/src/PHPMailer.php';
require 'frameworks/PHPMailer/src/SMTP.php';

if(isset($_GET["emailAdresi"])){
    $gelenemailAdresi           =   guvenlik($_GET["emailAdresi"]);
}else{
    $gelenemailAdresi           =    "";
}
if(isset($_GET["aktivasyonKodu"])){
    $gelenaktivasyonKodu           =   guvenlik($_GET["aktivasyonKodu"]);
}else{
    $gelenaktivasyonKodu          =    "";
}
if(isset($_POST["sifre"])){
    $gelensifre      =   guvenlik($_POST["sifre"]);
}else{
    $gelensifre     =    "";
}
if(isset($_POST["sifreTekrar"])){
    $gelensifreTekrar      =   guvenlik($_POST["sifreTekrar"]);
}else{
    $gelensifreTekrar      =    "";
}

$md5liSifre                =    md5($gelensifre);

if(($gelenemailAdresi!="") and ($gelenaktivasyonKodu!="") and ($gelensifre!="") and ($gelensifreTekrar!="")){
    if($gelensifre!=$gelensifreTekrar){
        header("Location:index.php?sk=47");
        exit();
    }else{
        $uyeGuncellemeSorgusu   =   $baglanti->prepare("UPDATE uyeler SET sifre = ? WHERE emailAdresi = ? AND aktivasyonKodu = ? LIMIT 1");
        $uyeGuncellemeSorgusu->execute([$md5liSifre, $gelenemailAdresi, $gelenaktivasyonKodu]);
        $kontrol                =   $uyeGuncellemeSorgusu->rowCount();

        if($kontrol>0){
            header("Location:index.php?sk=45");
            exit();        
        }else{
            header("Location:index.php?sk=46");
            exit();
        }
    }
}else{
    header("Location:index.php?sk=48");
    exit();
}
?>