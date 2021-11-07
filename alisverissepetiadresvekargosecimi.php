<?php
if(isset($_SESSION["kullanici"])){

$stokIcinSepettekiUrunlerSorgusu       =   $baglanti->prepare("SELECT * FROM sepet WHERE uyeId = ?");
$stokIcinSepettekiUrunlerSorgusu->execute([$kullaniciID]);
$stokIcinSepettekiUrunSayisi           =   $stokIcinSepettekiUrunlerSorgusu->rowCount();
$stokIcinSepetKayitlari                =   $stokIcinSepettekiUrunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

if($stokIcinSepettekiUrunSayisi>0){
    foreach($stokIcinSepetKayitlari as $stokIcinSepettekiSatirlar){
        $stokIcinSepetIdsi                     =   $stokIcinSepettekiSatirlar["id"];
        $stokIcinSepettekiUrununVaryantIdsi    =   $stokIcinSepettekiSatirlar["varyantId"];
        $stokIcinSepettekiUrununAdedi          =   $stokIcinSepettekiSatirlar["urunAdedi"];

        $stokIcinUrunVaryantBilgileriSorgusu   =   $baglanti->prepare("SELECT * FROM urunvaryantlari WHERE id = ? LIMIT 1");
        $stokIcinUrunVaryantBilgileriSorgusu->execute([$stokIcinSepettekiUrununVaryantIdsi]);
        $stokIcinUaryantKaydi                  =   $stokIcinUrunVaryantBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
            $stokIcinUrununStokAdedi               =   $stokIcinUaryantKaydi["stokAdedi"];

        if($stokIcinUrununStokAdedi==0){
            $sepetSilSorgusu          =   $baglanti->prepare("DELETE FROM sepet WHERE id = ? AND uyeId = ? LIMIT 1");
            $sepetSilSorgusu->execute([$stokIcinSepetIdsi, $kullaniciID]);
            $sepetSilmeSayisi         =   $sepetSilSorgusu->rowCount();

        }elseif($stokIcinSepettekiUrununAdedi>$stokIcinUrununStokAdedi){
            $sepetGuncellemeSorgusu     =   $baglanti->prepare("UPDATE sepet SET urunAdedi= ? WHERE id = ? AND uyeId = ? LIMIT 1");
            $sepetGuncellemeSorgusu->execute([$stokIcinUrununStokAdedi, $stokIcinSepetIdsi, $kullaniciID]);
        }
    }
}
?>
<form action="index.php?sk=99" method="POST">
    <div class="alisverissepeticon">
        <div class="alisverissepetisol">
            <h3>Alışveriş Sepeti</h3>
            <p class="havalebildirimyazi">Adres ve kargo seçiminizi bu alanda yapabilirsiniz.</p>
            <div class="alisverissepetiadressecekle">
                <p >Adres Seçimi</p>
                <p ><a href="index.php?sk=70">+Yeni Adres Ekle</a></p>
            </div>
            <div class="alisverissepetiurunlercon">
                <?php
                $sepettekiUrunlerSorgusu       =   $baglanti->prepare("SELECT * FROM sepet WHERE uyeId = ? ORDER BY id DESC ");
                $sepettekiUrunlerSorgusu->execute([$kullaniciID]);
                $sepettekiUrunSayisi           =   $sepettekiUrunlerSorgusu->rowCount();
                $sepetKayitlari                =   $sepettekiUrunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

                if($sepettekiUrunSayisi>0){
                    $sepettekiToplamUrunSayisi      =   0;
                    $sepettekiToplamFiyat           =   0;

                        foreach($sepetKayitlari as $sepetSatirlari){ 
                            $sepetIdsi                     =   $sepetSatirlari["id"];
                            $sepettekiUrununIdsi           =   $sepetSatirlari["urunId"];
                            $sepettekiUrununVaryantIdsi    =   $sepetSatirlari["varyantId"];
                            $sepettekiUrununAdedi          =   $sepetSatirlari["urunAdedi"];

                            $urunBilgileriSorgusu          =   $baglanti->prepare("SELECT * FROM urunler WHERE id = ? LIMIT 1");
                            $urunBilgileriSorgusu->execute([$sepettekiUrununIdsi]);
                            $urunKaydi                     =   $urunBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                                $urununTuru             =    $urunKaydi["urunTuru"];
                                $urununFiyati           =    $urunKaydi["urunFiyati"];
                                $urununparaBirimi       =    $urunKaydi["paraBirimi"];
                                $urununKargoUcreti      =    $urunKaydi["kargoUcreti"];

                            if($urununTuru=="Erkek Ayakkabısı"){
                                $urunResimleriKlasoru   =   "erkek";
                            }elseif($urununTuru=="Kadın Ayakkabısı"){
                                $urunResimleriKlasoru   =   "kadin";            
                            }elseif($urununTuru=="Çocuk Ayakkabısı"){
                                $urunResimleriKlasoru   =   "cocuk";            
                            }         
                            
                            if($urununparaBirimi=="USD"){
                                $urunFiyatiHesapla              =   $urununFiyati*$dolarKuru;
                                $urunFiyatiBicimlendir          =   fiyatBicimlendir($urunFiyatiHesapla);
                            }elseif($urununparaBirimi=="EUR"){
                                $urunFiyatiHesapla      =   $urununFiyati*$euroKuru; 
                                $urunFiyatiBicimlendir  =   fiyatBicimlendir($urunFiyatiHesapla);
                            }else{
                                $urunFiyatiHesapla      =   $urununFiyati;
                                $urunFiyatiBicimlendir  =   fiyatBicimlendir($urununFiyati);
                            }


                            $urunToplamFiyatiHesapla        =   ($urunFiyatiHesapla*$sepettekiUrununAdedi);
                            $urunToplamFiyatiBiçimlendir    =   fiyatBicimlendir($urunToplamFiyatiHesapla);

                            $sepettekiToplamUrunSayisi      +=   $sepettekiUrununAdedi;
                            $sepettekiToplamFiyat           +=   ($urunFiyatiHesapla*$sepettekiUrununAdedi);

                            $sepettekiToplamKargoFiyati      =   $urununKargoUcreti; 
                        }

                        if($sepettekiToplamFiyat>=$ucretsizKargoBaraji){
                            $sepettekiToplamKargoFiyati                =   0;
                            $odenecekToplamTutariHesaplaBicimlendir    =   fiyatBicimlendir($sepettekiToplamFiyat);
                        }else{
                            $odenecekToplamTutariHesapla               =   ($sepettekiToplamFiyat+$sepettekiToplamKargoFiyati);
                            $odenecekToplamTutariHesaplaBicimlendir    =   fiyatBicimlendir($odenecekToplamTutariHesapla);
                        }

                    $adreslerSorgusu     =   $baglanti->prepare("SELECT * FROM adresler WHERE uyeId = ? ORDER BY id DESC ");
                    $adreslerSorgusu->execute([$kullaniciID]);
                    $adresSayisi         =   $adreslerSorgusu->rowCount();
                    $adresKayitlari     =   $adreslerSorgusu->fetchAll(PDO::FETCH_ASSOC);

                    if($adresSayisi>0){
                        foreach($adresKayitlari as $adresSatirlari){
                    ?>
                    <div class="alisverissepetiadresblog">
                        <p><input type="radio" name="adresSecimi" checked="checked" value="<?php echo donusumleriGeriDondur($adresSatirlari["id"]); ?>" ></p>
                        <p><?php echo donusumleriGeriDondur($adresSatirlari["adiSoyadi"]); ?> - <?php echo donusumleriGeriDondur($adresSatirlari["adres"]); ?> <?php echo donusumleriGeriDondur($adresSatirlari["ilce"]); ?> / <?php echo donusumleriGeriDondur($adresSatirlari["sehir"]); ?> - <?php echo donusumleriGeriDondur($adresSatirlari["telefon"]); ?></p>
                    </div>
                    <?php
                        }
                    }else{
                    ?>
                    <p class="alisverissepetikargoodemeadresekleme">Sisteme kayıtlı adresiniz bulunmamaktadır. Lütfen öncelikle "Hesabım" alanından adres ekleyiniz. Adres eklemek için lütfen <a href="index.php?sk=70">buraya tıklayınız</a></p>                
                    <?php
                    }
                    ?>               
                    <p class="alisverissepetiadressecekle">Kargo Seçimi</p>
                    <div class="bankablogcon">
                    <?php
                    $kargolarSorgusu    =  $baglanti->prepare("SELECT * FROM kargofirmalari");
                    $kargolarSorgusu->execute();
                    $kargoSayisi        =  $kargolarSorgusu->rowCount();
                    $kargoKayitlari     =  $kargolarSorgusu->fetchAll(PDO::FETCH_ASSOC);
                    
                    $donguSayisi        =   1;
                    $sutunAdetSayisi    =   3;
                    $secimIcinSayi      =   1;
                        foreach($kargoKayitlari as $kargoKAydi){
                    ?>
                        <div class="alisverissepetikargosatirlari">
                            <p><input type="radio" name="kargoSecimi" <?php if($secimIcinSayi==1){ ?> checked="checked" <?php } ?> value="<?php echo donusumleriGeriDondur($kargoKAydi["id"]); ?>"></p>
                            <p><img class="bankasatirlogo" src="resimler/<?php echo donusumleriGeriDondur($kargoKAydi["kargoFirmasiLogosu"]); ?>"></p>
                        </div>
                        <?php
                        $donguSayisi++;
                        $secimIcinSayi++;
                        if($donguSayisi>$sutunAdetSayisi){
                        ?>
                    </div>
                    <div class="bankablogcon">
                    <?php
                        $donguSayisi =1;
                    }
                    }
                    ?>
                    </div>  
                    <?php
                    }else{
                        $sepettekiToplamUrunSayisi  =   0;
                        $sepettekiToplamFiyat       =   0;

                        header("Location:index.php?sk=94");
                        exit();
                    }
                    ?>
            </div>
        </div>
        <div class="alisverissepetisag">
            <h3>Sipariş Özeti</h3>
            <p class="havalebildirimyazi">Toplam <span><?php echo $sepettekiToplamUrunSayisi; ?></span> Adet Ürün</p>
            <div class="alisverissepetisagodeme">
                <p>Ürünler Toplam Fiyat (KDV Dahil)</p>
                <p><?php echo fiyatBicimlendir($sepettekiToplamFiyat); ?> TL</p>
            </div>
            <div class="alisverissepetisagodeme">
                <p>Kargo Tutarı (KDV Dahil)</p>
                <p><?php echo fiyatBicimlendir($sepettekiToplamKargoFiyati ); ?> TL</p>               
            </div>
            <div class="alisverissepetisagodeme">
                <p>Ödenecek Tutar (KDV Dahil)</p>
                <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                <input type="submit" value="ÖDEME SEÇİMİ" class="alisverisitamambutonu">
            </div>
        </div>
    </div>
</form>
<?php
}else{
    header("Location:index.php");
    exit();
}
?>