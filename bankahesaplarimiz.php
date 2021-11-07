
<div class="hakkimizdametni">
    <h2>BANKA HESAPLARIMIZ</h2>
    <p class="hakkimazdaaltyazi">Ödemeleriniz için çalışmakta olduğumuz tüm banka hesap bilgileri aşağıdadır.</p>
    <div class="bankacontainer">
        <div class="bankablogcon">
        <?php
        $bankalarSorgusu    =  $baglanti->prepare("SELECT * FROM bankahesaplarimiz");
        $bankalarSorgusu->execute();
        $bankaSayisi        =  $bankalarSorgusu->rowCount();
        $bankaKayitlari     =  $bankalarSorgusu->fetchAll(PDO::FETCH_ASSOC);
        
        $donguSayisi        =   1;
        $sutunAdetSayisi    =   3;
        foreach($bankaKayitlari as $kayit){
        ?>
            <div class="bankablog">
                <table>
                    <tr >
                        <th colspan="3"><img class="bankasatirlogo" src="resimler/<?php echo donusumleriGeriDondur($kayit["bankaLogosu"]); ?>"></th>
                    </tr>
                    <tr class="bankasatir">
                        <td>Banka Adı</td>
                        <td>:</td>
                        <td><?php echo donusumleriGeriDondur($kayit["bankaAdi"]); ?></td>
                    </tr>
                    <tr class="bankasatir">
                        <td>Konum</td>
                        <td>:</td>
                        <td><?php echo donusumleriGeriDondur($kayit["konumSehir"]); ?> / <?php echo donusumleriGeriDondur($kayit["konumUlke"]); ?></td>
                    </tr>
                    <tr class="bankasatir">
                        <td>Şube</td>
                        <td>:</td>
                        <td><?php echo donusumleriGeriDondur($kayit["subeAdi"]); ?> / <?php echo donusumleriGeriDondur($kayit["subeKodu"]); ?></td>
                    </tr>
                    <tr class="bankasatir">
                        <td>Birim</td>
                        <td>:</td>
                        <td><?php echo donusumleriGeriDondur($kayit["paraBirimi"]); ?></td>
                    </tr>
                    <tr class="bankasatir">
                        <td>Hesap Adı</td>
                        <td>:</td>
                        <td><?php echo donusumleriGeriDondur($kayit["hesapSahibi"]); ?></td>
                    </tr>
                    <tr class="bankasatir">
                        <td>Hesap No</td>
                        <td>:</td>
                        <td><?php echo donusumleriGeriDondur($kayit["hesapNumarasi"]); ?></td>
                    </tr>
                    <tr class="bankasatir">
                        <td>IBAN No</td>
                        <td>:</td>
                        <td><?php echo IbanBicimlendir(donusumleriGeriDondur($kayit["IbanNumarasi"])); ?></td>
                    </tr>
                </table>
            </div>            
        <?php
        $donguSayisi++;
        if($donguSayisi>$sutunAdetSayisi){
        ?>
        </div>
        <div class="bankablogcon">
        <?php
            $donguSayisi =1;
        }
        }
        ?>
        </div>  
    </div>   
</div>
