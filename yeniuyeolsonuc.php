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
if(isset($_POST["sifreTekrar"])){
    $gelensifreTekrar      =   guvenlik($_POST["sifreTekrar"]);
}else{
    $gelensifreTekrar      =    "";
}
if(isset($_POST["isimSoyisim"])){
    $gelenisimSoyisim      =   guvenlik($_POST["isimSoyisim"]);
}else{
    $gelenisimSoyisim      =    "";
}
if(isset($_POST["telefon"])){
    $gelentelefon          =   guvenlik($_POST["telefon"]);
}else{
    $gelentelefon          =    "";
}
if(isset($_POST["cinsiyet"])){
    $gelencinsiyet         =   guvenlik($_POST["cinsiyet"]);
}else{
    $gelencinsiyet         =    "";
}
if(isset($_POST["sozlesmeOnay"])){
    $gelensozlesmeOnay     =   guvenlik($_POST["sozlesmeOnay"]);
}else{
    $gelensozlesmeOnay     =    "";
}

$aktivasyonKodu            =    aktivasyonKoduUret();
$md5liSifre                =    md5($gelensifre);

if(($gelenemailAdresi!="") and ($gelensifre!="") and ($gelensifreTekrar!="") and ($gelenisimSoyisim!="") and ($gelentelefon!="")and ($gelencinsiyet!="")){
    if(!isset($_POST['sozlesmeOnay'])){
        header("Location:index.php?sk=29");
        exit();
    }else{
        if($gelensifre!=$gelensifreTekrar){
            header("Location:index.php?sk=28");
            exit();
        }else{
            $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi = ?");
            $kontrolSorgusu->execute([$gelenemailAdresi]);
            $kullaniciSayisi         =   $kontrolSorgusu->rowCount();

            if($kullaniciSayisi>0){
                header("Location:index.php?sk=27");
                exit();
            }else{
                $uyeEklemeSorgusu       =   $baglanti->prepare("INSERT INTO uyeler (emailAdresi,sifre,isimSoyisim,telefonNumarasi,cinsiyet,durumu,kayitTarihi,kayitIPAdresi,aktivasyonKodu) values (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $uyeEklemeSorgusu->execute([$gelenemailAdresi, $md5liSifre, $gelenisimSoyisim, $gelentelefon, $gelencinsiyet,0 , $zamanDamgasi, $IPAdresi, $aktivasyonKodu ]);
                $kayitKontrol           =   $uyeEklemeSorgusu->rowCount();

                if($kayitKontrol>0){

                    $mailIcerigiHazirla   =     "Merhaba Sayın " . $gelenisimSoyisim . ",<br><br>Sitemize yapmış olduğunuz üyelik kaydını tamamlamak için lütfen <a href='" . $siteLinki . "/aktivasyon.php?aktivasyonKodu=" . $aktivasyonKodu . "&email=" . $gelenemailAdresi . "'> BURAYA TIKLAYINIZ.</a> <br><br>Saygılarımızla, iyi çalışmalar...<br>" . $siteAdi;

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
                        $mailGonder->addAddress(donusumleriGeriDondur($gelenemailAdresi), donusumleriGeriDondur($gelenisimSoyisim));
                        $mailGonder->addReplyTo(donusumleriGeriDondur($siteEmailAdresi), donusumleriGeriDondur($siteAdi));                                           
                        $mailGonder->isHTML(true);
                        $mailGonder->Subject                =  donusumleriGeriDondur($siteAdi) . ' Yeni Üyelik Aktivasyonu';
                        $mailGonder->MsgHTML($mailIcerigiHazirla);
                        $mailGonder->send();

                        header("Location:index.php?sk=24");
                        exit();                
                    }catch(Exception $e){
                        header("Location:index.php?sk=25");
                        exit();
                    }
                }else{
                    header("Location:index.php?sk=25");
                    exit();
                }
            }
        }
    }
}else{
    header("Location:index.php?sk=26");
    exit();
}
?>