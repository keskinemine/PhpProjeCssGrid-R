<?php
if(isset($_SESSION["kullanici"])){

$sayfalamaIcinSolveSagButonSayisi       =   2;
$sayfaBasinaGosterilecekKayitSayisi     =   5;
$toplamKayitSayisiSorgusu               =   $baglanti->prepare("SELECT DISTINCT siparisNumarasi FROM siparisler WHERE uyeId = ? ORDER BY siparisNumarasi DESC");
$toplamKayitSayisiSorgusu->execute([$kullaniciID]);
$toplamKayitSayisiSorgusu               =   $toplamKayitSayisiSorgusu->rowCount();
$sayfalamayaBaslanacakKayitSayisi       =   ($sayfalama*$sayfaBasinaGosterilecekKayitSayisi)-$sayfaBasinaGosterilecekKayitSayisi;
$bulunanSayfaSayisi                     =   ceil($toplamKayitSayisiSorgusu/$sayfaBasinaGosterilecekKayitSayisi);
?>
<hr>
    <div class="hesabimnav">
        <ul>
            <li><a href="index.php?sk=50">Üyelik</a></li>
            <li><a href="index.php?sk=58">Adresler</a></li>
            <li><a href="index.php?sk=59">Favoriler</a></li>
            <li><a href="index.php?sk=60">Yorumlar</a></li>
            <li><a href="index.php?sk=61">Siparişler</a></li>
        </ul>
    </div>
<hr/>
<div class="hesabimadresler">
    <h3>Hesabım > Siparişler</h3>
    <p class="havalebildirimyazi">Tüm siparişlerinizi bu alandan görüntüleyebilirsiniz.</p>
    <div class="hesabimsiparisler">
        <p>Sipariş Numarası</p>
        <p>Resim</p>
        <p>Yorum</p>
        <p>Adı</p>
        <p>Fiyatı</p>
        <p>Adet</p>
        <p>Toplam Fiyatı</p>
        <p>Kargo Durumu / Takip</p>       
    </div>
    <?php
    $siparisNumaralariSorgusu        =   $baglanti->prepare("SELECT DISTINCT siparisNumarasi FROM siparisler WHERE uyeId = ? ORDER BY siparisNumarasi DESC LIMIT $sayfalamayaBaslanacakKayitSayisi, $sayfaBasinaGosterilecekKayitSayisi");
    $siparisNumaralariSorgusu->execute([$kullaniciID]);
    $siparisNumaralariSayisi         =   $siparisNumaralariSorgusu->rowCount();
    $siparisNumaralariKayitlari      =   $siparisNumaralariSorgusu->fetchAll(PDO::FETCH_ASSOC);

    if($siparisNumaralariSayisi>0){
        foreach($siparisNumaralariKayitlari as $siparisNumaralariSatirlar){ 
            $siparisNo      =    donusumleriGeriDondur($siparisNumaralariSatirlar["siparisNumarasi"]);

            $siparisSorgusu               =   $baglanti->prepare("SELECT * FROM siparisler WHERE uyeId = ? AND siparisNumarasi = ? ORDER BY id ASC");
            $siparisSorgusu->execute([$kullaniciID, $siparisNo]);
            $siparisSorgusuKayitlari      =   $siparisSorgusu->fetchAll(PDO::FETCH_ASSOC);

            foreach($siparisSorgusuKayitlari as $siparisSatirlari){
                $urunTuru         =     donusumleriGeriDondur($siparisSatirlari["urunTuru"]); 
                    if($urunTuru   == "Erkek Ayakkabısı"){
                        $resimKlasoruAdi    =  "erkek";
                    }elseif($urunTuru   == "Kadın Ayakkabısı"){
                        $resimKlasoruAdi    =  "kadin";
                    }else{
                        $resimKlasoruAdi    =  "cocuk";
                    }

                    $kargoDurumu    =   donusumleriGeriDondur($siparisSatirlari["kargoDurumu"]);
                    if($kargoDurumu == 0){
                        $kargoDurumuYazdir  =   "Beklemede";
                    }else{
                        $kargoDurumuYazdir  =   donusumleriGeriDondur($siparisSatirlari["kargoGonderiKodu"]);
                    }
    ?>
                     <div class="hesabimsiparislersatirlar">
                        <p>#<?php echo donusumleriGeriDondur($siparisSatirlari["siparisNumarasi"]); ?></p>     
                        <p><img src="resimler/urunResimleri/<?php echo $resimKlasoruAdi; ?>/<?php echo donusumleriGeriDondur($siparisSatirlari["urunResmiBir"]); ?>"></p>     
                        <p><a href="index.php?sk=75&urunId=<?php echo donusumleriGeriDondur($siparisSatirlari["urunId"]); ?>"><img src="resimler/DokumanKirmiziKalemli20x20.png"></a></p>     
                        <p><?php echo donusumleriGeriDondur($siparisSatirlari["urunAdi"]); ?></p>     
                        <p><?php echo fiyatBicimlendir(donusumleriGeriDondur($siparisSatirlari["urunFiyati"])); ?> TL</p>     
                        <p><?php echo donusumleriGeriDondur($siparisSatirlari["urunAdedi"]); ?></p>     
                        <p><?php echo fiyatBicimlendir(donusumleriGeriDondur($siparisSatirlari["toplamUrunFiyati"])); ?> TL</p>     
                        <p><?php echo $kargoDurumuYazdir; ?></p>     
                    </div>
    <?php
            }
    ?>
                    <hr>
    <?php
        }
        if($bulunanSayfaSayisi>1){ 
    ?>
        <div class="sayfalamaAlaniKapsayicisi">
            <div class="sayfalamaAlaniIciMetinAlaniKapsayicisi">
                Toplam <?php echo $bulunanSayfaSayisi; ?> sayfada, <?php echo $toplamKayitSayisiSorgusu; ?> adet kayıt bulunmaktadır.
            </div>
            <div class="sayfalamaAlaniIciNumaraAlaniKapsayicisi">
                <?php
                if($sayfalama>1){
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=61&syf=1'><<</a></span>";
                    $sayfalamaIcinSayfaDegeriniBirGeriAl    =   $sayfalama-1;
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=61&syf=" . $sayfalamaIcinSayfaDegeriniBirGeriAl .  "'><</a></span>";
                }

                for($sayfalamaIcinSayfaIndexDegeri = $sayfalama-$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri<=$sayfalama+$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri++){
                    if(($sayfalamaIcinSayfaIndexDegeri>0) and ($sayfalamaIcinSayfaIndexDegeri<=$bulunanSayfaSayisi)){
                        if($sayfalama==$sayfalamaIcinSayfaIndexDegeri){
                            echo "<span class='sayfalamaAktif'>". $sayfalamaIcinSayfaIndexDegeri .  "</span>";
                        }else{
                            echo "<span class='sayfalamaPasif'><a href='index.php?sk=61&syf=" . $sayfalamaIcinSayfaIndexDegeri .  "'> " . $sayfalamaIcinSayfaIndexDegeri . "</a></span>";
                        }
                    }
                }

                if($sayfalama!=$bulunanSayfaSayisi){
                    $sayfalamaIcinSayfaDegeriniBirIleriAl   =   $sayfalama+1;
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=61&syf=" . $sayfalamaIcinSayfaDegeriniBirIleriAl .  "'>></a></span>";
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=61&syf=" . $bulunanSayfaSayisi .  "'>>></a></span>";                    
                }
                ?>
            </div>
        </div>
    <?php
        }
    }else{
    ?>
        <p class="hesabimadreslerbilgiyazi">Sisteme kayıtlı siparişiniz bulunmamaktadır.</p>
    <?php
    }
    ?>
</div>

<?php
}else{
    header("Location:index.php");
    exit();
}
?>
