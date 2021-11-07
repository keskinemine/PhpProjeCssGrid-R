<?php

if(isset($_SESSION["kullanici"])){
    if(isset($_POST["odemeTuruSecimi"])){
        $gelenodemeTuruSecimi     =   guvenlik($_POST["odemeTuruSecimi"]);
    }else{
        $gelenodemeTuruSecimi     =    "";
    }
    if(isset($_POST["taksitsecimi"])){
        $gelentaksitsecimi         =   guvenlik($_POST["taksitsecimi"]);
    }else{
        $gelentaksitsecimi    =    "";
    }

    if($gelenodemeTuruSecimi!=""){
        if($gelenodemeTuruSecimi=="Banka Havalesi"){
            $alisverisSepetiSorgusu    =   $baglanti->prepare("SELECT * FROM sepet WHERE uyeId = ?");
            $alisverisSepetiSorgusu->execute([$kullaniciID]);
            $sepetSayisi               =   $alisverisSepetiSorgusu->rowCount();
            $sepetUrunleri             =   $alisverisSepetiSorgusu->fetchAll(PDO::FETCH_ASSOC);

            if($sepetSayisi>0){
                foreach($sepetUrunleri as $sepetSatirlari){
                    $sepetIdsi                     =   $sepetSatirlari["id"];
                    $sepetsepetNumarasi            =   $sepetSatirlari["sepetNumarasi"];
                    $sepettekiuyeId                =   $sepetSatirlari["uyeId"];
                    $sepettekiurunId               =   $sepetSatirlari["urunId"];
                    $sepettekiadresId              =   $sepetSatirlari["adresId"];
                    $sepettekivaryantId            =   $sepetSatirlari["varyantId"];
                    $sepettekikargoId              =   $sepetSatirlari["kargoId"];
                    $sepettekiurunAdedi            =   $sepetSatirlari["urunAdedi"];
                    $sepettekiodemeSecimi          =   $sepetSatirlari["odemeSecimi"];
                    $sepettekitaksitSecimi         =   $sepetSatirlari["taksitSecimi"];

                    $urunBilgileriSorgusu          =   $baglanti->prepare("SELECT * FROM urunler WHERE id = ? LIMIT 1");
                    $urunBilgileriSorgusu->execute([$sepettekiurunId]);
                    $urunKaydi                  =   $urunBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                        $urununTuru             =    $urunKaydi["urunTuru"];
                        $urununAdi              =    $urunKaydi["urunAdi"];
                        $urununFiyati           =    $urunKaydi["urunFiyati"];
                        $urununparaBirimi       =    $urunKaydi["paraBirimi"];
                        $urununkdvOrani         =    $urunKaydi["kdvOrani"];
                        $urununkargoUcreti      =    $urunKaydi["kargoUcreti"];
                        $urununResmi            =    $urunKaydi["urunResmiBir"];
                        $urununVaryantBasligi   =    $urunKaydi["varyantBasligi"];

                    $urunVaryantBilgileriSorgusu   =   $baglanti->prepare("SELECT * FROM urunvaryantlari WHERE id = ? LIMIT 1");
                    $urunVaryantBilgileriSorgusu->execute([$sepettekivaryantId]);
                    $varyantKaydi                  =   $urunVaryantBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                        $varyantAdi            =   $varyantKaydi["varyantAdi"];

                    $kargoBilgileriSorgusu     =   $baglanti->prepare("SELECT * FROM kargofirmalari WHERE id = ? LIMIT 1");
                    $kargoBilgileriSorgusu->execute([$sepettekikargoId]);
                    $kargoKaydi                =   $kargoBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                        $kargonunAdi           =   $kargoKaydi["kargoFirmasiAdi"];

                    $adresBilgileriSorgusu     =   $baglanti->prepare("SELECT * FROM adresler WHERE id = ? LIMIT 1");
                    $adresBilgileriSorgusu->execute([$sepettekiadresId]);
                    $adresoKaydi               =   $adresBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                        $adresadiSoyadi        =   $adresoKaydi["adiSoyadi"];
                        $adresAdres            =   $adresoKaydi["adres"];
                        $adresilce             =   $adresoKaydi["ilce"];
                        $adressehir            =   $adresoKaydi["sehir"];
                        $adresToparla          =   $adresAdres . " " . $adresilce . " " . $adressehir;
                        $adrestelefon          =   $adresoKaydi["telefon"];

                    if($urununparaBirimi=="USD"){
                        $urunFiyatiHesapla              =   $urununFiyati*$dolarKuru;
                    }elseif($urununparaBirimi=="EUR"){
                        $urunFiyatiHesapla              =   $urununFiyati*$euroKuru; 
                    }else{
                        $urunFiyatiHesapla              =   $urununFiyati;
                    }

                    $urununToplamFiyati                 =   ($urunFiyatiHesapla*$sepettekiurunAdedi);
                    $urununToplamKargoFiyati            =   $urununkargoUcreti; 

                    $siparisEkle        =      $baglanti->prepare("INSERT INTO siparisler (uyeId, siparisNumarasi, urunId, urunTuru, urunAdi, urunFiyati, kdvOrani, urunAdedi, toplamUrunFiyati, kargoFirmasiSecimi, kargoUcreti, urunResmiBir, varyantBasligi, varyantSecimi, adresAdiSoyadi, adresDetay, adresTelefon, odemeSecimi, taksitSecimi, siparisTarihi, siparisIpAdresi, onayDurumu, kargoDurumu, kargoGonderiKodu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $siparisEkle->execute([$sepettekiuyeId, $sepetsepetNumarasi, $sepettekiurunId, $urununTuru, $urununAdi, $urunFiyatiHesapla, $urununkdvOrani, $sepettekiurunAdedi, $urununToplamFiyati, $kargonunAdi, $urununToplamKargoFiyati, $urununResmi, $urununVaryantBasligi, $varyantAdi, $adresadiSoyadi, $adresToparla, $adrestelefon, $gelenodemeTuruSecimi, 0, $zamanDamgasi, $IPAdresi, 0, 0, 0]);
                    $eklemeKontrol      =      $siparisEkle->rowCount();

                    if($eklemeKontrol>0){
                        $sepettenSilmeSorgusu       =   $baglanti->prepare("DELETE FROM sepet WHERE id = ? AND uyeId = ? LIMIT 1");
                        $sepettenSilmeSorgusu->execute([$sepetIdsi, $sepettekiuyeId]);

                        $urunSatisiArttirmaSorgusu  =   $baglanti->prepare("UPDATE urunler SET toplamSatisSayisi = toplamSatisSayisi + ? WHERE id = ?");  
                        $urunSatisiArttirmaSorgusu->execute([$sepettekiurunAdedi, $sepettekiurunId]);

                        $stokGuncellemeSorgusu      =   $baglanti->prepare("UPDATE urunvaryantlari SET stokAdedi = stokAdedi - ? WHERE id = ? LIMIT 1");  
                        $stokGuncellemeSorgusu->execute([$sepettekiurunAdedi ,$sepettekivaryantId]);
                    }else{
                        header("Location:index.php?sk=102");
                        exit();
                    }         
                }
 
                $kargoFiyatiIcinSiparislerSorgusu    =   $baglanti->prepare("SELECT SUM(toplamUrunFiyati) AS toplamUcret FROM siparisler WHERE uyeId = ? AND siparisNumarasi = ?");
                $kargoFiyatiIcinSiparislerSorgusu->execute([$kullaniciID, $sepetsepetNumarasi]);
                $kargoFiyatiKaydi                    =   $kargoFiyatiIcinSiparislerSorgusu->fetch(PDO::FETCH_ASSOC);
                    $toplamUcretimiz                 =   $kargoFiyatiKaydi["toplamUcret"];

                    if($toplamUcretimiz>=$ucretsizKargoBaraji){
                        $siparisiGuncelle     =   $baglanti->prepare("UPDATE siparisler SET kargoUcreti = ? WHERE uyeId = ? AND siparisNumarasi = ?");
                        $siparisiGuncelle->execute([0, $sepettekiuyeId, $sepetsepetNumarasi]);
                    }

                header("Location:index.php?sk=101");
                exit();
            }else{
                header("Location:index.php");
                exit();
            }
        }else{
            if($gelentaksitsecimi!=""){
                $sepetiGuncelle     =   $baglanti->prepare("UPDATE sepet SET odemeSecimi = ?, taksitSecimi = ?  WHERE uyeId = ?");
                $sepetiGuncelle->execute([$gelenodemeTuruSecimi, $gelentaksitsecimi, $kullaniciID]);
                $sepetKontrol       =   $sepetiGuncelle->rowCount();

                if($sepetKontrol>0){
                    header("Location:index.php?sk=103");
                    exit();
                }else{
                    header("Location:index.php");
                    exit();
                }
            }else{
                header("Location:index.php");
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