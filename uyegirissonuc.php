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
if(isset($_POST["sifre"])){
    $gelensifre            =   guvenlik($_POST["sifre"]);
}else{
    $gelensifre            =    "";
}

$md5liSifre                =    md5($gelensifre);

if(($gelenemailAdresi!="") and ($gelensifre!="")){
    $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi = ? and sifre = ?");
    $kontrolSorgusu->execute([$gelenemailAdresi, $md5liSifre]);
    $kullaniciSayisi         =   $kontrolSorgusu->rowCount();
    $kullaniciKaydi          =   $kontrolSorgusu->fetch(PDO::FETCH_ASSOC);

    if($kullaniciSayisi>0){
        if($kullaniciKaydi["durumu"]==1){
            $_SESSION["kullanici"]  =   $gelenemailAdresi;

            if($_SESSION["kullanici"]==$gelenemailAdresi){
                header("Location:index.php?sk=50");
                exit();
            }else{
                header("Location:index.php?sk=33");
                exit();
            }
        }else{
            $mailIcerigiHazirla   =     "Merhaba Sayın " . $kullaniciKaydi["isimSoyisim"] . ",<br><br>Sitemize yapmış olduğunuz üyelik kaydını tamamlamak için lütfen <a href='" . $siteLinki . "/aktivasyon.php?aktivasyonKodu=" . $kullaniciKaydi["aktivasyonKodu"] . "&email=" . $kullaniciKaydi["emailAdresi"] . "'> BURAYA TIKLAYINIZ.</a> <br><br>Saygılarımızla, iyi çalışmalar...<br>" . $siteAdi;

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
                $mailGonder->Subject                =  donusumleriGeriDondur($siteAdi) . ' Yeni Üyelik Aktivasyonu';
                $mailGonder->MsgHTML($mailIcerigiHazirla);
                $mailGonder->send();

                header("Location:index.php?sk=36");
                exit();                
            }catch(Exception $e){
                header("Location:index.php?sk=33");
                exit();
            }
        }
    }else{
        header("Location:index.php?sk=34");
        exit();
    }
}else{
    header("Location:index.php?sk=35");
    exit();
}
?>