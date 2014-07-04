<?php
/**
* Elgg multimedia Plugin
* @package multimedia
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
*/

// Back-end settings
// Set Defaults
if (!isset($vars['entity']->encode)) {
	$vars['entity']->encode = 'off';
	}
if (!isset($vars['entity']->audio_wd)) {
	$vars['entity']->audio_wd = 300;
	}
if (!isset($vars['entity']->audio_ht)) {
	$vars['entity']->audio_ht = 150;
	}
if (!isset($vars['entity']->video_wd)) {
	$vars['entity']->video_wd = 640;
	}
if (!isset($vars['entity']->video_ht)) {
	$vars['entity']->video_ht = 480;
	}
if (!isset($vars['entity']->river_width_a)) {
    $vars['entity']->river_width_a = 320;
    }
if (!isset($vars['entity']->river_height_a)) {
    $vars['entity']->river_height_a = 120;
    }
if (!isset($vars['entity']->river_width_v)) {
    $vars['entity']->river_width_v = 320;
    }
if (!isset($vars['entity']->river_height_v)) {
    $vars['entity']->river_height_v = 240;
    }
if (!isset($vars['entity']->video_start)) {
	$vars['entity']->video_start = 'false';
	}
if (!isset($vars['entity']->audio_start)) {
	$vars['entity']->audio_start = 'false';
	}
if (!isset($vars['entity']->thumb_percent)) {
    $vars['entity']->thumb_percent = 11;
    }
if (!isset($vars['entity']->avconv_path)) {
    $vars['entity']->avconv_path = 'usr/bin';
    }
if (!isset($vars['entity']->river_autoplay)) {
    $vars['entity']->river_autoplay = 1;
    }
if (!isset($vars['entity']->watermark_path)) {
    $vars['entity']->watermark_path = '';
    }


echo '<div style="margin-left:25px;">';
// Setup option for urlencoding
echo "<h3>" . elgg_echo("multimedia:encode:encode") . "</h3>";
echo '<br/>';
echo '<p>' . elgg_echo("multimedia:encoding:desc") . '</p>';

echo "<br /><label>" . elgg_echo("multimedia:encode:encoding") . "</label>";

echo elgg_view('input/dropdown', array(
	'name' => 'params[encode]',
	'options_values' => array(
		'urlencode' => elgg_echo('multimedia:urlencode'),
		'rawurlencode' => elgg_echo('multimedia:rawurlencode'),
		'off' => elgg_echo('multimedia:off')
	),
	'value' => $vars['entity']->encode,
	'class' => 'elgg-admin-input-right',
));

echo '<p style="padding-bottom:20px;border-bottom:solid 1px #777;width:100%;"></p>';

// Setup option for autostart
echo "<h3>" . elgg_echo("multimedia:autoplay:autoplay") . "</h3>";
echo '<br/>';
echo '<p>' . elgg_echo("multimedia:autoplay:desc") . '</p>';


echo '<div style="margin:5px;">';
echo elgg_view('input/dropdown', array(
	'name' => 'params[audio_start]',
	'options_values' => array(
		'false' => elgg_echo('multimedia:autoplay:off'),
		'true' => elgg_echo('multimedia:autoplay:on')
	),
	'value' => $vars['entity']->audio_start,
    'class' => 'elgg-admin-input-right',	
)) . '</div>';

echo "<label>" . elgg_echo("multimedia:autoplay:audio") . "</label>";

echo '<div style="margin:5px;">';
echo elgg_view('input/dropdown', array(
	'name' => 'params[video_start]',
	'options_values' => array(
		'false' => elgg_echo('multimedia:autoplay:off'),
		'true' => elgg_echo('multimedia:autoplay:on')
	),
	'value' => $vars['entity']->video_start,
    'class' => 'elgg-admin-input-right',	
)) . '</div>';
echo "<label>" . elgg_echo("multimedia:autoplay:video") . "</label>";

// river autoplay

echo '<div style="margin:5px;">';
echo elgg_view('input/dropdown', array(
    'name' => 'params[river_autoplay]',
    'options_values' => array(
        0 => elgg_echo('multimedia:autoplay:off'),
        1 => elgg_echo('multimedia:autoplay:on')
    ),
    'value' => $vars['entity']->river_autoplay,
    'class' => 'elgg-admin-input-right',    
)) . '</div>';
echo '<label>'. elgg_echo("multimedia:admin:river_autoplay") .'</label>';

echo '<p style="padding-bottom:20px;border-bottom:solid 1px #777;width:100%;">';
// Setup A&V screens height and width 
echo '<br/><br/>';
echo "<h3>" . elgg_echo("multimedia:player:size") . "</h3>";
echo '<br/>';
echo '<p>' . elgg_echo("multimedia:player:size:desc") . "</p>";
echo '<br/>';

//video player sizes
echo "<h4>" . elgg_echo("multimedia:video:size") . "</h4>";

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
	'name' => 'params[video_ht]',
	'class' => 'multimedia-admin-input',
	'value' => $vars['entity']->video_ht,
)) . '</div>';
echo '<div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:video:size:height") .'</label></div><div style="clear:both;"></div>';

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
	'name' => 'params[video_wd]',
	'class' => 'multimedia-admin-input',
	'value' => $vars['entity']->video_wd,
)) . '</div>';
echo '<div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:video:size:width") .'</label></div><div style="clear:both;"></div>';

// audio player sizes
echo "<h4>" . elgg_echo("multimedia:audio:size") . "</h4><p>";

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
	'name' => 'params[audio_ht]',
	'class' => 'multimedia-admin-input',
	'value' => $vars['entity']->audio_ht,
));
echo '</div><div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:audio:size:height") .'</label></div><div style="clear:both;"></div>';

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
	'name' => 'params[audio_wd]',
	'class' => 'multimedia-admin-input',
	'value' => $vars['entity']->audio_wd,
));

echo '</div><div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:audio:size:width") .'</label></div><div style="clear:both;"></div>';

// river video
echo "<h4>" . elgg_echo("multimedia:video:river:size") . "</h4><p>";

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[river_height_v]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->river_height_v,
));
echo '</div><div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:river_size:video:height") .'</label></div><div style="clear:both;"></div>';

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[river_width_v]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->river_width_v,
));
echo '</div><div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:river_size:video:width") .'</label></div><div style="clear:both;"></div>';

// river audio
echo "<h4>" . elgg_echo("multimedia:audio:river:size") . "</h4>";

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[river_height_a]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->river_height_a,
));
echo '</div><div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:river_size:audio:height") .'</label></div><div style="clear:both;"></div>';

echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[river_width_a]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->river_width_a,
));
echo '</div><div style="margin:6px 0 0 20px;"><label>' . elgg_echo("multimedia:river_size:audio:width") . '</label></div><div style="clear:both;"></div>';

echo "<br/><br/><em>" . elgg_echo("multimedia:size:option") . "</em>";

echo '<p style="padding-bottom:20px;border-bottom:solid 1px #777;width:100%;"></p>';

echo "<h3>" . elgg_echo("multimedia:thumbnail:options") . "</h3>";
echo '<br/>';

// thumb percentage
echo '<div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:admin:thumb_percent") .'</label></div><div style="clear:both;"></div>';
echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[thumb_percent]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->thumb_percent,
));
echo '</div><div class="clearfloat"></div>';
// FFMPEG path
echo '<div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:admin:avconv_path") .'</label></div><div style="clear:both;"></div>';
echo '<div style="float:right;margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[avconv_path]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->avconv_path,
));
echo '</div><div class="clearfloat"></div>';


echo '<p style="padding-bottom:20px;border-bottom:solid 1px #777;width:100%;"></p>';

echo "<h3>" . elgg_echo("multimedia:watermark:options") . "</h3>";
echo '<br/>';

// watermark
echo '<div style="margin:6px 0 0 20px;"><label>'. elgg_echo("multimedia:admin:watermark_path") .'</label></div><div style="clear:both;"></div>';
echo '<div style="margin:0 10px 10px 0;padding-left:25px;">';
echo elgg_view('input/text', array(
    'name' => 'params[watermark_path]',
    'class' => 'multimedia-admin-input',
    'value' => $vars['entity']->watermark_path,
));
echo '</div><div class="clearfloat"></div>';
echo '<br/>';