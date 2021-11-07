<?php
if(isset($_SESSION["kullanici"])){

$sayfalamaIcinSolveSagButonSayisi       =   2;
$sayfaBasinaGosterilecekKayitSayisi     =   5;
$toplamKayitSayisiSorgusu               =   $baglanti->prepare("SELECT * FROM favoriler WHERE uyeId = ? ORDER BY id DESC");
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
    <h3>Hesabım > Favoriler</h3>
    <p class="havalebildirimyazi">Favorilerinize eklediğiniz tüm ürünleri bu alandan görüntüleyebilirsiniz.</p>
    <div class="hesabimfavoriler">
        <p>Resim</p>
        <p>Sil</p>
        <p>Adı</p>
        <p>Fiyatı</p>     
    </div>
    <?php
    $favorilerSorgusu        =   $baglanti->prepare("SELECT * FROM favoriler WHERE uyeId = ? ORDER BY id DESC LIMIT $sayfalamayaBaslanacakKayitSayisi, $sayfaBasinaGosterilecekKayitSayisi");
    $favorilerSorgusu->execute([$kullaniciID]);
    $favorilerSayisi         =   $favorilerSorgusu->rowCount();
    $favorilerKayitlari      =   $favorilerSorgusu->fetchAll(PDO::FETCH_ASSOC);

    if($favorilerSayisi>0){
        foreach($favorilerKayitlari as $favoriSatirlar){

            $urunlerSorgusu      =   $baglanti->prepare("SELECT * FROM urunler WHERE id = ? LIMIT 1");
            $urunlerSorgusu->execute([$favoriSatirlar["urunId"]]);
            $urunKaydi           =   $urunlerSorgusu->fetch(PDO::FETCH_ASSOC);

            $urununAdi           =   $urunKaydi["urunAdi"];
            $urununTuru          =   $urunKaydi["urunTuru"];
            $urununResmi         =   $urunKaydi["urunResmiBir"];
            $urununFiyati        =   $urunKaydi["urunFiyati"];
            $urununParaBirimi    =   $urunKaydi["paraBirimi"];

            if($urununTuru   == "Erkek Ayakkabısı"){
                $resimKlasoruAdi    =  "erkek";
            }elseif($urununTuru    == "Kadın Ayakkabısı"){
                $resimKlasoruAdi    =  "kadin";
            }else{
                $resimKlasoruAdi    =  "cocuk";
            }
    ?>
            <div class="hesabimfavorilersatirlar">   
                <p><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($urunKaydi['id']); ?>"><img src="resimler/urunResimleri/<?php echo $resimKlasoruAdi; ?>/<?php echo donusumleriGeriDondur($urununResmi); ?>"></a></p>     
                <p><a href="index.php?sk=80&id=<?php echo donusumleriGeriDondur($favoriSatirlar['id']); ?>"> <img src="resimler/Sil20x20.png"></a></p>     
                <p><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($urunKaydi['id']); ?>"><?php echo donusumleriGeriDondur($urununAdi); ?></a></p>         
                <p><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($urunKaydi['id']); ?>"><?php echo fiyatBicimlendir(donusumleriGeriDondur($urununFiyati));?> <?php echo donusumleriGeriDondur($urununParaBirimi); ?></a></p>     
            </div>
    <?php
        }
    ?>
    <?php       
        if($bulunanSayfaSayisi>1){ 
    ?>
        <div class="sayfalamaAlaniKapsayicisi">
            <div class="sayfalamaAlaniIciMetinAlaniKapsayicisi">
                Toplam <?php echo $bulunanSayfaSayisi; ?> sayfada, <?php echo $toplamKayitSayisiSorgusu; ?> adet kayıt bulunmaktadır.
            </div>
            <div class="sayfalamaAlaniIciNumaraAlaniKapsayicisi">
                <?php
                if($sayfalama>1){
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=59&syf=1'><<</a></span>";
                    $sayfalamaIcinSayfaDegeriniBirGeriAl    =   $sayfalama-1;
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=59&syf=" . $sayfalamaIcinSayfaDegeriniBirGeriAl .  "'><</a></span>";
                }

                for($sayfalamaIcinSayfaIndexDegeri = $sayfalama-$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri<=$sayfalama+$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri++){
                    if(($sayfalamaIcinSayfaIndexDegeri>0) and ($sayfalamaIcinSayfaIndexDegeri<=$bulunanSayfaSayisi)){
                        if($sayfalama==$sayfalamaIcinSayfaIndexDegeri){
                            echo "<span class='sayfalamaAktif'>". $sayfalamaIcinSayfaIndexDegeri .  "</span>";
                        }else{
                            echo "<span class='sayfalamaPasif'><a href='index.php?sk=59&syf=" . $sayfalamaIcinSayfaIndexDegeri .  "'> " . $sayfalamaIcinSayfaIndexDegeri . "</a></span>";
                        }
                    }
                }

                if($sayfalama!=$bulunanSayfaSayisi){
                    $sayfalamaIcinSayfaDegeriniBirIleriAl   =   $sayfalama+1;
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=59&syf=" . $sayfalamaIcinSayfaDegeriniBirIleriAl .  "'>></a></span>";
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=59&syf=" . $bulunanSayfaSayisi .  "'>>></a></span>";                    
                }
                ?>
            </div>
        </div>
    <?php
        }
    }else{
    ?>
        <p class="hesabimadreslerbilgiyazi">Sisteme kayıtlı favori ürününüz bulunmamaktadır.</p>
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
