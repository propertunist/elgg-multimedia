<?php

// Upgrade also possible hidden entities. This feature get run
// by an administrator so there's no need to ignore access.
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);
$dbprefix = elgg_get_config('dbprefix');
//echo('dbprefix = ' . $dbprefix . '<br/>');
$options = array(
	"type" => "object",
        "subtype" => 'videolist_item',
	"count" => true
);

$options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM metadata md, metastrings ms, metastrings ms2 WHERE md.entity_guid = e.guid AND md.name_id = ms.id AND md.value_id = ms2.id AND ms.string = 'simpletype' AND ms2.string = 'video')";

$count = elgg_get_entities($options);
               
echo elgg_view("admin/upgrades/view", array(
    	"count" => $count,
	"action" => "action/multimedia/multimedia_videolist_items",
));

access_show_hidden_entities($access_status);