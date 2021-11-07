
<div class="anasayfabannerlari">
<?php
    $bannerSorgusu         =   $baglanti->prepare("SELECT * FROM bannerlar WHERE bannerAlani =  'Ana sayfa' ORDER BY gosterimSayisi ASC LIMIT 1");
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
    <p class="enyeniurunbaslik">En Yeni Ürünler</p>
<div class="enyeniurunlercon">
    <?php
        $enYeniUrunlerSorgusu    =  $baglanti->prepare("SELECT * FROM urunler WHERE Durumu = '1' ORDER BY id DESC LIMIT 5");
        $enYeniUrunlerSorgusu->execute();
        $enYeniUrunSayisi        =  $enYeniUrunlerSorgusu->rowCount();
        $enYeniUrunKayitlari     =  $enYeniUrunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

        $enYenidonguSayisi        =   1;

        foreach($enYeniUrunKayitlari as $enYeniUrunSatirlari){
            $enYeniUrunTuru              =    donusumleriGeriDondur($enYeniUrunSatirlari['urunTuru']);
            $enYeniurununFiyati          =    donusumleriGeriDondur($enYeniUrunSatirlari['urunFiyati']);
            $enYeniurununParaBirimi      =    donusumleriGeriDondur($enYeniUrunSatirlari['paraBirimi']);

            if($enYeniurununParaBirimi=="USD"){
                $enYeniurunFiyatiHesapla  =   $enYeniurununFiyati*$dolarKuru;
            }elseif($enYeniurununParaBirimi=="EUR"){
                $enYeniurunFiyatiHesapla  =   $enYeniurununFiyati*$euroKuru;
            }else{
                $enYeniurunFiyatiHesapla  =   $enYeniurununFiyati;
            }

            if($enYeniUrunTuru=="Erkek Ayakkabısı"){
                $enYeniUrunResimKlasoru   =   "erkek";
            }elseif($enYeniUrunTuru=="Kadın Ayakkabısı"){
                $enYeniUrunResimKlasoru  =   "kadin";
            }elseif($enYeniUrunTuru=="Çocuk Ayakkabısı"){
                $enYeniUrunResimKlasoru  =   "cocuk";
            }

            $enYeniurununToplamYorumSayisi    =   donusumleriGeriDondur($enYeniUrunSatirlari['yorumSayisi']);
            $enYeniurununToplamYorumPuani     =   donusumleriGeriDondur($enYeniUrunSatirlari['toplamYorumPuani']);

            if($enYeniurununToplamYorumSayisi>0){
                $enYenipuanHesapla                =   number_format($enYeniurununToplamYorumPuani/$enYeniurununToplamYorumSayisi, 2, ".", "");
            }else{
                $enYenipuanHesapla                =   0;
            }
            
            if($enYenipuanHesapla==0){
                $enYenipuanResmi      =   "YildizCizgiliBos.png";
            }elseif(($enYenipuanHesapla>0) and ($enYenipuanHesapla<=1)){
                $enYenipuanResmi      =   "YildizCizgiliBirDolu.png";
            }elseif(($enYenipuanHesapla>1) and ($enYenipuanHesapla<=2)){
                $enYenipuanResmi      =   "YildizCizgiliIkiDolu.png";
            }elseif(($enYenipuanHesapla>2) and ($enYenipuanHesapla<=3)){
                $enYenipuanResmi      =   "YildizCizgiliUcDolu.png";
            }elseif(($enYenipuanHesapla>3) and ($enYenipuanHesapla<=4)){
                $enYenipuanResmi      =   "YildizCizgiliDortDolu.png";
            }elseif($enYenipuanHesapla>4){
                $enYenipuanResmi      =   "YildizCizgiliBesDolu.png";
            }
        ?>
            <div class="enyeniurunlerblog">
                <table>
                    <tr>
                        <th><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enYeniUrunSatirlari['id']); ?>"><img class="ayakkabiresimbilgiresim" src="resimler/urunResimleri/<?php echo $enYeniUrunResimKlasoru; ?>/<?php echo donusumleriGeriDondur($enYeniUrunSatirlari["urunResmiBir"]); ?>"></a></th>
                    </tr>
                    <tr class="ayakkabiresimbilgisatir">
                        <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enYeniUrunSatirlari['id']); ?>" class="ayakkabiresimbilgisatirurunTuru"><?php echo donusumleriGeriDondur($enYeniUrunTuru); ?></a></td>
                    </tr>
                    <tr>
                        <td><div class="ayakkabiresimbilgisatirurunAdi"><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enYeniUrunSatirlari['id']); ?>"><?php echo donusumleriGeriDondur($enYeniUrunSatirlari["urunAdi"]);?></a></div></td>
                    </tr>
                    <tr class="ayakkabiresimbilgisatir">
                        <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enYeniUrunSatirlari['id']); ?>"><img src="resimler/<?php echo $enYenipuanResmi; ?>" ></a></td>
                    </tr>
                    <tr class="ayakkabiresimbilgisatir">
                        <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enYeniUrunSatirlari['id']); ?>" class="ayakkabiresimbilgisatirurunFiyat"><?php echo fiyatBicimlendir($enYeniurunFiyatiHesapla);?> TL</a></td>
                    </tr>
                </table>
            </div>                 
        <?php
        $enYenidonguSayisi++;
        ?>
        <?php
        }   
        ?> 
</div>
    <p class="enpopulerurunbaslik">En Popüler Ürünler</p>
<div class="enyeniurunlercon">
<?php
    $enPopulerUrunlerSorgusu    =  $baglanti->prepare("SELECT * FROM urunler WHERE Durumu = '1' ORDER BY goruntulenmeSayisi DESC LIMIT 5");
    $enPopulerUrunlerSorgusu->execute();
    $enPopulerUrunSayisi        =  $enPopulerUrunlerSorgusu->rowCount();
    $enPopulerUrunKayitlari     =  $enPopulerUrunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

    $enPopulerdonguSayisi        =   1;

    foreach($enPopulerUrunKayitlari as $enPopulerUrunSatirlari){
        $enPopulerUrunTuru              =    donusumleriGeriDondur($enPopulerUrunSatirlari['urunTuru']);
        $enPopulerurununFiyati          =    donusumleriGeriDondur($enPopulerUrunSatirlari['urunFiyati']);
        $enPopulerurununParaBirimi      =    donusumleriGeriDondur($enPopulerUrunSatirlari['paraBirimi']);

        if($enPopulerurununParaBirimi=="USD"){
            $enPopulerurunFiyatiHesapla  =   $enPopulerurununFiyati*$dolarKuru;
        }elseif($enPopulerurununParaBirimi=="EUR"){
            $enPopulerurunFiyatiHesapla  =   $enPopulerurununFiyati*$euroKuru;
        }else{
            $enPopulerurunFiyatiHesapla  =   $enPopulerurununFiyati;
        }

        if($enPopulerUrunTuru=="Erkek Ayakkabısı"){
            $enPopulerUrunResimKlasoru   =   "erkek";
        }elseif($enPopulerUrunTuru=="Kadın Ayakkabısı"){
            $enPopulerUrunResimKlasoru  =   "kadin";
        }elseif($enPopulerUrunTuru=="Çocuk Ayakkabısı"){
            $enPopulerUrunResimKlasoru  =   "cocuk";
        }

        $enPopulerurununToplamYorumSayisi    =   donusumleriGeriDondur($enPopulerUrunSatirlari['yorumSayisi']);
        $enPopulerurununToplamYorumPuani     =   donusumleriGeriDondur($enPopulerUrunSatirlari['toplamYorumPuani']);

        if($enPopulerurununToplamYorumSayisi>0){
            $enPopulerpuanHesapla                =   number_format($enPopulerurununToplamYorumPuani/$enPopulerurununToplamYorumSayisi, 2, ".", "");
        }else{
            $enPopulerpuanHesapla                =   0;
        }
        
        if($enPopulerpuanHesapla==0){
            $enPopulerpuanResmi      =   "YildizCizgiliBos.png";
        }elseif(($enPopulerpuanHesapla>0) and ($enPopulerpuanHesapla<=1)){
            $enPopulerpuanResmi      =   "YildizCizgiliBirDolu.png";
        }elseif(($enPopulerpuanHesapla>1) and ($enPopulerpuanHesapla<=2)){
            $enPopulerpuanResmi      =   "YildizCizgiliIkiDolu.png";
        }elseif(($enPopulerpuanHesapla>2) and ($enPopulerpuanHesapla<=3)){
            $enPopulerpuanResmi      =   "YildizCizgiliUcDolu.png";
        }elseif(($enPopulerpuanHesapla>3) and ($enPopulerpuanHesapla<=4)){
            $enPopulerpuanResmi      =   "YildizCizgiliDortDolu.png";
        }elseif($enPopulerpuanHesapla>4){
            $enPopulerpuanResmi      =   "YildizCizgiliBesDolu.png";
        }
    ?>
        <div class="enyeniurunlerblog">
            <table>
                <tr>
                    <th><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enPopulerUrunSatirlari['id']); ?>"><img class="ayakkabiresimbilgiresim" src="resimler/urunResimleri/<?php echo $enPopulerUrunResimKlasoru; ?>/<?php echo donusumleriGeriDondur($enPopulerUrunSatirlari["urunResmiBir"]); ?>"></a></th>
                </tr>
                <tr class="ayakkabiresimbilgisatir">
                    <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enPopulerUrunSatirlari['id']); ?>" class="ayakkabiresimbilgisatirurunTuru"><?php echo donusumleriGeriDondur($enPopulerUrunTuru); ?></a></td>
                </tr>
                <tr>
                    <td><div class="ayakkabiresimbilgisatirurunAdi"><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enPopulerUrunSatirlari['id']); ?>"><?php echo donusumleriGeriDondur($enPopulerUrunSatirlari["urunAdi"]);?></a></div></td>
                </tr>
                <tr class="ayakkabiresimbilgisatir">
                    <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enPopulerUrunSatirlari['id']); ?>"><img src="resimler/<?php echo $enPopulerpuanResmi; ?>" ></a></td>
                </tr>
                <tr class="ayakkabiresimbilgisatir">
                    <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enPopulerUrunSatirlari['id']); ?>" class="ayakkabiresimbilgisatirurunFiyat"><?php echo fiyatBicimlendir($enPopulerurunFiyatiHesapla);?> TL</a></td>
                </tr>
            </table>
        </div>                 
    <?php
    $enPopulerdonguSayisi++;
    ?>
    <?php
    }
    ?> 
</div>
    <p class="enpopulerurunbaslik">En Çok Satan Ürünler</p>
<div class="enyeniurunlercon">
    <?php
        $enCokSatanUrunlerSorgusu    =  $baglanti->prepare("SELECT * FROM urunler WHERE Durumu = '1' ORDER BY toplamSatisSayisi DESC LIMIT 5");
        $enCokSatanUrunlerSorgusu->execute();
        $enCokSatanUrunSayisi        =  $enCokSatanUrunlerSorgusu->rowCount();
        $enCokSatanUrunKayitlari     =  $enCokSatanUrunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

        $enCokSatandonguSayisi        =   1;

        foreach($enCokSatanUrunKayitlari as $enCokSatanUrunSatirlari){
            $enCokSatanUrunTuru              =    donusumleriGeriDondur($enCokSatanUrunSatirlari['urunTuru']);
            $enCokSatanurununFiyati          =    donusumleriGeriDondur($enCokSatanUrunSatirlari['urunFiyati']);
            $enCokSatanurununParaBirimi      =    donusumleriGeriDondur($enCokSatanUrunSatirlari['paraBirimi']);

            if($enCokSatanurununParaBirimi=="USD"){
                $enCokSatanurunFiyatiHesapla  =   $enCokSatanurununFiyati*$dolarKuru;
            }elseif($enCokSatanurununParaBirimi=="EUR"){
                $enCokSatanurunFiyatiHesapla  =   $enCokSatanurununFiyati*$euroKuru;
            }else{
                $enCokSatanurunFiyatiHesapla  =   $enCokSatanurununFiyati;
            }

            if($enCokSatanUrunTuru=="Erkek Ayakkabısı"){
                $enCokSatanUrunResimKlasoru   =   "erkek";
            }elseif($enCokSatanUrunTuru=="Kadın Ayakkabısı"){
                $enCokSatanUrunResimKlasoru  =   "kadin";
            }elseif($enCokSatanUrunTuru=="Çocuk Ayakkabısı"){
                $enCokSatanUrunResimKlasoru  =   "cocuk";
            }

            $enCokSatanurununToplamYorumSayisi    =   donusumleriGeriDondur($enCokSatanUrunSatirlari['yorumSayisi']);
            $enCokSatanurununToplamYorumPuani     =   donusumleriGeriDondur($enCokSatanUrunSatirlari['toplamYorumPuani']);

            if($enCokSatanurununToplamYorumSayisi>0){
                $enCokSatanpuanHesapla                =   number_format($enCokSatanurununToplamYorumPuani/$enCokSatanurununToplamYorumSayisi, 2, ".", "");
            }else{
                $enCokSatanpuanHesapla                =   0;
            }
            
            if($enCokSatanpuanHesapla==0){
                $enCokSatanpuanResmi      =   "YildizCizgiliBos.png";
            }elseif(($enCokSatanpuanHesapla>0) and ($enCokSatanpuanHesapla<=1)){
                $enCokSatanpuanResmi      =   "YildizCizgiliBirDolu.png";
            }elseif(($enCokSatanpuanHesapla>1) and ($enCokSatanpuanHesapla<=2)){
                $enCokSatanpuanResmi      =   "YildizCizgiliIkiDolu.png";
            }elseif(($enCokSatanpuanHesapla>2) and ($enCokSatanpuanHesapla<=3)){
                $enCokSatanpuanResmi      =   "YildizCizgiliUcDolu.png";
            }elseif(($enCokSatanpuanHesapla>3) and ($enCokSatanpuanHesapla<=4)){
                $enCokSatanpuanResmi      =   "YildizCizgiliDortDolu.png";
            }elseif($enCokSatanpuanHesapla>4){
                $enCokSatanpuanResmi      =   "YildizCizgiliBesDolu.png";
            }
        ?>
            <div class="enyeniurunlerblog">
                <table>
                    <tr>
                        <th><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari['id']); ?>"><img class="ayakkabiresimbilgiresim" src="resimler/urunResimleri/<?php echo $enCokSatanUrunResimKlasoru; ?>/<?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari["urunResmiBir"]); ?>"></a></th>
                    </tr>
                    <tr class="ayakkabiresimbilgisatir">
                        <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari['id']); ?>" class="ayakkabiresimbilgisatirurunTuru"><?php echo donusumleriGeriDondur($enCokSatanUrunTuru); ?></a></td>
                    </tr>
                    <tr>
                        <td><div class="ayakkabiresimbilgisatirurunAdi"><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari['id']); ?>"><?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari["urunAdi"]);?></a></div></td>
                    </tr>
                    <tr class="ayakkabiresimbilgisatir">
                        <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari['id']); ?>"><img src="resimler/<?php echo $enCokSatanpuanResmi; ?>" ></a></td>
                    </tr>
                    <tr class="ayakkabiresimbilgisatir">
                        <td><a href="index.php?sk=82&id=<?php echo donusumleriGeriDondur($enCokSatanUrunSatirlari['id']); ?>" class="ayakkabiresimbilgisatirurunFiyat"><?php echo fiyatBicimlendir($enCokSatanurunFiyatiHesapla);?> TL</a></td>
                    </tr>
                </table>
            </div>                 
        <?php
        $enCokSatandonguSayisi++;
        ?>
        <?php
        }  
        ?> 
</div>
<div class="anasayfateslimatiadecon">
    <div class="anasayfateslimatiadeblog">
        <img src="resimler/HizliTeslimat.png">
        <p><b>Bugün Teslimat</b></p>
        <p>Saat 14:00'a kadar verdiğiniz siparişler aynı gün kapınızda.</p>
    </div>
    <div class="anasayfateslimatiadeblog">
        <img src="resimler/GuvenliAlisveris.png">
        <p><b>Tek Tıkla Güvenli Alışveriş</b></p>
        <p>Ödeme ve adres bilgilerinizi kaydedin, güvenli alışverişin keyfini çıkarın.</p>
    </div>
    <div class="anasayfateslimatiadeblog">
        <img src="resimler/MobilErisim.png">
        <p><b>Mobil Erişim</b></p>
        <p>Dilediğiniz her cihazdan sistemimize erişebilir ve alışveriş yapabilirsiniz.</p>
    </div>
    <div class="anasayfateslimatiadeblog">
        <img src="resimler/IadeGarantisi.png">
        <p><b>Kolay İade</b></p>
        <p>Aldığınız herhangi bir ürünü 14 gün içerisinde kolaylıkla iade edbilirsiniz.</p>
    </div>
</div>
 
 
 






          
