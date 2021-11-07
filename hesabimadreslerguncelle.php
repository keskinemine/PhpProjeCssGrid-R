<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["id"])){
        $gelenid     =   guvenlik($_GET["id"]);
    }else{
        $gelenid     =    "";
    }

    if($gelenid!=""){
        $adresSorgusu      =   $baglanti->prepare("SELECT * FROM adresler WHERE id =  ? AND uyeId = ? LIMIT 1");
        $adresSorgusu->execute([$gelenid, $kullaniciID]);
        $adresSayisi       =   $adresSorgusu->rowCount();
        $kayitBilgisi      =   $adresSorgusu->fetch(PDO::FETCH_ASSOC);

        if($adresSayisi>0){ 
?>
            <div class="havelebildirimcon">
                <div class="havalebildirimsol">
                    <h3>Hesabım > Adresler</h3>
                    <p class="havalebildirimyazi">Tüm adreslerinizi görüntüleyebilir veya güncelleyebilirsiniz.</p>
                    <div class="havaleformu">
                        <form action="index.php?sk=63&id=<?php echo $gelenid; ?>" method="post">
                            <label for="isimSoyisim">İsim Soyisim (*)</label>
                            <input type="text" name="isimSoyisim" value="<?php echo $kayitBilgisi["adiSoyadi"] ; ?>">  
                            <label for="adres">Adres (*)</label>
                            <input type="text" name="adres" value="<?php echo $kayitBilgisi["adres"] ; ?>">
                            <label for="ilce">İlçe (*)</label>
                            <input type="text" name="ilce" value="<?php echo $kayitBilgisi["ilce"] ; ?>">
                            <label for="sehir">Şehir (*)</label>
                            <input type="text" name="sehir" value="<?php echo $kayitBilgisi["sehir"] ; ?>">
                            <label for="telefon">Telefon Numarası (*)</label>
                            <input type="text" name="telefon" maxlength="11" value="<?php echo $kayitBilgisi["telefon"] ; ?>">
                            <input type="submit" value="Adresi Güncelle">    
                        </form>
                    </div>
                </div>
                <div class="havalebildirimsag">
                    <h3>Reklam</h3>
                    <p class="havalebildirimyazi">Redpen.site Reklamları</p>
                    <div class="reklamalani">
                        <p>545 X 371</p>
                        <p>Reklam Alanı</p>
                    </div>
                </div>
            </div>
<?php
        }else{
            header("Location:index.php?sk=65");
            exit();
        }
    }else{
        header("Location:index.php?sk=65");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>