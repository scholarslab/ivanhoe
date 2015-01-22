$( function() {

    if ( $.cookie( 'styleClass' ) ) {
        var newClass = $.cookie( 'styleClass' );
        $('body').attr( 'class', newClass );
    }

});
