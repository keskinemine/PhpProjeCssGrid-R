<div class="havelebildirimcon">
    <div class="havalebildirimsol">
        <h3>Havale Bildirim Formu</h3>
        <p class="havalebildirimyazi">Tamamlanmış olan ödeme işlemlerinizi aşağıdaki formdan iletiniz.</p>
        <div class="havaleformu">
            <form action="index.php?sk=10" method="POST">
                <label for="isimSoyisim">İsim Soyisim (*)</label>
                <input type="text" name="isimSoyisim">
                <label for="emailAdresi">E-mail Adresi (*)</label>
                <input type="email" name="emailAdresi">
                <label for="telefon">Telefon Numarası (*)</label>
                <input type="number" name="telefon" maxlength="11">
                <label for="banka">Ödeme Yapılan Banka (*)</label>
                <select name="bankaSecimi">
                    <?php                        
                    $bankalarSorgusu    =   $baglanti->prepare("SELECT * FROM bankahesaplarimiz ORDER BY bankaAdi ASC");
                    $bankalarSorgusu->execute();
                    $bankaSayisi     =   $bankalarSorgusu->rowCount();
                    $bankaKayitlari       =   $bankalarSorgusu->fetchAll(PDO::FETCH_ASSOC);

                    foreach($bankaKayitlari as $bankalar){ 
                    ?>
                    <option value="<?php echo donusumleriGeriDondur($bankalar["id"]); ?>"><?php echo donusumleriGeriDondur($bankalar["bankaAdi"]); ?></option>
                    <?php
                    }
                    ?>
                </select> 
                <label for="telefon">Açıklama</label>
                <textarea name="aciklama" rows="9"></textarea>
                <input type="submit" value="Bildirimi Gönder">
            </form>
        </div>
    </div>
    <div class="havalebildirimsag">
        <h3>İşleyiş</h3>
        <p class="havalebildirimyazi">Havale / EFT İşlemlerinin Kontrolü</p>
        <div class="havaleresimblog">
            <img src="resimler/Banka20x20.png">
            <p>Havale / EFT İşlemi</p>
        </div>
        <p>Müşteri tarafından öncelikle banka hesaplarımız sayfasında bulunan herhangi bir hesaba ödeme işlemi gerçekleştirilir.</p>
        <div class="havaleresimblog">
            <img src="resimler/DokumanKirmiziKalemli20x20.png">
            <p>Bildirim İşlemi</p>
        </div>
        <p>Ödeme işleminizi tamamladıktan sonra "Havale Bildirim Formu" sayfasından müşteri yapmış olduğu ödeme için bildirim formunu doldurarak online olarak gönderir.</p>
        <div class="havaleresimblog">
            <img src="resimler/CarklarSiyah20x20.png">
            <p>Kontroller</p>
        </div>
        <p>"Havale Bildirim Formu"nuz tarafımıza ulaştığı anda ilgili departman tarafından yapmış olduğunuz havale / EFT işlemi ilgili banka üzerinden kontrol edilir.</p>
        <div class="havaleresimblog">
            <img src="resimler/InsanlarSiyah20x20.png">
            <p>Onay / Red</p>
        </div>
        <p>Havale bildirimi geçerli ise yani hesaba ödeme geçmiş ise, yönetici ilgili ödeme onayını vererek, siparişiniz teslimat birimine iletilir.</p>
        <div class="havaleresimblog">
            <img src="resimler/SaatEsnetikGri20x20.png">
            <p>Sipariş Hazırlama & Teslimat</p>
        </div>
        <p>Yönetici ödeme onayından sonra sayfamız üzerinden vermiş olduğunuz sipariş en kısa sürede hazırlanarak kargoya teslim edilir ve tarafınıza ulaştılır...</p>
    </div>
</div>
