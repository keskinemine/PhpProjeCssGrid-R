<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["urunId"])){
        $gelenurunId        =   guvenlik($_GET["urunId"]);
    }else{
        $gelenurunId        =    "";
    }
    if(isset($_POST["yorumpuan"])){
        $gelenyorumpuan     =   guvenlik($_POST["yorumpuan"]);
    }else{
        $gelenyorumpuan     =    "";
    }
    if(isset($_POST["yorum"])){
        $gelenyorum         =   guvenlik($_POST["yorum"]);
    }else{
        $gelenyorum         =    "";
    }

    if(($gelenurunId!="") and ($gelenyorumpuan!="") and ($gelenyorum!="")){
        $yorumKayitSorgusu       =   $baglanti->prepare("INSERT INTO yorumlar (urunId, uyeId, puan, yorumMetni, yorumTarihi, yorumIpAdresi) VALUES (?, ?, ?, ?, ?, ?)");
        $yorumKayitSorgusu->execute([$gelenurunId, $kullaniciID, $gelenyorumpuan, $gelenyorum, $zamanDamgasi, $IPAdresi]);  
        $yorumKayitKontrol       =   $yorumKayitSorgusu->rowCount();
        
        if($yorumKayitKontrol>0){
            $urunGuncellemeSorgusu      =   $baglanti->prepare("UPDATE urunler SET yorumSayisi=yorumSayisi+1, toplamYorumPuani= toplamYorumPuani+? WHERE id=? LIMIT 1");
            $urunGuncellemeSorgusu->execute([$gelenyorumpuan, $gelenurunId]);  
            $urunGuncellemeKontrol          =   $urunGuncellemeSorgusu->rowCount();

            if($urunGuncellemeKontrol>0){
                header("Location:index.php?sk=77"); 
                exit();
            }else{
                header("Location:index.php?sk=78"); 
                exit();
            }
        }else{
            header("Location:index.php?sk=78"); 
            exit();
        }    
    }else{
        header("Location:index.php?sk=79"); 
        exit();
    }          
}else{
    header("Location:index.php");
    exit();
}
?>