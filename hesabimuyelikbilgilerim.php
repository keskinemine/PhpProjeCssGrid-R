<?php
if(isset($_SESSION["kullanici"])){
?>
    <div class="hesabimnav">
        <ul>
            <li><a href="index.php?sk=50">Üyelik</a></li>
            <li><a href="index.php?sk=58">Adresler</a></li>
            <li><a href="index.php?sk=59">Favoriler</a></li>
            <li><a href="index.php?sk=60">Yorumlar</a></li>
            <li><a href="index.php?sk=61">Siparişler</a></li>
        </ul>
    </div>
<div class="havelebildirimcon">
    <div class="havalebildirimsol">
        <h3>Hesabım > Üyelik Bilgileri</h3>
        <p class="havalebildirimyazi">Aşağıdan üyelik bilgilerinizi görüntüleyebilir veya güncelleyebilirsiniz.</p>
        <div class="havaleformu">
            <p><b>İsim Soyisim</b></p>
            <p class="uyelikbilgileri"><?php echo $isimSoyisim; ?></p>
            <p><b>E-mail Adresi</b></p>
            <p class="uyelikbilgileri"><?php echo $emailAdresi; ?></p>
            <p><b>Telefon Numarası</b></p>
            <p class="uyelikbilgileri"><?php echo $telefonNumarasi; ?></p>
            <p><b>Cinsiyet</b></p>
            <p class="uyelikbilgileri"><?php echo $cinsiyet; ?></p>
            <p><b>Kayıt Tarihi</b></p>
            <p class="uyelikbilgileri"><?php echo tarihBul($kayitTarihi); ?></p>
            <p><b>Kayıt IP</b></p>
            <p><?php echo $kayitIPAdresi; ?></p>
            <p class="bilgilerimiGüncelleButonu"><a href="index.php?sk=51">Bilgilerimi Güncelle</a></p>
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