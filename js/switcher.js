$(function(){



function styleSwitch(){
    var letter = $(this).attr('id');
    if ($("#switch").attr("href") !== "css/"+ letter + ".css"){
    $("body").fadeOut(1000);
    setTimeout(function(){
        $("#switch").attr("href", "css/"+ letter + ".css");
        $("body").fadeIn(1000);
    }, 1000);
    
    }
}


$('.titleLetter').hover(styleSwitch, false,500);



});