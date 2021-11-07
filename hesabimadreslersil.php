<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if($gelenid!=""){
        $adresSilmeSorgusu          =   $baglanti->prepare("DELETE FROM adresler WHERE id =  ? LIMIT 1");
        $adresSilmeSorgusu->execute([$gelenid]);
        $adresSilmeSayisi         =   $adresSilmeSorgusu->rowCount();

        if($adresSilmeSayisi>0){
            header("Location:index.php?sk=68");
            exit();
        }else{
            header("Location:index.php?sk=69");
            exit();
        }
    }else{
        header("Location:index.php?sk=69");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>