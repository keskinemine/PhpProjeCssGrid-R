<?php
if(isset($_SESSION["kullanici"])){
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

    $md5liSifre                =    md5($gelensifre);

    if(($gelenemailAdresi!="") and ($gelensifre!="") and ($gelensifreTekrar!="") and ($gelenisimSoyisim!="") and ($gelentelefon!="")and ($gelencinsiyet!="")){
        if($gelensifre!=$gelensifreTekrar){
            header("Location:index.php?sk=57"); 
            exit();
        }else{
            if($gelensifre == "eskiSifre"){
                $sifreDegistirmeDurumu      =   0;
            }else{
                $sifreDegistirmeDurumu      =   1;
            }

            if($emailAdresi != $gelenemailAdresi){
                $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi = ?");
                $kontrolSorgusu->execute([$gelenemailAdresi]);
                $kullaniciSayisi         =   $kontrolSorgusu->rowCount();

                if($kullaniciSayisi>0){
                    header("Location:index.php?sk=55");  
                    exit();
                }
            }

            if($sifreDegistirmeDurumu == 1){
                $kullaniciGuncellemeSorgusu       =   $baglanti->prepare("UPDATE uyeler SET emailAdresi = ?, sifre = ?, isimSoyisim = ?, telefonNumarasi = ?, cinsiyet = ? WHERE id = ? LIMIT 1");
                $kullaniciGuncellemeSorgusu->execute([$gelenemailAdresi, $md5liSifre, $gelenisimSoyisim, $gelentelefon, $gelencinsiyet, $kullaniciID]);               
            }else{
                $kullaniciGuncellemeSorgusu       =   $baglanti->prepare("UPDATE uyeler SET emailAdresi = ?, isimSoyisim = ?, telefonNumarasi = ?, cinsiyet = ? WHERE id = ? LIMIT 1");
                $kullaniciGuncellemeSorgusu->execute([$gelenemailAdresi, $gelenisimSoyisim, $gelentelefon, $gelencinsiyet, $kullaniciID]);
            }

            $kayitKontrol           =   $kullaniciGuncellemeSorgusu->rowCount();

            if($kayitKontrol>0){  
                $_SESSION["kullanici"]  =     $gelenemailAdresi;
                
                header("Location:index.php?sk=53"); 
                exit();                
            }else{
                header("Location:index.php?sk=54"); 
                exit();
            }  
        }   
    }else{
        header("Location:index.php?sk=56"); 
        exit();
    }          
}else{
    header("Location:index.php");
    exit();
}
?>