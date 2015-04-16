// parent move toggles on click 

$(function(){

    $(".parent-title").click(function(){
        $(this).next().toggle();
    });

});