<?php
/**
* Elgg multimedia Plugin
* @package multimedia
* @author ura soul
* @link https://www.infiniteeureka.com + http://www.multimedia.com/
*/

function multimedia_init() 
{
    $base_path = elgg_get_plugins_path() . 'multimedia/';
    $public_base_path = elgg_get_site_url() . 'mod/multimedia/';
    $lib = $base_path . 'lib/multimedia-lib.php';
    elgg_register_library('multimedia-lib', $lib);
    elgg_load_library('multimedia-lib');
    $hooks = $base_path . 'lib/multimedia-hooks.php';
    elgg_register_library('multimedia-hooks', $hooks);
    elgg_load_library('multimedia-hooks');    
    // Extend system CSS with additional styles
    elgg_extend_view('css/elgg', 'multimedia/css');
    elgg_extend_view('css/admin', 'multimedia/admin', 1);    
    // Register multimedia javascript	
	$base_js_url = $public_base_path . 'vendors/video-js/video.js';
	elgg_register_js('multimedia', $base_js_url);
    elgg_register_js('multimedia-logo', $public_base_path . 'vendors/video-js/plugins/videojs.logobrand.js');
    elgg_register_js('multimedia-speed', $public_base_path . 'vendors/video-js/plugins/video-speed.js');
    elgg_register_js('multimedia-watermark', $public_base_path . 'vendors/video-js/plugins/videojs.watermark.js');
    elgg_register_js('multimedia-vol-persist', $public_base_path . 'vendors/video-js/plugins/videojs.persistvolume.js');
    elgg_register_js('multimedia-related', $public_base_path . 'vendors/video-js/plugins/videojs.relatedCarousel.js');
  
  
    elgg_unregister_action("file/upload");
    elgg_register_action("file/upload", "$base_path" . "actions/file/upload.php");
    elgg_register_plugin_hook_handler("route", "file", "multimedia_file_route_hook");    

  //  elgg_unregister_plugin_hook_handler('entity:icon:url', 'object', 'file_icon_url_override');
//    elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'multimedia_file_icon_url_override');

    elgg_register_page_handler('media-embed', 'multimedia_page_handler');  
}

elgg_register_event_handler('init', 'system', 'multimedia_init');