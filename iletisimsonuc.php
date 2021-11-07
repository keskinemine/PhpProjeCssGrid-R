<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'frameworks/PHPMailer/src/Exception.php';
require 'frameworks/PHPMailer/src/PHPMailer.php';
require 'frameworks/PHPMailer/src/SMTP.php';

if(isset($_POST["isimSoyisim"])){
    $gelenisimSoyisim      =   guvenlik($_POST["isimSoyisim"]);
}else{
    $gelenisimSoyisim      =    "";
}
if(isset($_POST["emailAdresi"])){
    $gelenemailAdresi      =   guvenlik($_POST["emailAdresi"]);
}else{
    $gelenemailAdresi      =    "";
}
if(isset($_POST["telefon"])){
    $gelentelefon          =   guvenlik($_POST["telefon"]);
}else{
    $gelentelefon          =    "";
}
if(isset($_POST["mesaj"])){
    $gelenmesaj            =   guvenlik($_POST["mesaj"]);
}else{
    $gelenmesaj            =    "";
}

if(($gelenisimSoyisim!="") and ($gelenemailAdresi!="") and ($gelentelefon!="") and ($gelenmesaj!="")){
    $mailIcerigiHazirla    =   "İsim Soyisim: " . $gelenisimSoyisim . "<br>E-mail Adresi: " . $gelenemailAdresi . "<br>Telefon Numarası: " . $gelentelefon . "<br>Mesaj: " . $gelenmesaj;

    $mailGonder    = new PHPMailer(true);

    try {
        $mailGonder->SMTPDebug              =   0;
        $mailGonder->isSMTP(); 
        $mailGonder->Host                   =   donusumleriGeriDondur($siteEmailHostAdresi);
        $mailGonder->SMTPAuth               =   true;
        $mailGonder->CharSet                =   "UTF-8";
        $mailGonder->Username               =   donusumleriGeriDondur($siteEmailAdresi);
        $mailGonder->Password               =   donusumleriGeriDondur($siteEmailSifresi);
        $mailGonder->SMTPSecure             =   'tls';
        $mailGonder->Port                   =   587;
        $mailGonder->SMTPOptions            =   array(
                                                    'ssl' => array(
                                                        'verify_peer' => false,
                                                        'verify_peer_name' => false,
                                                        'allow_self_signed' => true
                                                    )
                                                );
        $mailGonder->setFrom(donusumleriGeriDondur($siteEmailAdresi), donusumleriGeriDondur($siteAdi));
        $mailGonder->addAddress(donusumleriGeriDondur($siteEmailAdresi), donusumleriGeriDondur($siteAdi));
        $mailGonder->addReplyTo(donusumleriGeriDondur($gelenemailAdresi), donusumleriGeriDondur($gelenisimSoyisim));
        $mailGonder->isHTML(true);
        $mailGonder->Subject =  donusumleriGeriDondur($siteAdi) . ' İletişim Formu Mesajı';
        $mailGonder->MsgHTML($mailIcerigiHazirla);
        $mailGonder->send();

        header("Location:index.php?sk=18");
        exit();
    }catch(Exception $e){
        header("Location:index.php?sk=19");
        exit();
    }
}else{
    header("Location:index.php?sk=20");
    exit();
}
?>