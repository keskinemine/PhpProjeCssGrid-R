<?php
if(isset($_SESSION["yonetici"])){ 
?>

dashboard / pano sayfası

<?php
}else{
    header("Location:index.php?skD=1");
    exit();
}
?> 