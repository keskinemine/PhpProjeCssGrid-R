<?php
if(isset($_SESSION["kullanici"])){
    if(isset($_GET["urunId"])){
        $gelenurunId     =   guvenlik($_GET["urunId"]);
    }else{
        $gelenurunId     =    "";
    }
    if($gelenurunId!=""){ 
?>
<div class="havelebildirimcon">
        <div class="havalebildirimsol">
            <h3>Hesabım > Yorum Yap</h3>
            <p class="havalebildirimyazi">Satın almış olduğunuz ürün ile alakalı aşağıdan yorumunuzu belirtebilirsiniz.</p>
            <div class="yorumyapformu">
                <form action="index.php?sk=76&urunId=<?php echo $gelenurunId; ?>" method="post">
                    <p>Puanlama (*)</p>
                    <div class="yorumpuanlamaresimblogcon">
                        <div class="yorumpuanlamaresimblog">
                            <img src="resimler/YildizBirDolu.png">
                            <input type="radio" name="yorumpuan" value="1">
                        </div>
                        <div class="yorumpuanlamaresimblog">
                            <img src="resimler/YildizIkiDolu.png">
                            <input type="radio" name="yorumpuan" value="2">
                        </div>
                        <div class="yorumpuanlamaresimblog">
                            <img src="resimler/YildizUcDolu.png">
                            <input type="radio" name="yorumpuan" value="3">
                        </div>
                        <div class="yorumpuanlamaresimblog">
                            <img src="resimler/YildizDortDolu.png">
                            <input type="radio" name="yorumpuan" value="4">
                        </div>
                        <div class="yorumpuanlamaresimblog">
                            <img src="resimler/YildizBesDolu.png">
                            <input type="radio" name="yorumpuan" value="5">
                        </div>
                    </div> 
                   <label for="sifre">Yorum Metni (*)</label>
                    <textarea name="yorum" cols="30" rows="12"></textarea>                  
                    <input type="submit" value="Yorumu Gönder">    
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
        header("Location:index.php?sk=78");
        exit();
    }
}else{
    header("Location:index.php");
    exit();
}
?>