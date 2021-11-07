<?php
if(isset($_REQUEST["menuId"])){
    $gelenmenuId            =   sayiliIcerikleriFiltrele(guvenlik($_REQUEST["menuId"]));
    $menuKosulu             =   " AND menuId = ' " .$gelenmenuId . "' ";
    $sayfalamaKosulu        =   "&menuId=" .$gelenmenuId;
}else{  
    $gelenmenuId            =   "";
    $menuKosulu             =   "";
    $sayfalamaKosulu        =   "";
}

if(isset($_REQUEST["aramaIcerigi"])){
    $gelenaramaIcerigi      =   guvenlik($_REQUEST["aramaIcerigi"]);
    $aramaKosulu            =   " AND urunAdi LIKE '%" . $gelenaramaIcerigi . "%' ";
    $sayfalamaKosulu        .=   "&aramaIcerigi=" . $gelenaramaIcerigi;
}else{
    $aramaKosulu            =   "";
    $sayfalamaKosulu        .=   "";
}

$sayfalamaIcinSolveSagButonSayisi       =   2;
$sayfaBasinaGosterilecekKayitSayisi     =   10;
$toplamKayitSayisiSorgusu               =   $baglanti->prepare("SELECT * FROM urunler WHERE urunTuru = 'Kadın Ayakkabısı' AND Durumu = '1' $menuKosulu $aramaKosulu ORDER BY id DESC");
$toplamKayitSayisiSorgusu->execute();
$toplamKayitSayisiSorgusu               =   $toplamKayitSayisiSorgusu->rowCount();
$sayfalamayaBaslanacakKayitSayisi       =   ($sayfalama*$sayfaBasinaGosterilecekKayitSayisi)-$sayfaBasinaGosterilecekKayitSayisi;
$bulunanSayfaSayisi                     =   ceil($toplamKayitSayisiSorgusu/$sayfaBasinaGosterilecekKayitSayisi);

$anaMenununTumUrunSayiSorgusu           =   $baglanti->prepare("SELECT SUM(urunSayisi) AS menununToplamUrunu FROM menuler WHERE urunTuru = 'Kadın Ayakkabısı'");
$anaMenununTumUrunSayiSorgusu->execute();
$anaMenununTumUrunSayiSorgusu           =   $anaMenununTumUrunSayiSorgusu->fetch(PDO::FETCH_ASSOC);

?>
<div class="ayakkabilarcon">
    <div class="ayakkabilarsol">
        <div class="ayakkabilarsolmenuler">
            <h4>MENÜLER</h4>
            <p><a href="index.php?sk=85" style="<?php if($gelenmenuId=="") { ?> color: #FF9900;<?php } else{ ?>color: #646464; <?php } ?>">Tüm Ürünler (<?php echo $anaMenununTumUrunSayiSorgusu["menununToplamUrunu"]; ?>)</a></p>
            <?php
            $menulerSorgusu          =   $baglanti->prepare("SELECT * FROM menuler WHERE urunTuru =  'Kadın Ayakkabısı' ORDER BY menuAdi ASC");
            $menulerSorgusu->execute();
            $menuKayitSayisi         =   $menulerSorgusu->rowCount();
            $menuKayitlari           =   $menulerSorgusu->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($menuKayitlari as $menu){
            ?>

            <p><a href="index.php?sk=85&menuId=<?php echo $menu["id"]; ?>" style="<?php if($gelenmenuId==$menu["id"]) { ?> color: #FF9900;<?php } else{ ?>color: #646464; <?php } ?>"><?php echo donusumleriGeriDondur($menu["menuAdi"]); ?> (<?php echo donusumleriGeriDondur($menu["urunSayisi"]); ?>)</a></p>

            <?php                            
            }
            ?>
        </div>
        <div class="ayakkabilarsolreklamlar">
            <h4>REKLAMLAR</h4>
                <?php
                $bannerSorgusu         =   $baglanti->prepare("SELECT * FROM bannerlar WHERE bannerAlani =  'Menu Altı' ORDER BY gosterimSayisi ASC LIMIT 1");
                $bannerSorgusu->execute();
                $bannerSayisi          =   $bannerSorgusu->rowCount();
                $bannerKaydi           =   $bannerSorgusu->fetch(PDO::FETCH_ASSOC);

                ?>               
                <p><img src="resimler/<?php echo $bannerKaydi["bannerResmi"]; ?>"></p>  
                <?php
                $bannerGuncelle         =   $baglanti->prepare("UPDATE bannerlar SET gosterimSayisi=gosterimSayisi+1 WHERE id = ? LIMIT 1");
                $bannerGuncelle->execute([$bannerKaydi["id"]]);
                ?>                
        </div>
    </div>
    <div class="ayakkabilarsag">
        <form action="index.php?sk=85" method="POST">
        <?php
        if($gelenmenuId!=""){
        ?>
        <input type="hidden" name="menuId" value="<?php echo $gelenmenuId; ?>">
        <?php
        }
        ?>
        <div class="ayakkabiaramaalani">
            <input type="submit" value="">
            <input type="text" name="aramaIcerigi">
        </div>
        </form>
       
        <div class="ayakkabiresimbilgicontainer">
            <div class="ayakkabiresimbilgiblogcon">
            <?php
            $urunlerSorgusu    =  $baglanti->prepare("SELECT * FROM urunler WHERE urunTuru = 'Kadın Ayakkabısı' AND Durumu = '1' $menuKosulu $aramaKosulu ORDER BY id DESC LIMIT $sayfalamayaBaslanacakKayitSayisi, $sayfaBasinaGosterilecekKayitSayisi");
            $urunlerSorgusu->execute();
            $urunSayisi        =  $urunlerSorgusu->rowCount();
            $urunKayitlari     =  $urunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

            $donguSayisi        =   1;
            $sutunAdetSayisi    =   4;
            foreach($urunKayitlari as $kayit){
                $urununFiyati          =    donusumleriGeriDondur($kayit['urunFiyati']);
                $urununParaBirimi      =    donusumleriGeriDondur($kayit['paraBirimi']);

                if($urununParaBirimi=="USD"){
                    $urunFiyatiHesapla  =   $urununFiyati*$dolarKuru;
                }elseif($urununParaBirimi=="EUR"){
                    $urunFiyatiHesapla  =   $urununFiyati*$euroKuru;
                }else{
                    $urunFiyatiHesapla  =   $urununFiyati;
                }

                $urununToplamYorumSayisi    =   donusumleriGeriDondur($kayit['yorumSayisi']);
                $urununToplamYorumPuani     =   donusumleriGeriDondur($kayit['toplamYorumPuani']);

                if($urununToplamYorumSayisi>0){
                    $puanHesapla                =   number_format($urununToplamYorumPuani/$urununToplamYorumSayisi, 2, ".", "");
                }else{
                    $puanHesapla                =   0;
                }
                
                if($puanHesapla==0){
                    $puanResmi      =   "YildizCizgiliBos.png";
                }elseif(($puanHesapla>0) and ($puanHesapla<=1)){
                    $puanResmi      =   "YildizCizgiliBirDolu.png";
                }elseif(($puanHesapla>1) and ($puanHesapla<=2)){
                    $puanResmi      =   "YildizCizgiliIkiDolu.png";
                }elseif(($puanHesapla>2) and ($puanHesapla<=3)){
                    $puanResmi      =   "YildizCizgiliUcDolu.png";
                }elseif(($puanHesapla>3) and ($puanHesapla<=4)){
                    $puanResmi      =   "YildizCizgiliDortDolu.png";
                }elseif($puanHesapla>4){
                    $puanResmi      =   "YildizCizgiliBesDolu.png";
                }
            ?>
                <div class="ayakkabiresimbilgiblog">
                    <table>
                        <tr>
                            <th><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($kayit['id']); ?>"><img class="ayakkabiresimbilgiresim" src="resimler/urunResimleri/kadin/<?php echo donusumleriGeriDondur($kayit["urunResmiBir"]); ?>"></a></th>
                        </tr>
                        <tr class="ayakkabiresimbilgisatir">
                            <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($kayit['id']); ?>" class="ayakkabiresimbilgisatirurunTuru">Kadın Ayakkabısı</a></td>
                        </tr>
                        <tr>
                            <td><div class="ayakkabiresimbilgisatirurunAdi"><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($kayit['id']); ?>"><?php echo donusumleriGeriDondur($kayit["urunAdi"]);?></a></div></td>
                        </tr>
                        <tr class="ayakkabiresimbilgisatir">
                            <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($kayit['id']); ?>"><img src="resimler/<?php echo $puanResmi; ?>" ></a></td>
                        </tr>
                        <tr class="ayakkabiresimbilgisatir">
                            <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($kayit['id']); ?>" class="ayakkabiresimbilgisatirurunFiyat"><?php echo fiyatBicimlendir($urunFiyatiHesapla);?> TL</a></td>
                        </tr>
                    </table>
                </div>                 
            <?php
            $donguSayisi++;
            if($donguSayisi>$sutunAdetSayisi){
            ?>
            </div>
            <div class="ayakkabiresimbilgiblogcon">
            <?php
                $donguSayisi =1;
            }
            }
            ?>
            </div>
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
                        echo "<span class='sayfalamaPasif'><a href='index.php?sk=85" .$sayfalamaKosulu . "&syf=1'><<</a></span>";
                        $sayfalamaIcinSayfaDegeriniBirGeriAl    =   $sayfalama-1;
                        echo "<span class='sayfalamaPasif'><a href='index.php?sk=85" .$sayfalamaKosulu . "&syf=" . $sayfalamaIcinSayfaDegeriniBirGeriAl . "'><</a></span>";
                    }

                    for($sayfalamaIcinSayfaIndexDegeri = $sayfalama-$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri<=$sayfalama+$sayfalamaIcinSolveSagButonSayisi; $sayfalamaIcinSayfaIndexDegeri++){
                        if(($sayfalamaIcinSayfaIndexDegeri>0) and ($sayfalamaIcinSayfaIndexDegeri<=$bulunanSayfaSayisi)){
                            if($sayfalama==$sayfalamaIcinSayfaIndexDegeri){
                                echo "<span class='sayfalamaAktif'>". $sayfalamaIcinSayfaIndexDegeri .  "</span>";
                            }else{
                                echo "<span class='sayfalamaPasif'><a href='index.php?sk=85" .$sayfalamaKosulu . "&syf=" . $sayfalamaIcinSayfaIndexDegeri .  "'> " . $sayfalamaIcinSayfaIndexDegeri . "</a></span>";
                            }
                        }
                    }

                    if($sayfalama!=$bulunanSayfaSayisi){
                        $sayfalamaIcinSayfaDegeriniBirIleriAl   =   $sayfalama+1;
                        echo "<span class='sayfalamaPasif'><a href='index.php?sk=85" .$sayfalamaKosulu . "&syf=" . $sayfalamaIcinSayfaDegeriniBirIleriAl .  "'>></a></span>";
                        echo "<span class='sayfalamaPasif'><a href='index.php?sk=85" .$sayfalamaKosulu . "&syf=" . $bulunanSayfaSayisi .  "'>>></a></span>";                    
                    }          
                ?>                   
                </div>
            </div>
            <?php
            }
            ?>                        
        </div>
    </div>
</div>


