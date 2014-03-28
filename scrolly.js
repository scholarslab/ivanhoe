var $w = $(window);

var refigure = function() {

    $scrollTop = $w.scrollTop();

    var $slow = $scrollTop / 3;
    var $position = '-5% -'+ $slow + 'px';

    $('html').css(
        { 
            backgroundPosition:$position
        }
    );

};

refigure();

$w.scroll(refigure);

