<?php
/**
* Elgg multimedia Plugin
* @package multimedia
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
*/

$vars['width'] = '640';
if($plugin_video_wd = elgg_get_plugin_setting("video_wd", "multimedia")){
    $vars['width'] = $plugin_video_wd;
}
$vars['height'] = '480';
if($plugin_video_ht = elgg_get_plugin_setting("video_ht", "multimedia")){
    $vars['height'] = $plugin_video_ht;
}

if($plugin_video_autoplay = elgg_get_plugin_setting("video_start", "multimedia")){
    $vars['autoplay'] = $plugin_video_autoplay;
}

echo elgg_view('multimedia/player', $vars);
echo elgg_view('multimedia/embed-box', $vars);
