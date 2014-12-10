$(function(){

function testAlert() {
    alert('uuuugh');
}


function styleSwitch(){
    $("body").fadeOut(1000);
    var letter = $(this).attr('id');
    setTimeout(function(){
        $("#switch").attr("href", "css/"+ letter + ".css");
        $("body").fadeIn(1000);
    }, 1000);
}


$('.titleLetter').hover(styleSwitch, false,500);



});