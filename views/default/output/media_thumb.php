<?php
/**
 * Elgg media_thumb view
 *
 * @uses string $vars['src'] The image src url.
 */

$vars['src'] = elgg_normalize_url($vars['src']);
$vars['src'] = elgg_format_url($vars['src']);

$attributes = elgg_format_attributes($vars);
$output = "<img $attributes/>";
$output .= '<div class="elgg-media-thumb"></div>';
echo $output;