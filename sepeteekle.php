<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if(isset($_POST["varyant"])){
        $gelenvaryantId    =   guvenlik($_POST["varyant"]);
    }else{
        $gelenvaryantId     =    "";
    }

    if(($gelenid!="") and ($gelenvaryantId!="")){
        $kullanicininSepetKontrolSorgusu  =     $baglanti->prepare("SELECT * FROM sepet WHERE uyeId = ? ORDER BY id DESC LIMIT 1");
        $kullanicininSepetKontrolSorgusu->execute([$kullaniciID]);
        $kullanicininSepetSayisi          =     $kullanicininSepetKontrolSorgusu->rowCount();

        if($kullanicininSepetSayisi>0){
            $urunSepetKontrolSorgusu      =     $baglanti->prepare("SELECT * FROM sepet WHERE uyeId = ? AND urunId = ? AND varyantId = ? LIMIT 1");
            $urunSepetKontrolSorgusu->execute([$kullaniciID, $gelenid, $gelenvaryantId]);
            $urunSepetSayisi              =     $urunSepetKontrolSorgusu->rowCount();
            $urunSepetKaydi               =     $urunSepetKontrolSorgusu->fetch(PDO::FETCH_ASSOC);

            if($urunSepetSayisi>0){
                $urununIdsi                     =     $urunSepetKaydi["id"];
                $urununSepettekiMevcutAdedi     =     $urunSepetKaydi["urunAdedi"];
                $urununYeniAdedi                =     $urununSepettekiMevcutAdedi+1;

                $urunGuncellemeSorgusu   =   $baglanti->prepare("UPDATE sepet SET urunAdedi = ? WHERE id = ? AND uyeId = ? AND urunId = ? LIMIT 1");
                $urunGuncellemeSorgusu->execute([$urununYeniAdedi, $urununIdsi, $kullaniciID, $gelenid]);
                $urunGuncellemeSayisi  =   $urunGuncellemeSorgusu->rowCount();

                if($urunGuncellemeSayisi>0){
                    header("Location:index.php?sk=94");
                    exit();
                }else{
                    header("Location:index.php?sk=92");
                    exit();
                }
            
            }else{
                $urununIdsi                     =     $urunSepetKaydi["id"];
                $urununSepettekiMevcutAdedi     =     $urunSepetKaydi["urunAdedi"];
                $urununYeniAdedi                =     $urununSepettekiMevcutAdedi+1;

                $urunEklemeSorgusu       =   $baglanti->prepare("INSERT INTO sepet (sepetNumarasi, adresId, urunAdedi, kargoId, odemeSecimi, taksitSecimi, uyeId, urunId, varyantId) values (? , ?, ?, ? , ?, ?, ? , ?, ?)");
                $urunEklemeSorgusu->execute([0, 0, $urununYeniAdedi, 0, 0, 0, $kullaniciID, $gelenid, $gelenvaryantId]);
                $urunEklemeSayisi        =   $urunEklemeSorgusu->rowCount();
                $sonIdDegeri             =   $baglanti->lastInsertId();

                if($urunEklemeSayisi>0){
                    $siparisNumarasiniGuncelleSorgusu   =   $baglanti->prepare("UPDATE sepet SET sepetNumarasi = ? WHERE uyeId = ?");
                    $siparisNumarasiniGuncelleSorgusu->execute([$sonIdDegeri, $kullaniciID]);
                    $siparisNumarasiniGuncelleSayisi    =   $siparisNumarasiniGuncelleSorgusu->rowCount();
                        if($siparisNumarasiniGuncelleSayisi>0){
                            header("Location:index.php?sk=94");
                            exit();
                        }else{
                            header("Location:index.php?sk=92");
                            exit();                       
                        }
                }else{
                    header("Location:index.php?sk=92");
                    exit();            
                }
            }
        }else{
            $urunEklemeSorgusu       =   $baglanti->prepare("INSERT INTO sepet (sepetNumarasi, adresId, kargoId, odemeSecimi, taksitSecimi, uyeId, urunId, varyantId, urunAdedi) values (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $urunEklemeSorgusu->execute([0, 0, 0, 0, 0, $kullaniciID, $gelenid, $gelenvaryantId, 1]);
            $urunEklemeSayisi        =   $urunEklemeSorgusu->rowCount();
            $sonIdDegeri             =   $baglanti->lastInsertId();

            if($urunEklemeSayisi>0){
                $siparisNumarasiniGuncelleSorgusu   =   $baglanti->prepare("UPDATE sepet SET sepetNumarasi = ? WHERE uyeId = ?");
                $siparisNumarasiniGuncelleSorgusu->execute([$sonIdDegeri, $kullaniciID]);
                $siparisNumarasiniGuncelleSayisi    =   $siparisNumarasiniGuncelleSorgusu->rowCount();
                    if($siparisNumarasiniGuncelleSayisi>0){
                        header("Location:index.php?sk=94");
                        exit();
                    }else{
                        header("Location:index.php?sk=92");
                        exit();
                    }
            }else{
                header("Location:index.php?sk=92");
                exit();
            }
        }
    }else{
        header("Location:index.php");
        exit();
    }
}else{
    header("Location:index.php?sk=93");
    exit();
}
?>