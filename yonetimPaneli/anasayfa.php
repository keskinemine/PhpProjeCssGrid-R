<?php
if(isset($_SESSION["yonetici"])){ 
?>
<div id="header">
    <div class="panelcon">
        <div class="solpanel">
            <p><a href="index.php?skD=0&skI=0"><img src="../resimler/Logo.png"></a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">SİPARİŞLER (X/X)</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">HAVALE BİLDİRİMLERİ (X/X)</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">ÜRÜNLER</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">ÜYELER</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">YORUMLAR</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">SİTE AYARLARI</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">MENÜLER</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">BANKA HESAP AYARLARI</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">SÖZLEŞMELER VE METİNLER</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">KARGO AYARLARI</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">BANNER AYARLARI</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">DESTEK İÇERİKLERİ</a></p>
            <p><a href="index.php?skD=0&skI=xxxxx">YÖNETİCİLER</a></p>
        </div>
        <div class="sagpanel">
            <?php
            if((!$icsayfaKoduDegeri) or ($icsayfaKoduDegeri=="") or ($icsayfaKoduDegeri==0)){
                include($sayfaIc[0]);
            }else{
                include($sayfaIc[$icsayfaKoduDegeri]);
            }
            ?> 
        </div>
    </div>
</div>

<?php
}else{
    header("Location:index.php?skD=1");
    exit();
}
?> 



