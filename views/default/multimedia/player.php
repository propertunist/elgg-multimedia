<?php
/**
* Elgg multimedia Plugin
* @package multimedia
*/

// Load multimedia javascript
elgg_require_js('videojs');
elgg_require_js('multimedia/player');
elgg_require_js('video-logobrand');
elgg_require_js('video-watermark');

// Set location variables
$file = $vars['entity'];
$site_url = elgg_get_site_url();
$swf_url =  $site_url . 'mod/multimedia/vendors/video-js/video-js.swf';
$owner = $vars['entity']->getOwnerEntity();
$owner_url = $owner->getURL();
$file_url = $site_url . 'file/play/'.$file->getGUID();
$view_url = $site_url . 'file/view/'.$file->getGUID();
$callback_url = $site_url;

$watermark_path = $owner->getIconURL('small');

if ($watermark_path == '')
{
    $watermark_path = elgg_get_plugin_setting("watermark_path", "multimedia");
}

// get file's external path
$real_file_path = elgg_get_inline_url($file);

if (!$real_file_path) {
	// no file path is available, return to origin page with error
	register_error(elgg_echo('multimedia:real_path_error'));
	forward(REFERER);
}

$mime = $vars['entity']->getMimeType();
if (!$mime) {
    $mime = "application/octet-stream";
}

if (!$vars['autoplay'])
    $vars['autoplay'] = 'false';

if ($vars['autoplay'] == 0)
{
    $vars['autoplay'] = 'false';
}
else
{
    $vars['autoplay'] = 'true';
}

if($vars['iframe'])
{
        $vars['width'] = '100%';
        $vars['height'] = '100%';
        $callback_url = $view_url;
}

// build HTML for media player

$output = '<div class="multimedia';
if ($vars['iframe']) {$output .= ' elgg-media-embed';}
$output .='" id="mediaplayer" data-swf="' . $swf_url . '" data-title="' . $file->title . '" data-owner="' . $owner->name . '" data-href="' . $view_url . '" data-watermark="' . $watermark_path . '" data-owner-url=" ' . $owner_url . '">';

$output .= '<video id="multimedia_1" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="' . $vars['width'] . '" height="' . $vars['height'] . '"
 poster="' . $file->getIconURL('master') . '" data-setup=\'{"controls":true,"autoplay":' . $vars['autoplay'] . ',"preload":"auto"}\'>';

$output .= '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>';

$output .= '<source src="' . $real_file_path .'" type="' . $mime .'" />';

$output .= '</video>';
$output .= '</div>';

echo $output;
