<?php
if(isset($_GET["id"])){
$gelenid           =   sayiliIcerikleriFiltrele(guvenlik($_GET["id"]));
$urununGoruntulenmeGuncellemeSorgusu  =   $baglanti->prepare("UPDATE urunler SET goruntulenmeSayisi=goruntulenmeSayisi+1 WHERE id = ? AND Durumu = ? LIMIT 1");
$urununGoruntulenmeGuncellemeSorgusu->execute([$gelenid, 1]);

$urunSorgusu           =   $baglanti->prepare("SELECT * FROM urunler WHERE id = ? AND Durumu = ? LIMIT 1");
$urunSorgusu->execute([$gelenid, 1]);
$urunSayisi            =   $urunSorgusu->rowCount();
$urunSorgusuKaydi      =   $urunSorgusu->fetch(PDO::FETCH_ASSOC);

    if($urunSayisi>0){
        $urunTuru      =   $urunSorgusuKaydi["urunTuru"];
        if($urunTuru=="Erkek Ayakkabısı"){
            $resimKlasoru   =   "erkek";
        }elseif($urunTuru=="Kadın Ayakkabısı"){
            $resimKlasoru   =   "kadin";            
        }elseif($urunTuru=="Çocuk Ayakkabısı"){
            $resimKlasoru   =   "cocuk";            
        }

        $urununFiyati          =    donusumleriGeriDondur($urunSorgusuKaydi['urunFiyati']);
        $urununParaBirimi      =    donusumleriGeriDondur($urunSorgusuKaydi['paraBirimi']);

        if($urununParaBirimi=="USD"){
            $urunFiyatiHesapla  =   $urununFiyati*$dolarKuru;
        }elseif($urununParaBirimi=="EUR"){
            $urunFiyatiHesapla  =   $urununFiyati*$euroKuru;
        }else{
            $urunFiyatiHesapla  =   $urununFiyati;
        }
?>
<div class="urundetaycon">
    <div class="urundetaysol">
        <div class="urundetaysolresim">
            <img id="buyukResim" src="resimler/urunResimleri/<?php echo $resimKlasoru; ?>/<?php echo donusumleriGeriDondur($urunSorgusuKaydi["urunResmiBir"]); ?>">
            <div class="urundetaydortluresimblog">
                <img src="resimler/urunResimleri/<?php echo $resimKlasoru; ?>/<?php echo donusumleriGeriDondur($urunSorgusuKaydi["urunResmiBir"]); ?>" onclick="$.urunDetayResmiDegistir('<?php echo $resimKlasoru; ?>', '<?php echo donusumleriGeriDondur($urunSorgusuKaydi['urunResmiBir']); ?>');">

                <?php if(($urunSorgusuKaydi["urunResmiIki"]!="") and ($urunSorgusuKaydi["urunResmiIki"] !== "0")){ ?> <img src="resimler/urunResimleri/<?php echo $resimKlasoru; ?>/<?php echo donusumleriGeriDondur($urunSorgusuKaydi["urunResmiIki"]); ?>" onclick="$.urunDetayResmiDegistir('<?php echo $resimKlasoru; ?>', '<?php echo donusumleriGeriDondur($urunSorgusuKaydi['urunResmiIki']); ?>');"> <?php }else{ ?> <?php } ?>

                <?php if(($urunSorgusuKaydi["urunResmiUc"]!="") and ($urunSorgusuKaydi["urunResmiUc"] !== "0")){ ?> <img src="resimler/urunResimleri/<?php echo $resimKlasoru; ?>/<?php echo donusumleriGeriDondur($urunSorgusuKaydi["urunResmiUc"]); ?>" onclick="$.urunDetayResmiDegistir('<?php echo $resimKlasoru; ?>', '<?php echo donusumleriGeriDondur($urunSorgusuKaydi['urunResmiUc']); ?>');"> <?php }else{ ?> <?php } ?>

                <?php if(($urunSorgusuKaydi["urunResmiDort"]!="") and ($urunSorgusuKaydi["urunResmiDort"] !== "0")){ ?> <img src="resimler/urunResimleri/<?php echo $resimKlasoru; ?>/<?php echo donusumleriGeriDondur($urunSorgusuKaydi["urunResmiDort"]); ?>" onclick="$.urunDetayResmiDegistir('<?php echo $resimKlasoru; ?>', '<?php echo donusumleriGeriDondur($urunSorgusuKaydi['urunResmiDort']); ?>');"> <?php }else{ ?> <?php } ?>
            </div>            
        </div>
        <div class="urundetaysolreklam">
            <h4>REKLAMLAR</h4>
            <?php
            $bannerSorgusu         =   $baglanti->prepare("SELECT * FROM bannerlar WHERE bannerAlani =  'Ürün Detay' ORDER BY gosterimSayisi ASC LIMIT 1");
            $bannerSorgusu->execute();
            $bannerSayisi          =   $bannerSorgusu->rowCount();
            $bannerKaydi           =   $bannerSorgusu->fetch(PDO::FETCH_ASSOC);

            ?>               
            <p><img src="resimler/<?php echo donusumleriGeriDondur($bannerKaydi["bannerResmi"]); ?>"></p>  
            <?php
            $bannerGuncelle         =   $baglanti->prepare("UPDATE bannerlar SET gosterimSayisi=gosterimSayisi+1 WHERE id = ? LIMIT 1");
            $bannerGuncelle->execute([$bannerKaydi["id"]]);
            ?>            
        </div>
    </div>
    <div class="urundetaysag">
        <h3><?php echo donusumleriGeriDondur($urunSorgusuKaydi["urunAdi"]); ?></h3>
        <form action="index.php?sk=91&id=<?php echo donusumleriGeriDondur($urunSorgusuKaydi["id"]); ?>" method="POST">
        <div class="urundetaysagfavsepet">
            <div class="urundetayfavlink">
                <a href="<?php echo donusumleriGeriDondur($sosyalLinkInstagram); ?>" target="_blank"><i class="fa fa-instagram fa-lg" aria-hidden="true"></i></a>
                <a href="<?php echo donusumleriGeriDondur($sosyalLinkTwitter); ?>" target="_blank"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i></a>
                <?php if(isset($_SESSION["kullanici"])){?><a href="index.php?sk=87&id=<?php echo donusumleriGeriDondur($urunSorgusuKaydi["id"]); ?>"><img src="resimler/KalpKirmiziDaireliBeyaz24x24.png"></a>
                <?php }else{ ?><a href="index.php?sk=31"><img src="resimler/KalpKirmiziDaireliBeyaz24x24.png"></a><?php } ?>
            </div>
            <div class="urundetaysepetbutonu">
                <input type="submit" value="sepete ekle">             
            </div>
        </div>
        <div class="varyantvefiyat">
            <select name="varyant">
                <option value="">Lütfen <?php echo donusumleriGeriDondur($urunSorgusuKaydi["varyantBasligi"]); ?> Seçiniz</option>
                <?php

                $varyantSorgusu         =   $baglanti->prepare("SELECT * FROM urunvaryantlari WHERE urunId = ? AND stokAdedi > ? ORDER BY varyantAdi ASC");
                $varyantSorgusu->execute([donusumleriGeriDondur($urunSorgusuKaydi["id"]), 0]);
                $varyantSayisi          =   $varyantSorgusu->rowCount();
                $varyantKayitlari       =   $varyantSorgusu->fetchAll(PDO::FETCH_ASSOC);

                foreach($varyantKayitlari as $varyantSecimi){
                ?>
                <option value="<?php echo donusumleriGeriDondur($varyantSecimi["id"]); ?>"><?php echo donusumleriGeriDondur($varyantSecimi["varyantAdi"]); ?></option>
                <?php
                }
                ?>
            </select>
            <p><?php echo fiyatBicimlendir($urunFiyatiHesapla);?> TL</p>
        </div>
        <hr>
        <div class="urundetaykargoodemecon">
            <div class="urundetaykargoodemeblog">
                <img src="resimler/SaatEsnetikGri20x20.png">
                <p>Siparişiniz <?php echo ucGunIleriTarihBul(); ?> tarihine kadar kargoya verilecektir.</p>
            </div>
            <div class="urundetaykargoodemeblog">
                <img src="resimler/SaatEsnetikGri20x20.png">
                <p>İlgili ürün süper hızlı gönderi kapsamındadır. Aynı gün teslimat yapılabilir.</p>
            </div>
            <div class="urundetaykargoodemeblog">
                <img src="resimler/KrediKarti20x20.png">
                <p>Tüm bankaların kredi kartları ile peşin veya taksitli ödeme seçeneği.</p>
            </div>
            <div class="urundetaykargoodemeblog">
                <img src="resimler/Banka20x20.png">
                <p>Tüm bankalardan havale veya EFT ile ödeme seçeneği.</p>
            </div>
        </div>
        <hr>
        <div class="urundetaysagaciklamaveyorum">
            <h4>Ürün Açıklaması</h4>
            <p><?php echo donusumleriGeriDondur($urunSorgusuKaydi['urunAciklamasi']); ?></p>
            <h4>Ürün Yorumları</h4>
            <div class="urundetayyorumlar">
                <?php
                $yorumlarSorgusu       =   $baglanti->prepare("SELECT * FROM yorumlar WHERE urunId = ? ORDER BY yorumTarihi DESC");
                $yorumlarSorgusu->execute([donusumleriGeriDondur($urunSorgusuKaydi["id"])]);
                $yorumSayisi           =   $yorumlarSorgusu->rowCount();
                $yorumKayitlari        =   $yorumlarSorgusu->fetchAll(PDO::FETCH_ASSOC);

                if($yorumSayisi>0){
                    foreach($yorumKayitlari as $yorumSatirlari){
                        $yorumPuani    =    donusumleriGeriDondur($yorumSatirlari["puan"]);

                        if($yorumPuani==1){
                            $yorumPuanResmi    =   "YildizBirDolu.png";
                        }elseif($yorumPuani==2){
                            $yorumPuanResmi    =   "YildizIkiDolu.png";
                        }elseif($yorumPuani==3){
                            $yorumPuanResmi    =   "YildizUcDolu.png";
                        }elseif($yorumPuani==4){
                            $yorumPuanResmi    =   "YildizDortDolu.png";
                        }elseif($yorumPuani==5){
                            $yorumPuanResmi    =   "YildizBesDolu.png";
                        }

                        $yorumIcinUyeSorgusu   =   $baglanti->prepare("SELECT * FROM uyeler WHERE id = ? LIMIT 1");
                        $yorumIcinUyeSorgusu->execute([donusumleriGeriDondur($yorumSatirlari["uyeId"])]);
                        $yorumIcinUyeKaydi     =   $yorumIcinUyeSorgusu->fetch(PDO::FETCH_ASSOC);
                ?>

                <ul>
                    <li><img src="resimler/<?php echo $yorumPuanResmi; ?>" alt=""></li>
                    <li><?php echo donusumleriGeriDondur($yorumIcinUyeKaydi["isimSoyisim"]); ?></li>
                    <li><?php echo tarihBul(donusumleriGeriDondur($yorumSatirlari["yorumTarihi"])); ?></li>
                </ul>

                <p class="urundetayyorumsatir"><?php echo donusumleriGeriDondur($yorumSatirlari["yorumMetni"]); ?></p>
                <?php
                    }
                }else{
                ?>
                <p>Bu ürün için henüz yorum yapılmadı.</p>
                <?php
                }
                ?>
            </div>
        </div>
        </form>
    </div>
</div>
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
