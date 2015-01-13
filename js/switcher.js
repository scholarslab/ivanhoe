$(function(){



function styleSwitch(){
    var letter = $(this).attr('id');
    if ($("#switch").attr("href") !== "css/"+ letter + ".css"){
    
    $("body").fadeOut(1500);
    setTimeout(function(){
        /**change stylesheet to the one corresponding to hovered letter**/
        $("#switch").attr("href", "css/"+ letter + ".css");
        $("body").fadeIn(1500);
    }, 1500);
    
    }
}


$('.titleLetter').hover(styleSwitch, false,500);



});