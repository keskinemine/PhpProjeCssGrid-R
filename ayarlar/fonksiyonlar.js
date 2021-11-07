$(document).ready(function(){

    $.soruIcerigiGoster         =   function(elemanID){

        var soruID              =   elemanID;
        var islenecekAlan       =   "#" + elemanID;

        $(".soruCevapAlan").slideUp();
        $(islenecekAlan).parent().find(".soruCevapAlan").slideToggle();
    }

    $.urunDetayResmiDegistir    =   function(klasor, resimDegeri){
        var resimIcinDosyaYolu  =   "resimler/urunResimleri/" + klasor + "/" +resimDegeri;
        $("#buyukResim").attr("src", resimIcinDosyaYolu);
    }

    $.KrediKartiSecildi         =   function(){
        $(".bankahavalesicon").css("display", "none");
        $(".kredikarticon").css("display", "block");
    }
    $.BankaHavalesiSecildi         =   function(){
        $(".kredikarticon").css("display", "none");
        $(".bankahavalesicon").css("display", "block");
    }
});