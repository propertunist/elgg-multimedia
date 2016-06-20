<?php
//elgg_require_js('zclip'); 
elgg_require_js('multimedia/init');
$video_widths = array(560, 853, 1024);
//$video_width = elgg_get_plugin_user_setting('video_embed_width', elgg_get_logged_in_user_guid(), 'multimedia');
//if (!in_array($video_width, $haystack))
//{
    $video_width = 640;
//}
$base_path = elgg_get_site_url();
$embed_code = htmlspecialchars('<iframe width="' . $vars['width'] . '" height="' . $vars['height'] . '" src="' . elgg_get_site_url() . 'media-embed/' . $vars['entity']->getGUID() . '" allowfullscreen></iframe>');

$output = '<div class="elgg-media-embed-box">';
$output .= elgg_echo('multimedia:embed:embed-code') . '<input type="text" value="' . $embed_code . '" class="elgg-input-text embedcode" id="embedcode" readonly/>';

$output .= elgg_view('input/dropdown', array(
                        'title' => elgg_echo('multimedia:widths_for_embed'),
                        'class' => 'embed-width-select',
                        'value' => $video_width,
                        'options_values' => array(
                                '560' => '560 x 315',
                                '640' => '640 x 480',
                                '853' => '853 x 480',
                                '1280' => '1280 x 720',                            
                        ),
                ));

$output .= elgg_view('output/url', array('class'=>'embed-copy-btn elgg-button',
                                         'href' => '#',
                                         'id' => 'embed-copy-btn',
                                         'title' => elgg_echo('multimedia:copy_prompt'),
                                         'onclick' => 'javascript: copyToClipboard($(\'input#embedcode\').val());',
                                         'text'=> elgg_view('output/img', array('alt'=> elgg_echo('multimedia:copy_button'),
                                         'src' => $base_path . 'mod/multimedia/graphics/edit-copy.png'))));
$output .= '</div>';
/*$output .= '<script>'
        . '$(document).ready(function(){
            $(\'a#embed-copy-btn-' .$vars['entity']->getGUID() . '\').zclip({
            path:\'' . $base_path . 'mod/multimedia/views/default/js/multimedia/ZeroClipboard.swf\',
            copy:function(){return $(\'input#embedcode-' . $vars['entity']->getGUID() . '\').val();}
          });});'
        . '</script>';*/
echo $output;