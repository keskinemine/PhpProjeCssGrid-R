<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


session_start(); ob_start();
require_once("../ayarlar/ayar.php");
require_once("../ayarlar/fonksiyonlar.php");
require_once("../ayarlar/yonetimsayfalariic.php");
require_once("../ayarlar/yonetimsayfalaridis.php");

if(isset($_REQUEST["skD"])){
    $dissayfaKoduDegeri    =   sayiliIcerikleriFiltrele($_REQUEST["skD"]);
}else{
    $dissayfaKoduDegeri    =   0;
}

if(isset($_REQUEST["skI"])){
    $icsayfaKoduDegeri    =   sayiliIcerikleriFiltrele($_REQUEST["skI"]);
}else{
    $icsayfaKoduDegeri    =   0;
}

if(isset($_REQUEST["syf"])){
    $sayfalama    =   sayiliIcerikleriFiltrele($_REQUEST["syf"]);
}else{
    $sayfalama    =   1;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="googlebot" content="noindex, nofollow, noarchive">
    <title><?php echo donusumleriGeriDondur($siteTitle); ?></title>
    <link type="image/png" rel="icon" href="../resimler/Favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../ayarlar/styleyonetim.css">
    <script type="text/javascript" src="../frameworks/jQuery/jquery-3.6.0.min.js" language="javascript"></script>
    <script type="text/javascript" src="../ayarlar/fonksiyonlar.js" language="javascript"></script>
</head>
<body>
<div id="header">
    <?php
        if(empty($_SESSION["yonetici"])){
            if((!$dissayfaKoduDegeri) or ($dissayfaKoduDegeri=="") or ($dissayfaKoduDegeri==0)){
                include($sayfaDis[1]);
            }else{
                include($sayfaDis[$dissayfaKoduDegeri]);
            }
        }else{ 
            if((!$dissayfaKoduDegeri) or ($dissayfaKoduDegeri=="") or ($dissayfaKoduDegeri==0)){
                include($sayfaDis[0]);
            }else{
                include($sayfaDis[$dissayfaKoduDegeri]);
            }
        }
    ?> 
</div>    
</body>
</html>

<?php
$baglanti   =   null;
ob_end_flush();
?>