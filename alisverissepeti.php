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

$sepetiSifirlamaSorgusu     =   $baglanti->prepare("UPDATE sepet SET adresId = ?, kargoId = ?, odemeSecimi = ?, taksitSecimi = ?  WHERE uyeId = ?");
$sepetiSifirlamaSorgusu->execute([0, 0, "", 0, $kullaniciID]);
?>
<div class="alisverissepeticon">
    <div class="alisverissepetisol">
        <h3>Alışveriş Sepeti</h3>
        <p class="havalebildirimyazi">Alışveriş sepetine eklemiş olduğunuz ürünleri bu sayfada görüntüleyebilirsiniz.</p>
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
                        $urununResmi            =    $urunKaydi["urunResmiBir"];
                        $urununAdi              =    $urunKaydi["urunAdi"];
                        $urununFiyati           =    $urunKaydi["urunFiyati"];
                        $urununparaBirimi       =    $urunKaydi["paraBirimi"];
                        $urununVaryantBasligi   =    $urunKaydi["varyantBasligi"];

                    $urunVaryantBilgileriSorgusu   =   $baglanti->prepare("SELECT * FROM urunvaryantlari WHERE id = ? LIMIT 1");
                    $urunVaryantBilgileriSorgusu->execute([$sepettekiUrununVaryantIdsi]);
                    $varyantKaydi                  =   $urunVaryantBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                        $urununVaryantAdi     =   $varyantKaydi["varyantAdi"];
                        $urununStokAdedi      =   $varyantKaydi["stokAdedi"];

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

            ?>
            <div class="alisverissepetiurunlerblog">
                <p><img src="resimler/urunResimleri/<?php echo $urunResimleriKlasoru; ?>/<?php echo donusumleriGeriDondur($urununResmi); ?>"></p>
                <p><a href="index.php?sk=95&id=<?php echo donusumleriGeriDondur($sepetIdsi); ?>"><img src="resimler/SilDaireli20x20.png"></a></p>
                <p><?php echo donusumleriGeriDondur($urununAdi); ?> <br> <?php echo donusumleriGeriDondur($urununVaryantBasligi); ?> : <?php echo donusumleriGeriDondur($urununVaryantAdi); ?></p>
                
                <p><a href="index.php?sk=96&id=<?php echo donusumleriGeriDondur($sepetIdsi); ?>"><?php if($sepettekiUrununAdedi>1){ ?><img src="resimler/AzaltDaireli20x20.png" ></a><?php }else{ ?> </a>  <?php } ?></p>
                <p><?php echo donusumleriGeriDondur($sepettekiUrununAdedi); ?></p>
                <p><a href="index.php?sk=97&id=<?php echo donusumleriGeriDondur($sepetIdsi); ?>"><img src="resimler/ArttirDaireli20x20.png" alt=""></a></p>
                <p><?php echo $urunFiyatiBicimlendir; ?> TL <br> <?php echo $urunToplamFiyatiBiçimlendir; ?> TL</p>

            </div>

            <?php
                }
            }else{
                $sepettekiToplamUrunSayisi  =   0;
                $sepettekiToplamFiyat       =   0;
            ?>           
            <p>Alışveriş sepetinizde ürün bulunmamaktadır.</p>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="alisverissepetisag">
        <h3>Sipariş Özeti</h3>
        <p class="havalebildirimyazi">Toplam <span><?php echo $sepettekiToplamUrunSayisi; ?></span> Adet Ürün</p>
        <div class="alisverissepetisagodeme">
            <p>Ödenecek Tutar (KDV Dahil)</p>
            <p><?php echo fiyatBicimlendir($sepettekiToplamFiyat); ?> TL</p>
            <p><a href="index.php?sk=98"><img src="resimler/SepetBeyaz21x20.png"><a href="index.php?sk=98"> DEVAM ET </a></a></p>
        </div>
    </div>
</div>
<?php
}else{
    header("Location:index.php");
    exit();
}
?>