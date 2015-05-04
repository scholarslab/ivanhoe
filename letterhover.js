
/**
 * Load Ivanhoe module
 */
var ivanhoe = (function() {
  "use strict";

  var heading           = null;
  var characters        = null;
  var refreshIntervalId = null;

  var letters = {

    init: function(element){
      heading = element;
      letters.copy();
      $(heading).hover(letters.animate, letters.pause);
    },
    copy: function() {
      characters = $(heading).text().split("");
      heading.empty();

      $.each(characters, function(i, el) {
        var l = $(heading).append("<span class='letters_" + i + "'>" + el + '</span>');
      });
    },
    pause: function() {
      clearInterval(refreshIntervalId);
    },
    style: function() {
      $.each(characters, function(i) {
        var s = $(".letters_" + i);
        s.css('color', letters.randomColor());
        s.css('font-family', letters.randomFont());
      });
    },
    animate: function() {
       refreshIntervalId = setInterval(letters.style, 250);
    },
    randomFont: function() {
      var randomElement = Math.floor(Math.random() * WebFontConfig.google.families.length);
      return letters.cleanFont(WebFontConfig.google.families[randomElement]);
    },
    cleanFont: function(font) {
      return font.replace('+', ' ');
    },
    randomColor: function() {
      return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }
  };

  $(document).ready(function() {
    letters.init($('#banner h1 a'));
  });


})();
