<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if($gelenid!=""){

        $favoriKontrolSorgusu        =   $baglanti->prepare("SELECT * FROM favoriler WHERE urunId = ? AND uyeId = ? LIMIT 1");
        $favoriKontrolSorgusu->execute([$gelenid, $kullaniciID]);
        $favoriKontrolSayisi         =   $favoriKontrolSorgusu->rowCount();

        if($favoriKontrolSayisi>0){
            header("Location:index.php?sk=90");
            exit();
        }else{
            $favoriEklemeSorgusu        =   $baglanti->prepare("INSERT INTO favoriler (urunId, uyeId) VALUES (?, ?)");
            $favoriEklemeSorgusu->execute([$gelenid, $kullaniciID]);
            $favoriEklemeSayisi         =   $favoriEklemeSorgusu->rowCount();

            if($favoriEklemeSayisi>0){
                header("Location:index.php?sk=88");
                exit();
            }else{
                header("Location:index.php?sk=89");
                exit();
            }
        }
    }else{
        header("Location:index.php");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>