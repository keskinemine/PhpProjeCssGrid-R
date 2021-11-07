<?php
try{
    $baglanti  =  new PDO("mysql:host=localhost;dbname=redpen_anket;charset=UTF8", "redpen_redpen", "xi6ir7xl");
}catch(PDOException $e){
    //echo "Bağlantı hatası <br>" . $e->getMessage(); //Bu alanı kapatın çünkü site hata yaparsa kullanıcılar hata değerini görmesin.
    die();
}

$ayarSorgusu    =   $baglanti->prepare("SELECT * FROM ayarlar LIMIT 1");
$ayarSorgusu->execute();
$ayarSayisi     =   $ayarSorgusu->rowCount();
$ayarlar        =   $ayarSorgusu->fetch(PDO::FETCH_ASSOC);

if($ayarSayisi>0){
    $siteAdi                =   $ayarlar["siteAdi"];
    $siteTitle              =   $ayarlar["siteTitle"];
    $siteDescription        =   $ayarlar["siteDescription"];
    $siteKeywords           =   $ayarlar["siteKeywords"];
    $siteCopyrightMetni     =   $ayarlar["siteCopyrightMetni"];
    $siteLogosu             =   $ayarlar["siteLogosu"];
    $siteLinki              =   $ayarlar["siteLinki"];
    $siteEmailAdresi        =   $ayarlar["siteEmailAdresi"];
    $siteEmailSifresi       =   $ayarlar["siteEmailSifresi"];
    $siteEmailHostAdresi    =   $ayarlar["siteEmailHostAdresi"];
    $sosyalLinkInstagram    =   $ayarlar["sosyalLinkInstagram"];
    $sosyalLinkFacebook     =   $ayarlar["sosyalLinkFacebook"];
    $sosyalLinkYoutube      =   $ayarlar["sosyalLinkYoutube"];
    $sosyalLinkTwitter      =   $ayarlar["sosyalLinkTwitter"];
    $sosyalLinkLinkedin     =   $ayarlar["sosyalLinkLinkedin"];
    $sosyalLinkPinterest    =   $ayarlar["sosyalLinkPinterest"];
    $dolarKuru              =   $ayarlar["dolarKuru"];
    $euroKuru               =   $ayarlar["euroKuru"];
    $ucretsizKargoBaraji    =   $ayarlar["ucretsizKargoBaraji"];
    $clientId               =   $ayarlar["clientId"];
    $storeKey               =   $ayarlar["storeKey"];
    $ApiKullanicisi         =   $ayarlar["ApiKullanicisi"];
    $ApiSifresi             =   $ayarlar["ApiSifresi"];
}else{
    //echo "Site ayar sorgusu hatalı <br>"; //Bu alanı kapatın çünkü site hata yaparsa kullanıcılar hata değerini görmesin.
    die();
}

$metinSorgusu    =   $baglanti->prepare("SELECT * FROM sozlesmelervemetinler LIMIT 1");
$metinSorgusu->execute();
$metinSayisi     =   $metinSorgusu->rowCount();
$metinler        =   $metinSorgusu->fetch(PDO::FETCH_ASSOC);

if($metinSayisi>0){
    $hakkimizdaMetni                 =   $metinler["hakkimizdaMetni"];
    $uyelikSozlesmesiMetni           =   $metinler["uyelikSozlesmesiMetni"];
    $kullanimKosullariMetni          =   $metinler["kullanimKosullariMetni"];
    $gizlilikSozlesmesiMetni         =   $metinler["gizlilikSozlesmesiMetni"];
    $mesafeliSatisSozlesmesiMetni    =   $metinler["mesafeliSatisSozlesmesiMetni"];
    $teslimatMetni                   =   $metinler["teslimatMetni"];
    $iptalIadeDegisimMetni           =   $metinler["iptalIadeDegisimMetni"];
}else{
    //echo "Site metinler sorgusu hatalı <br>"; //Bu alanı kapatın çünkü site hata yaparsa kullanıcılar hata değerini görmesin.
    die();
}

if(isset($_SESSION["kullanici"])){ 
    $kullaniciSorgusu          =   $baglanti->prepare("SELECT * FROM uyeler WHERE emailAdresi =  ? LIMIT 1");
    $kullaniciSorgusu->execute([$_SESSION["kullanici"]]);
    $kullaniciSayisi           =   $kullaniciSorgusu->rowCount();
    $kullanici                 =   $kullaniciSorgusu->fetch(PDO::FETCH_ASSOC);

    if($kullaniciSayisi>0){
        $kullaniciID           =   $kullanici["id"];
        $emailAdresi           =   $kullanici["emailAdresi"];
        $sifre                 =   $kullanici["sifre"];
        $isimSoyisim           =   $kullanici["isimSoyisim"];
        $telefonNumarasi       =   $kullanici["telefonNumarasi"];
        $cinsiyet              =   $kullanici["cinsiyet"];
        $durumu                =   $kullanici["durumu"];
        $kayitTarihi           =   $kullanici["kayitTarihi"];
        $kayitIPAdresi         =   $kullanici["kayitIPAdresi"];
        $aktivasyonKodu        =   $kullanici["aktivasyonKodu"];
    }else{
        //echo "Kullancı sorgusu hatalı <br>"; //Bu alanı kapatın çünkü site hata yaparsa kullanıcılar hata değerini görmesin.
        die();
    }
}

if(isset($_SESSION["yonetici"])){ 
    $yoneticiSorgusu            =   $baglanti->prepare("SELECT * FROM yoneticiler WHERE kullaniciAdi =  ? LIMIT 1");
    $yoneticiSorgusu->execute([$_SESSION["yonetici"]]);
    $yoneticiSayisi             =   $yoneticiSorgusu->rowCount();
    $yonetici                   =   $yoneticiSorgusu->fetch(PDO::FETCH_ASSOC);

    if($yoneticiSayisi>0){
        $yoneticiID             =   $yonetici["id"];
        $yoneticikullaniciAdi   =   $yonetici["kullaniciAdi"];
        $yoneticisifre          =   $yonetici["sifre"];
        $yoneticiisimSoyisim    =   $yonetici["isimSoyisim"];
        $yoneticiemailAdresi    =   $yonetici["emailAdresi"];
        $cinsiyettelefon        =   $yonetici["telefon"];
    }else{
        //echo "Yönetici sorgusu hatalı <br>"; //Bu alanı kapatın çünkü site hata yaparsa kullanıcılar hata değerini görmesin.
        die();
    }
}
?>