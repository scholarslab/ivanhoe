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

function ivanhoe_a ($url, $link_text, $css_options=null, $escape="escape_both")
{
    $translated_text = __($link_text, 'ivanhoe');

    switch ($escape) {
        case "escape_none":
            $url = $url;
            $translated_text = $translated_text;
            break;
        case "escape_url":
            $url = htmlspecialchars($url);
            break;
        case "escape_text":
            $translated_text = htmlspecialchars($translated_text);
            break;
        case "escape_both":
            $url = htmlspecialchars($url);
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
