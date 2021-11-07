<?php
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

if(isset($_POST["bankaSecimi"])){
    $gelenbankaSecimi      =   guvenlik($_POST["bankaSecimi"]);
}else{
    $gelenbankaSecimi      =    "";
}

if(isset($_POST["aciklama"])){
    $gelenaciklama          =   guvenlik($_POST["aciklama"]);
}else{
    $gelenaciklama          =    "";
}

if(($gelenisimSoyisim!="") and ($gelenemailAdresi!="") and ($gelentelefon!="") and ($gelenbankaSecimi!="")){
    $havaleBildirimiKaydet          =   $baglanti->prepare("INSERT INTO havalebildirimleri (bankaId, adiSoyadi, emailAdresi, telefonNumarasi, aciklama, islemTarihi, durum) values (?, ?, ?, ?, ?, ?, ?) ");
    $havaleBildirimiKaydet->execute([$gelenbankaSecimi, $gelenisimSoyisim, $gelenemailAdresi, $gelentelefon, $gelenaciklama, $zamanDamgasi, 0]);
    $havaleBildirimiKaydetKontrol   =   $havaleBildirimiKaydet->rowCount();

    if($havaleBildirimiKaydetKontrol>0){
        header("Location:index.php?sk=11");
        exit();
    }else{
        header("Location:index.php?sk=12");
        exit();
    }
}else{
    header("Location:index.php?sk=13");
    exit();
}
?>