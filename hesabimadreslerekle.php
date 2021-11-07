<?php
if(isset($_SESSION["kullanici"])){
?>
<div class="havelebildirimcon">
        <div class="havalebildirimsol">
            <h3>Hesabım > Adresler</h3>
            <p class="havalebildirimyazi">Adres kaydı için lütfen gerekli alanları doldurunuz.</p>
            <div class="havaleformu">
                <form action="index.php?sk=71" method="post">
                    <label for="isimSoyisim">İsim Soyisim (*)</label>
                    <input type="text" name="isimSoyisim">  
                    <label for="adres">Adres (*)</label>
                    <input type="text" name="adres">
                    <label for="ilce">İlçe (*)</label>
                    <input type="text" name="ilce">
                    <label for="sehir">Şehir (*)</label>
                    <input type="text" name="sehir">
                    <label for="telefon">Telefon Numarası (*)</label>
                    <input type="number" name="telefon" maxlength="11">
                    <input type="submit" value="Adresi Kaydet">    
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
    header("Location:index.php");
    exit();
}
?>