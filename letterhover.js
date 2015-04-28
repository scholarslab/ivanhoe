function getFont() {
    var fonts = [
        'open-sans',
        'slabo',
        'crimson-text',
        'pt-sans',
        'raleway',
        'roboto-slab',
        'bitter',
        'dosis'
    ];

    return fonts[Math.floor(Math.random() * fonts.length)];
}

var heading = $('#banner h1 a');
var characters = $(heading).text().split("");
$(heading).empty();

$.each(characters, function (i, el) {
    $(heading).append("<span class='letter'>" + el + "</span");
});

letterCycle = null;

function changeFont() {
    var letters = $('#banner h1 span');
    $.each(letters, function(i, el) {
        $(el).removeClass().addClass(getFont());
    });

}

$(heading).hover(
    function() {
        letterCycle = setInterval(function(){
            changeFont();
        }, 250);
    },
    function() {
        $(this).find('span').removeClass();
        clearInterval(letterCycle);
    }
);

