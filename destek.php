<div class="hakkimizdametni">
    <h2>SIK SORULAN SORULAR</h2>
    <p class="hakkimazdaaltyazisoru">Sık sorulan soruları aşağıda bulabilirsiniz. Farklı bir konuda aklınıza takılanları iletişim alanı aracıylığıyla iletebilirsiniz.</p>
    <?php
    $sorularSorgusu         =   $baglanti->prepare("SELECT * FROM sorular");
    $sorularSorgusu->execute();
    $soruSayisi             =   $sorularSorgusu->rowCount();
    $soruKayitlari          =   $sorularSorgusu->fetchAll(PDO::FETCH_ASSOC);

    foreach($soruKayitlari as $kayitlar){
    ?>
    <div class="soruCevapcon">
        <div id="<?php echo $kayitlar["id"]; ?>" class="soruBaslikAlan" onClick="$.soruIcerigiGoster(<?php echo $kayitlar["id"]; ?>)"><?php echo $kayitlar["soru"]; ?></div>
        <div class="soruCevapAlan"><?php echo $kayitlar["cevap"]; ?></div>
    </div>
    <?php
    }
    ?>
</div>

