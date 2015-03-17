<?php

/**
* Anchor builder helper
*
* @param $url
* @param $link_text
* @param $css_options string e.x. "class = 'btn'"
* @param $escape string specifies which variables to escape
*
* @return html anchor tags with css and link text
*/

if (!defined('ESCAPE_NONE')) {
    define('ESCAPE_NONE', 0);
}
if (!defined('ESCAPE_URL')) {
    define('ESCAPE_URL', 1);
}
if (!defined('ESCAPE_TEXT')) {
    define('ESCAPE_TEXT', 2);
}
if (!defined('ESCAPE_BOTH')) {
    define('ESCAPE_BOTH', 3);
}

function ivanhoe_a ($url, $link_text, $css_options=null, $escape=ESCAPE_BOTH)
{
    $translated_text = __($link_text, 'ivanhoe');

    if ($escape & ESCAPE_URL) {
        $url = htmlspecialchars($url);
    }
    if ($escape & ESCAPE_TEXT) {
        $translated_text = htmlspecialchars($translated_text);
    }

    $html = '<a href="' . $url . '"';

    if ($css_options) {
        $html .= " " . $css_options;
    }

    $html .= ">";
    $html .= $translated_text;
    $html .= "</a>";

    return $html;
}
