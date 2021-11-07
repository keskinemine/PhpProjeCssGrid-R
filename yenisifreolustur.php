<?php

if(isset($_GET["aktivasyonKodu"])){
    $gelenaktivasyonKodu      =   guvenlik($_GET["aktivasyonKodu"]);
}else{
    $gelenaktivasyonKodu      =    "";
}
if(isset($_GET["email"])){
    $gelenemail               =   guvenlik($_GET["email"]);
}else{
    $gelenemail               =    "";
}

if(($gelenaktivasyonKodu!="") and ($gelenemail!="")){
    $kontrolSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi = ? and aktivasyonKodu = ?");
    $kontrolSorgusu->execute([$gelenemail, $gelenaktivasyonKodu]);
    $kullaniciSayisi         =   $kontrolSorgusu->rowCount();
    $kullaniciKaydi          =   $kontrolSorgusu->fetch(PDO::FETCH_ASSOC);

    if($kullaniciSayisi>0){
?>
<div class="havelebildirimcon">
    <div class="havalebildirimsol">
        <h3>Şifre Sıfırlama</h3>
        <p class="havalebildirimyazi">Aşağıdan Hesabınıza Giriş Şifrenizi Değiştirebilirsiniz.</p>
        <div class="havaleformu">
            <form action="index.php?sk=44&emailAdresi=<?php echo $gelenemail;?>&aktivasyonKodu=<?php echo $gelenaktivasyonKodu;?>" method="POST">
                <label for="emailAdresi">Yeni Şifre (*)</label>
                <input type="password" name="sifre">
                <label for="sifreTekrar">Yeni Şifre Tekrar (*)</label>
                <input type="password" name="sifreTekrar">
                <input type="submit" value="Şifremi Güncelle">    
            </form>
        </div>
    </div>
    <div class="havalebildirimsag">
        <h3>Yeni Şifre Oluşturma</h3>
        <p class="havalebildirimyazi">Çalışma ve İşleyiş Açıklaması</p>
        <div class="havaleresimblog">
            <img src="resimler/CarklarSiyah20x20.png">
            <p>OBilgi Kontrolü</p>
        </div>
        <p>Kullanıcının form alanına girmiş olduğu değer veya değerler veritabanımızda tam detaylı olarak filtrelenerek kontrol edilir.</p>
        <div class="havaleresimblog">
            <img src="resimler/CarklarSiyah20x20.png">
            <p>E-mail Gönderimi & İçerik</p>
        </div>
        <p>Bilgi kontrolü başarılı olur ise, kullanıcının veritabanımızda kayıtlı olan e-mail adresine yeni şifre oluşturma içerikli bir mail gönderilir.</p>
        <div class="havaleresimblog">
            <img src="resimler/CarklarSiyah20x20.png">
            <p>Şifre Sıfırlama & Oluşturma</p>
        </div>
        <p>Kullanıcı, kendisine iletilen mail içerisindeki "Yeni Şifre Oluştur" metnine tıklayınca, site yeni şifre oluşturma sayfası açılır ve kullanıcıdan, yeni hesap şifresini oluşturması istenir.</p>
        <div class="havaleresimblog">
            <img src="resimler/CarklarSiyah20x20.png">
            <p>Sonuç</p>
        </div>
        <p>Kullanıcı yeni oluşturmuş olduğu hesap şifresi ile siteye giriş yapmaya hazırdır.</p>            
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
