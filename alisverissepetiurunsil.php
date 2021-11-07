<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if($gelenid!=""){
        $sepetSilSorgusu          =   $baglanti->prepare("DELETE FROM sepet WHERE id =  ? AND uyeId = ? LIMIT 1");
        $sepetSilSorgusu->execute([$gelenid, $kullaniciID]);
        $sepetSilmeSayisi         =   $sepetSilSorgusu->rowCount();

        if($sepetSilmeSayisi>0){
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