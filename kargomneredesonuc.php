<?php
if(isset($_POST["kargoTakipNumara"])){
    $gelenkargoTakipNumara     =   sayiliIcerikleriFiltrele(guvenlik($_POST["kargoTakipNumara"]));
}else{
    $gelenkargoTakipNumara      =    "";
}

if($gelenkargoTakipNumara!=""){
    header("Location:https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code=" . $gelenkargoTakipNumara);
    exit();
}else{
    header("Location:index.php?sk=14");
    exit();
}
?>