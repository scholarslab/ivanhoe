"use strict";

var fontsReady = false;
var starting = false;

var WebFontConfig = {
  google: { families: [ 'Crimson+Text::latin', 'Raleway::latin', 'Kite+One::latin', "Londrina+Solid",  "Lily+Script+One", "Fascinate+Inline", "Monsieur+La+Doulaise", "Life+Savers" ] },
  active: function() {
    console.log("All fonts are loaded");
    fontsReady = true;
  }
};

(function() {
  var wf = document.createElement('script');
  wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
  wf.type = 'text/javascript';
  wf.async = 'true';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(wf, s);
})();
