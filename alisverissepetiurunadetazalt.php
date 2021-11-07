<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if($gelenid!=""){
        $sepetGuncellemeSorgusu     =   $baglanti->prepare("UPDATE sepet SET urunAdedi=urunAdedi-1 WHERE id = ? AND uyeId = ? LIMIT 1");
        $sepetGuncellemeSorgusu->execute([$gelenid, $kullaniciID]);
        $sepetGuncellemeSayisi           =   $sepetGuncellemeSorgusu->rowCount();

        if($sepetGuncellemeSayisi>0){
            header("Location:index.php?sk=94");
            exit();
        }else{
            header("Location:index.php?sk=94");
            exit();
        }
    }else{
        header("Location:index.php?sk=94");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>