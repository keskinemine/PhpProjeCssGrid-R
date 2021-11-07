<?php
if(isset($_SESSION["kullanici"])){
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
    <h3>Hesabım > Adresler</h3>
    <p class="havalebildirimyazi">Tüm adreslerinizi görüntüleyebilir veya güncelleyebilirsiniz.</p>
    <div class="hesabimadresekle">
        <p>Adresler</p>
        <p><a href="index.php?sk=70"> + Yeni Adres Ekle</a></p>
    </div>
    <?php
    $adreslerSorgusu        =   $baglanti->prepare("SELECT * FROM adresler WHERE uyeId = ?");
    $adreslerSorgusu->execute([$kullaniciID]);
    $adreslerSayisi         =   $adreslerSorgusu->rowCount();
    $adreslerKayitlari      =   $adreslerSorgusu->fetchAll(PDO::FETCH_ASSOC);

    $birinciRenk   =  "#FFFFFF";
    $ikinciRenk    =  "#F1F1F1";
    $renkSayisi    =  1;

    if($adreslerSayisi>0){
        foreach($adreslerKayitlari as $satirlar){ 
            if($renkSayisi % 2){
                $arkaPlanRengi  =   $birinciRenk;
            }else{
                $arkaPlanRengi  =   $ikinciRenk;
            }
    ?>
    <div class="hesabimadreslerbilgicon" style="background-color: <?php echo $arkaPlanRengi; ?>">
        <div class="hesabimadreslerbilgisol" style="background-color: <?php echo $arkaPlanRengi; ?>">
            <p class="hesabimadreslerbilgiyazi"><?php echo $satirlar["adiSoyadi"]; ?> - <?php echo $satirlar["adres"]; ?> - <?php echo $satirlar["ilce"]; ?> / <?php echo $satirlar["sehir"]; ?> - <?php echo $satirlar["telefon"]; ?></p>
        </div>
        <div class="hesabimadreslerbilgisag" style="background-color: <?php echo $arkaPlanRengi; ?>">
            <div class="adresguncellesil"  style="background-color: <?php echo $arkaPlanRengi; ?>">
                <a href="index.php?sk=62&id=<?php echo $satirlar["id"]; ?>"><img src="resimler/Guncelleme20x20.png"></a>
                <p><a href="index.php?sk=62&id=<?php echo $satirlar["id"]; ?>">Güncelle</a></p>
            </div>
            <div class="adresguncellesil" style="background-color: <?php echo $arkaPlanRengi; ?>">
                <a href="index.php?sk=67&id=<?php echo $satirlar["id"]; ?>"><img src="resimler/Sil20x20.png"></a>
                <p><a href="index.php?sk=67&id=<?php echo $satirlar["id"]; ?>">Sil</a></p>
            </div>
        </div>
    </div>   
    <?php
            $renkSayisi++;
        }
    }else{
    ?>
        <p class="hesabimadreslerbilgiyazi">Sisteme kayıtlı adresiniz bulunmamaktadır.</p>
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