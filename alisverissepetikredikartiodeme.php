<?php
if(isset($_SESSION["kullanici"])){
    $sepettekiUrunlerSorgusu       =   $baglanti->prepare("SELECT * FROM sepet WHERE uyeId = ? ORDER BY id DESC ");
    $sepettekiUrunlerSorgusu->execute([$kullaniciID]);
    $sepettekiUrunSayisi           =   $sepettekiUrunlerSorgusu->rowCount();
    $sepetKayitlari                =   $sepettekiUrunlerSorgusu->fetchAll(PDO::FETCH_ASSOC);

    if($sepettekiUrunSayisi>0){
        $sepettekiToplamUrunSayisi      =   0;
        $sepettekiToplamFiyat           =   0;

        foreach($sepetKayitlari as $sepetSatirlari){ 
            $sepetIdsi                     =   $sepetSatirlari["id"];
            $sepetsepetNumarasi            =   $sepetSatirlari["sepetNumarasi"];
            $sepettekiUrununIdsi           =   $sepetSatirlari["urunId"];
            $sepettekiUrununVaryantIdsi    =   $sepetSatirlari["varyantId"];
            $sepettekiUrununAdedi          =   $sepetSatirlari["urunAdedi"];

            $urunBilgileriSorgusu          =   $baglanti->prepare("SELECT * FROM urunler WHERE id = ? LIMIT 1");
            $urunBilgileriSorgusu->execute([$sepettekiUrununIdsi]);
            $urunKaydi                     =   $urunBilgileriSorgusu->fetch(PDO::FETCH_ASSOC);
                $urununTuru             =    $urunKaydi["urunTuru"];
                $urununFiyati           =    $urunKaydi["urunFiyati"];
                $urununparaBirimi       =    $urunKaydi["paraBirimi"];
                $urununKargoUcreti      =    $urunKaydi["kargoUcreti"];

            if($urununTuru=="Erkek Ayakkabısı"){
                $urunResimleriKlasoru   =   "erkek";
            }elseif($urununTuru=="Kadın Ayakkabısı"){
                $urunResimleriKlasoru   =   "kadin";            
            }elseif($urununTuru=="Çocuk Ayakkabısı"){
                $urunResimleriKlasoru   =   "cocuk";            
            }         
            
            if($urununparaBirimi=="USD"){
                $urunFiyatiHesapla              =   $urununFiyati*$dolarKuru;
                $urunFiyatiBicimlendir          =   fiyatBicimlendir($urunFiyatiHesapla);
            }elseif($urununparaBirimi=="EUR"){
                $urunFiyatiHesapla      =   $urununFiyati*$euroKuru; 
                $urunFiyatiBicimlendir  =   fiyatBicimlendir($urunFiyatiHesapla);
            }else{
                $urunFiyatiHesapla      =   $urununFiyati;
                $urunFiyatiBicimlendir  =   fiyatBicimlendir($urununFiyati);
            }


            $urunToplamFiyatiHesapla        =   ($urunFiyatiHesapla*$sepettekiUrununAdedi);
            $urunToplamFiyatiBiçimlendir    =   fiyatBicimlendir($urunToplamFiyatiHesapla);

            $sepettekiToplamUrunSayisi      +=   $sepettekiUrununAdedi;
            $sepettekiToplamFiyat           +=   ($urunFiyatiHesapla*$sepettekiUrununAdedi);

            $sepettekiToplamKargoFiyati      =   $urununKargoUcreti; 
        }

        if($sepettekiToplamFiyat>=$ucretsizKargoBaraji){
            $sepettekiToplamKargoFiyati                =   0;
            $odenecekToplamTutariHesaplaBicimlendir    =   fiyatBicimlendir($sepettekiToplamFiyat);
        }else{
            $odenecekToplamTutariHesapla               =   ($sepettekiToplamFiyat+$sepettekiToplamKargoFiyati);
            $odenecekToplamTutariHesaplaBicimlendir    =   fiyatBicimlendir($odenecekToplamTutariHesapla);
        }

    $clientId		=	donusumleriGeriDondur($clientId);	//	Bankadan Sanal Pos Onaylanınca Bankanın Verdiği İşyeri Numarası
    $amount			=	$odenecekToplamTutariHesapla; 	//	Sepet Ücreti yada İşlem Tutarı yada Karttan Çekilecek Tutar
    $oid			=	$sepetsepetNumarasi;	//	Sipariş Numarası (Tekrarlanmayan Bir Değer) (Örneğin Sepet Tablosundaki IDyi Kullanabilirsiniz) (Her İşlemde Değişmeli ve Asla Tekrarlanmamalı)
    $okUrl			=	"http://www.redpen.site/alisverissepetikredikartiodemesonuctamam.php";	//	Ödeme İşlemi Başarıyla Gerçekleşir ise Dönülecek Sayfa
    $failUrl		=	"http://www.redpen.site/alisverissepetikredikartiodemehata.php";	//	Ödeme İşlemi Red Olur ise Dönülecek Sayfa
    $rnd			=	@microtime();
    $storekey		=	donusumleriGeriDondur($storeKey);	// Sanal Pos Onaylandığında Bankanın Size Verdiği Sanal Pos Ekranına Girerek Oluşturulacak Olan İş Yeri Anahtarı
    $storetype		=	"3d";	//	3D Modeli
    $hashstr		=	$clientId.$oid.$amount.$okUrl.$failUrl.$rnd.$storekey;	// Bankanın Kendi Ayarladığı Hash Parametresi
    $hash			=	@base64_encode(@pack('H*',@sha1($hashstr)));	// Bankanın Kendi Ayarladığı Hash Şifreleme Parametresi
    $description	=	"Ürün Satışı";	//	Extra Bir Açıklama Yazmak İsterseniz Çekim İle İlgili Buraya Yazıyoruz
    $xid			=	"";		//	20 bytelik, 28 Karakterli base64 Olarak Boş Bırılınca Sistem Tarafindan Ototmatik Üretilir. Lütfen Boş Bırakın
    $lang			=	"";		//	Çekim Gösterim Dili Default Türkçedir. Ayarlamak İsterseniz Türkçe (tr), İngilizce (en) Girilmelidir. Boş Bırakılırsa (tr) Kabu Edilmiş Olur.
    $email			=	"";	//	İsterseniz Çekimi Yapan Kullanıcınızın E-Mail Adresini Gönderebilirsiniz
    $userid			=	"";	//	İsterseniz Çekimi Yapan Kullanıcınızın Idsini Gönderebilirsiniz

?>
<form action="https://<sunucu_adresi>/<3dgate_path>" method="POST"> <!-- Bu Adres Banka veya EST Firması Tarafından Verilir -->
    <input type="hidden" name="clientid" value="<?=$clientId?>" />
	<input type="hidden" name="amount" value="<?=$amount?>" />
	<input type="hidden" name="oid" value="<?=$oid?>" />
	<input type="hidden" name="okUrl" value="<?=$okUrl?>" />
	<input type="hidden" name="failUrl" value="<?=$failUrl?>" />
	<input type="hidden" name="rnd" value="<?=$rnd?>" />
	<input type="hidden" name="hash" value="<?=$hash?>" />
	<input type="hidden" name="storetype" value="3d" />	
	<input type="hidden" name="lang" value="tr" />
    <div class="alisverissepeticon">
        <div class="alisverissepetisol">
            <h3>Alışveriş Sepeti</h3>
            <p class="havalebildirimyazi">Kredi Kartı bilgileriniz ile bu alandan ödeme yapabilirsiniz.</p>       
            <div class="kredikartibilgilericon">
                <div class="kredikartibilgileriblog"><p>Kredi Kartı Numarası</p> <input type="text" name="pan"></div>
                <div class="kredikartibilgileriblog"><p>Son Kullanma Tarihi</p><select name="Ecom_Payment_Card_ExpDate_Month">
                    <option value=""></option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <select name="Ecom_Payment_Card_ExpDate_Year" >
									<option value=""></option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									<option value="2023">2023</option>
									<option value="2024">2024</option>
									<option value="2025">2025</option>
									<option value="2026">2026</option>
									<option value="2027">2027</option>
									<option value="2028">2028</option>
                </select></div>
                <div class="kredikartibilgileriblog"><p>Kart Türü</p><input type="radio" value="1" name="cardType"> Visa <input type="radio" value="2" name="cardType" class="kartturumaster"> MasterCard</div>
                <div class="kredikartibilgileriblog"><p>Güvenlik Kodu</p><input type="text" name="cv2" size="4" value="" ></div>
                <input type="submit" value="Ödeme Yap" class="YesilButon" />
            </div>         
        </div>
        <div class="alisverissepetisag">
            <h3>Sipariş Özeti</h3>
            <p class="havalebildirimyazi">Toplam <span><?php echo $sepettekiToplamUrunSayisi; ?></span> Adet Ürün</p>
            <div class="alisverissepetisagodeme">
                <p>Ürünler Toplam Fiyat (KDV Dahil)</p>
                <p><?php echo fiyatBicimlendir($sepettekiToplamFiyat); ?> TL</p>
            </div>
            <div class="alisverissepetisagodeme">
                <p>Kargo Tutarı (KDV Dahil)</p>
                <p><?php echo fiyatBicimlendir($sepettekiToplamKargoFiyati ); ?> TL</p>               
            </div>
            <div class="alisverissepetisagodeme">
                <p>Ödenecek Tutar (KDV Dahil)</p>
                <p><?php echo $odenecekToplamTutariHesaplaBicimlendir; ?> TL</p>
            </div>
        </div>
    </div>
</form>
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