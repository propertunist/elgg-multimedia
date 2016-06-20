<?php

error_log('multimedia: file thumbnail generation upgrade: start action');
global $START_MICROTIME;
$batch_run_time_in_secs = 600;
if ($status = get_input("upgrade_completed")) {
    error_log($status);
}

if (get_input("upgrade_completed")) {
	// set the upgrade as completed
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath("admin/upgrades/file_thumbnails");
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}

	return true;
}

// Offset is the total amount of errors so far. We skip these
// annotations to prevent them from possibly repeating the same error.
$offset = (int) get_input("offset", 0);

$limit = 1;
error_log('OFFSET = ' . $offset);

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

// don"t want any event or plugin hook handlers from plugins to run
$original_events = _elgg_services()->events;
$original_hooks = _elgg_services()->hooks;
//_elgg_services()->events = new Elgg_EventsService();
//_elgg_services()->hooks = new Elgg_PluginHooksService();

elgg_register_plugin_hook_handler("permissions_check", "all", "elgg_override_permissions");
elgg_register_plugin_hook_handler("container_permissions_check", "all", "elgg_override_permissions");
_elgg_services()->db->disableQueryCache();

$success_count = 0;
$error_count = 0;
$processed_count = 0;

$options["count"] = true;
$options["type"] = 'object';
$options["subtype"] = 'file';
$options["metadata_name_value_pairs"] = array('name' => 'simpletype', 'value' => 'video');
$total_count = elgg_get_entities_from_metadata($options);


$options["count"] = false;
$options["offset"] = $offset;
$options["limit"] = $limit;
$files = elgg_get_entities_from_metadata($options);
error_log('TOTAL COUNT of video files = ' . $total_count);

error_log('multimedia: videolist upgrade: before processing loop');
$prefix = "file/";
while (((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs)&&($processed_count < $limit))
{

    foreach ($files as $file) 
    {
        error_log('multimedia: file thumbnail upgrade: entity = ' . $file->guid);
        		// use same filename on the disk - ensures thumbnails are overwritten
        $filestorename = $file->getFilename();
        $filestorename = elgg_substr($filestorename, elgg_strlen($prefix));

        if (multimedia_get_video_thumbnail_for_file_plugin($file, 50, $prefix, $filestorename)) {
            $success_count++;
        }
        else 
        {
            $error_count++;
        }
        $processed_count = $success_count + $error_count;
    }
}

access_show_hidden_entities($access_status);

// replace events and hooks
//_elgg_services()->events = $original_events;
//_elgg_services()->hooks = $original_hooks;
_elgg_services()->db->enableQueryCache();
error_log('$success_count = ' . $success_count);
// Give some feedback for the UI
echo json_encode(array(
	"numSuccess" => $success_count,
	"numErrors" => $error_count
));
