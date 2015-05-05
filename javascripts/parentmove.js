// parent move toggles on click 

$(function(){
    
    $(".parent-title").prepend("<span class = 'parent-btn' >+</span>");
    $(".parent-title").click(function(){
        $(this).next().toggle();
    });

});