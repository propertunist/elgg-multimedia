<?php

$embed_code = htmlspecialchars('<iframe width="' . $vars['width'] . '" height="' . $vars['height'] . '" src="' . elgg_get_site_url() . 'media-embed/' . $vars['entity']->getGUID() . '"></iframe>');

$output = '<div class="elgg-media-embed-box">';
$output .= elgg_echo('multimedia:embed:embed-code') . '<input type="text" value="' . $embed_code . '" class="elgg-input-text embedcode" readonly/>';
$output .= '</div>';
echo $output;
?>