<?php
if(isset($_SESSION["kullanici"])){

$sayfalamaIcinSolveSagButonSayisi       =   2;
$sayfaBasinaGosterilecekKayitSayisi     =   5;
$toplamKayitSayisiSorgusu               =   $baglanti->prepare("SELECT * FROM  yorumlar WHERE uyeId = ? ORDER BY yorumTarihi DESC");
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
    <h3>Hesabım > Yorumlar</h3>
    <p class="havalebildirimyazi">Tüm yorumlarınızı bu alandan görüntüleyebilirsiniz.</p>
    <div class="hesabimyorum">
        <p>Puan</p>
        <p>Yorum</p>     
    </div>
    <?php
    $yorumlarSorgusu        =   $baglanti->prepare("SELECT * FROM yorumlar WHERE uyeId = ? ORDER BY yorumTarihi DESC LIMIT $sayfalamayaBaslanacakKayitSayisi, $sayfaBasinaGosterilecekKayitSayisi");
    $yorumlarSorgusu->execute([$kullaniciID]);
    $yorumlarSayisi         =   $yorumlarSorgusu->rowCount();
    $yorumlarKayitlari      =   $yorumlarSorgusu->fetchAll(PDO::FETCH_ASSOC);

    if($yorumlarSayisi>0){
        foreach($yorumlarKayitlari as $satirlar){ 
            $verilenPuan = $satirlar["puan"];
            if($verilenPuan==1){
                $resimDosyasi   =   "YildizBirDolu.png";
            }elseif($verilenPuan==2){
                $resimDosyasi   =   "YildizIkiDolu.png";
            }elseif($verilenPuan==3){
                $resimDosyasi   =   "YildizUcDolu.png";
            }elseif($verilenPuan==4){
                $resimDosyasi   =   "YildizDortDolu.png";
            }elseif($verilenPuan==5){
                $resimDosyasi   =   "YildizBesDolu.png";
            }
    ?>
            <div class="hesabimyorumlarsatirlar">  
                <p><img src="resimler/<?php echo $resimDosyasi; ?>"></p>            
                <p><?php echo donusumleriGeriDondur($satirlar["yorumMetni"]); ?></p>         
            </div>
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
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=60&syf=1'><<</a></span>";
                    $sayfalamaIcinSayfaDegeriniBirGeriAl    =   $sayfalama-1;
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=60&syf=" . $sayfalamaIcinSayfaDegeriniBirGeriAl .  "'><</a></span>";
                }

                for($sayfalamaIcinSayfaIndexDegeri = $sayfalama-$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri<=$sayfalama+$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri++){
                    if(($sayfalamaIcinSayfaIndexDegeri>0) and ($sayfalamaIcinSayfaIndexDegeri<=$bulunanSayfaSayisi)){
                        if($sayfalama==$sayfalamaIcinSayfaIndexDegeri){
                            echo "<span class='sayfalamaAktif'>". $sayfalamaIcinSayfaIndexDegeri .  "</span>";
                        }else{
                            echo "<span class='sayfalamaPasif'><a href='index.php?sk=60&syf=" . $sayfalamaIcinSayfaIndexDegeri .  "'> " . $sayfalamaIcinSayfaIndexDegeri . "</a></span>";
                        }
                    }
                }

                if($sayfalama!=$bulunanSayfaSayisi){
                    $sayfalamaIcinSayfaDegeriniBirIleriAl   =   $sayfalama+1;
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=60&syf=" . $sayfalamaIcinSayfaDegeriniBirIleriAl .  "'>></a></span>";
                    echo "<span class='sayfalamaPasif'><a href='index.php?sk=60&syf=" . $bulunanSayfaSayisi .  "'>>></a></span>";                    
                }
                ?>
            </div>
        </div>
    <?php
        }
    }else{
    ?>
        <p class="hesabimadreslerbilgiyazi">Sisteme kayıtlı yorum bulunmamaktadır.</p>
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
