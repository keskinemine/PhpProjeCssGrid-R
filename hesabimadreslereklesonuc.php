<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_POST["isimSoyisim"])){
        $gelenisimSoyisim      =   guvenlik($_POST["isimSoyisim"]);
    }else{
        $gelenisimSoyisim      =    "";
    }
    if(isset($_POST["adres"])){
        $gelenadres            =   guvenlik($_POST["adres"]);
    }else{
        $gelenadres            =    "";
    }
    if(isset($_POST["ilce"])){
        $gelenilce            =   guvenlik($_POST["ilce"]);
    }else{
        $gelenilce           =    "";
    }
    if(isset($_POST["sehir"])){
        $gelensehir            =   guvenlik($_POST["sehir"]);
    }else{
        $gelensehir           =    "";
    }
    if(isset($_POST["telefon"])){
        $gelentelefon          =   guvenlik($_POST["telefon"]);
    }else{
        $gelentelefon          =    "";
    }

    if(($gelenisimSoyisim!="") and ($gelenadres!="") and ($gelenilce!="") and ($gelensehir!="") and ($gelentelefon!="")){
        $adresEklemeSorgusu       =   $baglanti->prepare("INSERT INTO adresler (uyeId, adiSoyadi, adres, ilce, sehir, telefon) VALUES (?, ?, ?, ?, ?, ?)");
        $adresEklemeSorgusu->execute([$kullaniciID, $gelenisimSoyisim, $gelenadres, $gelenilce, $gelensehir, $gelentelefon]);  
        $eklemeKontrol            =   $adresEklemeSorgusu->rowCount();

        if($eklemeKontrol>0){
            header("Location:index.php?sk=72"); 
            exit();
        }else{
            header("Location:index.php?sk=73"); 
            exit();
        }          
    }else{
        header("Location:index.php?sk=74"); 
        exit();
    }          
}else{
    header("Location:index.php");
    exit();
}
?>