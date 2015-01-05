<?php

/**
* Anchor builder helper
*
* @param $url
* @param $link_text
* @param $css_options string e.x. "class = 'btn'"
* @param $text_escape boolean escapes link text unless set to false
* @param $url_escape boolean escapes link text unless set to false
*
* @return html anchor tags with css and link text
*/

function ivanhoe_a ($url, $link_text, $css_options=null, $url_escape=true, $text_escape=true)
{

    if ($url_escape) {
        $url = htmlspecialchars($url);
    }

    $translated_text = __($link_text, 'ivanhoe');
    if ($text_escape) {
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
