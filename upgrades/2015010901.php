<?php
/**
 * refresh the video thumbnails for videos held by the the file plugin
 * First determine if the upgrade is needed and then if needed, batch the update
 */

  
$items = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'file',
	'limit' => 5,
	'order_by' => 'e.time_created asc',
));

// if not items, no upgrade required
if (!$items) {
        error_log ('multimedia file upgrade: no multimedia video files found');
	return;
}

/**
 * runs the multimedia thumbnail update function
 *
 * @param ElggObject $item
 * @return bool
 */
function multimedia_2015010901($item) 
{
    require_once(elgg_get_plugins_path() . 'upgrade-tools/lib/upgrade_tools.php');
    require_once(elgg_get_plugins_path() . 'multimedia/lib/functions.php');
    
    $prefix = "file/";
    static $item_counter = 0;
    
    if ($item->simpletype == 'video')
    {
        // use same filename on the disk - ensures thumbnails are overwritten
        $filestorename = $item->getFilename();
        $filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
        if(multimedia_get_video_thumbnail_for_file_plugin($item, 50, $prefix, $filestorename))
        {
            $item_counter = $item_counter +1;
            error_log("Elgg multimedia upgrade (2015010901): " . $item_counter . ". id = " . $item->guid . ' - was successfully processed');
        }
        else
        {
            error_log("Elgg multimedia upgrade (2015010901): id = " . $item->guid . ' - failed processing');
        }
    }
}
$dbprefix = elgg_get_config("dbprefix");
$previous_access = elgg_set_ignore_access(true);
$options = array(
	'type' => 'object',
	'subtype' => 'file',
	'limit' => 0,
);
//$options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM {$dbprefix}metadata md WHERE md.entity_guid = e.guid AND md.name_id = 'iconcheck')";

$c_options = array(
    'type' => 'object',
    'subtype' => 'file',
    'limit' => 0,
    'count' => TRUE
);
//$c_options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM {$dbprefix}metadata md WHERE md.entity_guid = e.guid AND md.name_id = 'iconcheck')";

$item_count = elgg_get_entities_from_metadata ($c_options);

error_log("Elgg multimedia upgrade (2015010901): begin batch - total items = " . $item_count . '; start time = ' . date(DATE_RSS,time()));

$batch = new ElggBatch('elgg_get_entities_from_metadata', $options, 'multimedia_2015010901', 5);
elgg_set_ignore_access($previous_access);

if ($batch->callbackResult) {
	error_log("Elgg multimedia upgrade (2015010901) succeeded");
} else {
	error_log("Elgg multimedia upgrade (2015010901) failed");
}

error_log("Elgg multimedia upgrade (2015010901): end batch; end time = " . date(DATE_RSS,time()));