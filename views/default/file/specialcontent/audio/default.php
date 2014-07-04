<?php
/**
* Elgg multimedia Plugin
* @package multimedia
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
*/

$vars['width'] = '480';
if($plugin_audio_wd = elgg_get_plugin_setting("audio_wd", "multimedia")){
    $vars['width'] = $plugin_audio_wd;
}
$vars['height'] = '100';
if($plugin_audio_ht = elgg_get_plugin_setting("audio_ht", "multimedia")){
    $vars['height'] = $plugin_audio_ht;
}

if($plugin_audio_autoplay = elgg_get_plugin_setting("audio_start", "multimedia")){
    $vars['autoplay'] = $plugin_audio_autoplay;
}


echo elgg_view('multimedia/player', $vars);
echo elgg_view('multimedia/embed-box', $vars);