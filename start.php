<?php
/**
* Elgg multimedia Plugin
* @package multimedia
* @author ura soul
* @link https://www.ureka.org
*/

function multimedia_init()
{
    $base_path = elgg_get_plugins_path() . 'multimedia/';
    $public_base_path = elgg_get_site_url() . 'mod/multimedia/';

    $lib = $base_path . 'lib/functions.php';
    elgg_register_library('multimedia-functions', $lib);
    elgg_load_library('multimedia-functions');

    $hooks = $base_path . 'lib/hooks.php';
    elgg_register_library('multimedia-hooks', $hooks);
    elgg_load_library('multimedia-hooks');

    $handlers = $base_path . 'lib/handlers.php';
    elgg_register_library('multimedia-handlers', $handlers);
    elgg_load_library('multimedia-handlers');

    $events = $base_path . 'lib/events.php';
    elgg_register_library('multimedia-events', $events);
    elgg_load_library('multimedia-events');

    // Extend system CSS with additional styles
    elgg_extend_view('elgg.css', 'multimedia/css');
    elgg_extend_view('admin.css', 'multimedia/admin', 1);

    // elgg_register_event_handler('upgrade', 'system', 'multimedia_run_upgrades');
    elgg_unregister_action("file/upload");
    elgg_register_action("file/upload", "$base_path" . "actions/file/upload.php", 'admin');
    elgg_unregister_action('videolist/edit');
    elgg_register_action('videolist/edit', "$base_path" . "actions/videolist/edit.php");

    // register upgrade action
    elgg_register_action("multimedia/multimedia_videolist_items", dirname(__FILE__) . "/actions/multimedia/multimedia_videolist_items.php");

   // elgg_register_event_handler('upgrade', 'system', 'multimedia_upgrade_system_event_handler');

    elgg_register_action("multimedia/multimedia_file_thumbnails", dirname(__FILE__) . "/actions/multimedia/multimedia_file_thumbnails.php");

    elgg_unregister_plugin_hook_handler('entity:icon:url', 'object', 'file_icon_url_override');
    elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'multimedia_file_icon_url_override');

    elgg_register_page_handler('media-embed', 'multimedia_page_handler');

    elgg_define_js('video-watermark', array(
        'src' => elgg_get_simplecache_url('js', 'videojs.watermark.js'),
        'deps' => array('jquery', 'videojs'),
        'exports' => 'videojs.watermark',
    ));
    elgg_define_js('video-logobrand', array(
        'src' => elgg_get_simplecache_url('js', 'videojs.logobrand.js'),
        'deps' => array('jquery', 'videojs'),
        'exports' => 'videojs.logobrand',
    ));
}

elgg_register_event_handler('init', 'system', 'multimedia_init');
