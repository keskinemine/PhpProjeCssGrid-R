<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'frameworks/PHPMailer/src/Exception.php';
require 'frameworks/PHPMailer/src/PHPMailer.php';
require 'frameworks/PHPMailer/src/SMTP.php';

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

if(($gelenemailAdresi!="") or ($gelentelefon!="")){
    $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi = ? or telefonNumarasi = ?");
    $kontrolSorgusu->execute([$gelenemailAdresi, $gelentelefon]);
    $kullaniciSayisi         =   $kontrolSorgusu->rowCount();
    $kullaniciKaydi          =   $kontrolSorgusu->fetch(PDO::FETCH_ASSOC);

    if($kullaniciSayisi>0){
        $mailIcerigiHazirla   =     "Merhaba Sayın " . $kullaniciKaydi["isimSoyisim"] . ",<br><br>Sitemiz üzerinde bulunan hesabınızın şifresini sıfırlamak için lütfen <a href='" . $siteLinki . "/index.php?sk=43&aktivasyonKodu=" . $kullaniciKaydi["aktivasyonKodu"] . "&email=" . $kullaniciKaydi["emailAdresi"] . "'> BURAYA TIKLAYINIZ.</a> <br><br>Saygılarımızla, iyi çalışmalar...<br>" . $siteAdi;

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
            $mailGonder->addAddress(donusumleriGeriDondur($kullaniciKaydi["emailAdresi"]), donusumleriGeriDondur($kullaniciKaydi["isimSoyisim"]));
            $mailGonder->addReplyTo(donusumleriGeriDondur($siteEmailAdresi), donusumleriGeriDondur($siteAdi));                                           
            $mailGonder->isHTML(true);
            $mailGonder->Subject                =  donusumleriGeriDondur($siteAdi) . ' Şifre Sıfırlama';
            $mailGonder->MsgHTML($mailIcerigiHazirla);
            $mailGonder->send();

            header("Location:index.php?sk=39");
            exit();                
        }catch(Exception $e){
            header("Location:index.php?sk=40");
            exit();
        }   
    }else{
        header("Location:index.php?sk=41");
        exit();
    }
}else{
    header("Location:index.php?sk=42");
    exit();
}
?>