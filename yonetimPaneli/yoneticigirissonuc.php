<?php
if(empty($_SESSION["yonetici"])){ 
    if(isset($_POST["ykullanici"])){
        $gelenykullanici      =   guvenlik($_POST["ykullanici"]);
    }else{
        $gelenykullanici      =    "";
    }
    if(isset($_POST["ysifre"])){
        $gelenysifre           =   guvenlik($_POST["ysifre"]);
    }else{
        $gelenysifre           =    "";
    }

    $md5liSifre                =    md5($gelenysifre);

    if(($gelenykullanici!="") and ($gelenysifre!="")){
        $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM yoneticiler WHERE kullaniciAdi = ? and sifre = ?");
        $kontrolSorgusu->execute([$gelenykullanici, $gelenysifre]);
        $kullaniciSayisi         =   $kontrolSorgusu->rowCount();
        $kullaniciKaydi          =   $kontrolSorgusu->fetch(PDO::FETCH_ASSOC);

        if($kullaniciSayisi>0){
            $_SESSION["yonetici"]       =   $gelenykullanici;

            header("Location:index.php?skD=0&skI=0");
            exit();
        }else{
            header("Location:index.php?skD=3");
            exit();
        }
    }else{
        header("Location:index.php?skD=1");
        exit();
    }
}else{
    header("Location:index.php?skD=0");
    exit();
}
?>