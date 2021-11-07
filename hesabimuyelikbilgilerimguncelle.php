<?php
if(isset($_SESSION["kullanici"])){
?>
<div class="havelebildirimcon">
        <div class="havalebildirimsol">
            <h3>Hesabım > Üyelik Bilgileri</h3>
            <p class="havalebildirimyazi">Aşağıdan üyelik bilgilerinizi görüntüleyebilir veya güncelleyebilirsiniz.</p>
            <div class="havaleformu">
                <form action="index.php?sk=52" method="post">
                    <label for="emailAdresi">E-mail Adresi (*)</label>
                    <input type="email" name="emailAdresi" value="<?php echo $emailAdresi; ?>">
                    <label for="sifre">Şifre (*)</label>
                    <input type="password" name="sifre" value="eskiSifre">
                    <label for="sifre">Şifre Tekrar (*)</label>
                    <input type="password" name="sifreTekrar"value="eskiSifre">
                    <label for="isimSoyisim">İsim Soyisim (*)</label>
                    <input type="text" name="isimSoyisim" value="<?php echo $isimSoyisim; ?>">                  
                    <label for="telefon">Telefon Numarası (*)</label>
                    <input type="number" name="telefon" maxlength="11" value="<?php echo $telefonNumarasi; ?>">
                    <label for="cinsiyet">Cinsiyet (*)</label>
                    <select name="cinsiyet">
                        <option value="Erkek" <?php if($cinsiyet =="Erkek"){ ?> selected="selected" <?php }?>>Erkek</option>
                        <option value="Kadın" <?php if($cinsiyet =="Kadın"){ ?> selected="selected" <?php }?>>Kadın</option>                   
                    </select>
                    <input type="submit" value="Bilgilerimi Güncelle">    
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