<?php
if(empty($_SESSION["yonetici"])){ 
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
<form action="index.php?skD=2" method="POST">
    <div class="yoneticigiris">
        <label for="ykullanici">Yönetici Kullacı Adı</label>
        <input type="text" placeholder="Kullanıcı Adı" name="ykullanici" required>
        <label for="ysifre">Yönetici Şifresi</label>
        <input type="password" placeholder="Şifre" name="ysifre" required>
        <input type="submit" value="Giriş Yap">  
    </div>
</form>
</body>
</html>
<?php
}
?>