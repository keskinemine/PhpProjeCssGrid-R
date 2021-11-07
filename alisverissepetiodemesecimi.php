<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_POST["adresSecimi"])){
        $gelenadresSecimi     =   guvenlik($_POST["adresSecimi"]);
    }else{
        $gelenadresSecimi     =    "";
    }
    if(isset($_POST["kargoSecimi"])){
        $gelenkargoSecimi     =   guvenlik($_POST["kargoSecimi"]);
    }else{
        $gelenkargoSecimi     =    "";
    }

    if(($gelenadresSecimi!="") and ($gelenkargoSecimi!="")){ 
        $sepetiGuncellemeSorgusu       =   $baglanti->prepare("UPDATE sepet SET kargoId = ?, adresId = ? WHERE uyeId = ?");
        $sepetiGuncellemeSorgusu->execute([$gelenkargoSecimi, $gelenadresSecimi, $kullaniciID]);
        $guncellemeKontrol             =   $sepetiGuncellemeSorgusu->rowCount();

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

                    $ikiTaksitAylikOdemeTutari          =   number_format(($sepettekiToplamFiyat/2), "2", ",", ".");
                    $ucTaksitAylikOdemeTutari           =   number_format(($sepettekiToplamFiyat/3), "2", ",", ".");
                    $dortTaksitAylikOdemeTutari         =   number_format(($sepettekiToplamFiyat/4), "2", ",", ".");
                    $besTaksitAylikOdemeTutari          =   number_format(($sepettekiToplamFiyat/5), "2", ",", ".");
                    $altiTaksitAylikOdemeTutari         =   number_format(($sepettekiToplamFiyat/6), "2", ",", ".");
                    $yediTaksitAylikOdemeTutari         =   number_format(($sepettekiToplamFiyat/7), "2", ",", ".");
                    $sekizTaksitAylikOdemeTutari        =   number_format(($sepettekiToplamFiyat/8), "2", ",", ".");
                    $dokuzTaksitAylikOdemeTutari        =   number_format(($sepettekiToplamFiyat/9), "2", ",", ".");
                }
?>
<form action="index.php?sk=100" method="POST">
    <div class="alisverissepeticon">
        <div class="alisverissepetisol">
            <h3>Alışveriş Sepeti</h3>
            <p class="havalebildirimyazi">Ödeme türü seçiminizi bu alanda yapabilirsiniz.</p>           
                <p class="alisverissepetiadressecekle">Ödeme Türü Seçimi</p>
            <div class="alisverissepetiurunlercon">
                <div class="odemesecimi">
                    <p><input type="radio" name="odemeTuruSecimi" value="Kredi Kartı" checked="checked" onclick="$.KrediKartiSecildi();"><img src="resimler/KrediKarti92x75.png">Kredi Kartı</p>
                    <p><input type="radio" name="odemeTuruSecimi" value="Banka Havalesi" onclick="$.BankaHavalesiSecildi();"><img src="resimler/Banka80x75.png">Banka Havalesi</p>

                </div>
                <div class="kredikarticon">           
                    <p class="alisverissepetiadressecekle">Kredi Kartı ile Ödeme</p>
                        <p>Ödeme işleminizde aşağıdaki tüm kredi kartı markaları ile veya diğer markalar ile veya ATM(Bankamatik) kartı ile işlem yapabilirsiniz.</p>
                        <div class="bankasecimi">
                            <p><img src="resimler/OdemeSecimiAxessCard.png"></p>
                            <p><img src="resimler/OdemeSecimiBonusCard.png"></p>
                            <p><img src="resimler/OdemeSecimiCardFinans.png"></p>
                            <p><img src="resimler/OdemeSecimiMaximumCard.png"></p>
                            <p><img src="resimler/OdemeSecimiWorldCard.png"></p>
                            <p><img src="resimler/OdemeSecimiParafCard.png"></p>
                            <p><img src="resimler/OdemeSecimiDigerKartlar.png"></p>
                            <p><img src="resimler/OdemeSecimiATMKarti.png"></p>
                        </div>
                    <p class="alisverissepetiadressecekle">Taksit Seçimi</p> 
                        <p>Lütfen ödeme işleminde uygulanmasını istediğiniz taksit sayısını seçiniz.</p>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="1" checked="checked">Tek Çekim</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="2">2 Taksit</p>
                            <p>2 x <?php echo $ikiTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="3">3 Taksit</p>
                            <p>3 x <?php echo $ucTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="4">4 Taksit</p>
                            <p>4 x <?php echo $dortTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="5">5 Taksit</p>
                            <p>5 x <?php echo $besTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="6">6 Taksit</p>
                            <p>6 x <?php echo $altiTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="7">7 Taksit</p>
                            <p>7 x <?php echo $yediTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="8">8 Taksit</p>
                            <p>8 x <?php echo $sekizTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                        <div class="taksitsecimi">
                            <p><input type="radio" name="taksitsecimi" value="9">9 Taksit</p>
                            <p>9 x <?php echo $dokuzTaksitAylikOdemeTutari; ?> TL</p>
                            <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
                        </div>
                </div>
                <div class="bankahavalesicon">
                    <p class="alisverissepetiadressecekle">Banka Havalesi / EFT ile Ödeme</p> 
                        <p>Banka Havalesi / EFT ile ürün satın almak için, öncelikle alışveriş sepeti tutarını "Banka Hesaplarımız" sayfasında bulunan herhangi bir hesaba ödeme yaptıktan sonra "Havale Bildirim Formu" aracılığıyla lütfen tarafımıza iletiniz. "Ödeme Yap" butonuna tıkladığınız anda siparişiniz sisteme kayıt edilecektir.</p>

                </div>
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
                <input type="submit" value="ÖDEME YAP" class="alisverisitamambutonu">
            </div>
        </div>
    </div>
</form>
<?php

    }else{
        header("Location:index.php");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>