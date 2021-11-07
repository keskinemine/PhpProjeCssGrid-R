<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if($gelenid!=""){
        $favoriSilmeSorgusu       =   $baglanti->prepare("DELETE FROM favoriler WHERE id =  ? AND uyeId = ? LIMIT 1");
        $favoriSilmeSorgusu->execute([$gelenid, $kullaniciID]);
        $favoriSilmeSayisi         =   $favoriSilmeSorgusu->rowCount();

        if($favoriSilmeSayisi>0){
            header("Location:index.php?sk=59");
            exit();
        }else{
            header("Location:index.php?sk=81");
            exit();
        }
    }else{
        header("Location:index.php?sk=81");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>