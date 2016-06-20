<?php

error_log('multimedia: videolist upgrade: start action');
global $START_MICROTIME;
$batch_run_time_in_secs = 5;
if ($status = get_input("upgrade_completed")) {
    error_log($status);
}

if (get_input("upgrade_completed")) {
	// set the upgrade as completed
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath("admin/upgrades/videolist_items");
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}

	return true;
}

// Offset is the total amount of errors so far. We skip these
// annotations to prevent them from possibly repeating the same error.
$offset = (int) get_input("offset", 0);

$limit = 1000;
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

$options["count"] = true;
$options["type"] = 'object';
$options["subtype"] = 'videolist_item';
$options["order_by"] = 'e.time_created asc';
$options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM metadata md, metastrings ms, metastrings ms2 WHERE md.entity_guid = e.guid AND md.name_id = ms.id AND md.value_id = ms2.id AND ms.string = 'simpletype' AND ms2.string = 'video')";
$total_count = elgg_get_entities($options);


$options["count"] = false;
$options["offset"] = $offset;
$options["limit"] = $limit;
$videos = elgg_get_entities($options);
error_log('TOTAL COUNT = ' . $total_count);

error_log('multimedia: videolist upgrade: before processing loop');

while (((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs)&&($processed_count < $total_count))
{

    foreach ($videos as $video) 
    {
        error_log('multimedia: videolist upgrade: entity = ' . $video->guid);
        $video->simpletype = 'video';
        if ($video->simpletype == 'video') {
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
