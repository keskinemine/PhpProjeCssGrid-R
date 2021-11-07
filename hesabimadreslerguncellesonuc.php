<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }
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

    if(($gelenid!="") and ($gelenisimSoyisim!="") and ($gelenadres!="") and ($gelenilce!="") and ($gelensehir!="") and ($gelentelefon!="")){
        $adresGuncellemeSorgusu       =   $baglanti->prepare("UPDATE adresler SET adiSoyadi = ?, adres = ?, ilce = ?, sehir = ?, telefon = ? WHERE id =  ? AND uyeId = ? LIMIT 1");
        $adresGuncellemeSorgusu->execute([$gelenisimSoyisim, $gelenadres, $gelenilce, $gelensehir, $gelentelefon, $gelenid, $kullaniciID]);  
        $guncellemeKontrol                =   $adresGuncellemeSorgusu->rowCount();

        if($guncellemeKontrol>0){
            header("Location:index.php?sk=64"); 
            exit();
        }else{
            header("Location:index.php?sk=65"); 
            exit();
        }        
    }else{
        header("Location:index.php?sk=66"); 
        exit();
    }          
}else{
    header("Location:index.php");
    exit();
}
?>