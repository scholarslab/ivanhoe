$( function() {

    $('.titleLetter').click( function () {
        var letter = $(this).attr('id');
        var newClass = letter  + '-style';
        $('body').attr( 'class', newClass );
        $.cookie( 'styleClass', newClass, { expires: 7, path: '/' } );
    });

});
