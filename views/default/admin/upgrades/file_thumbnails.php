<?php

// Upgrade also possible hidden entities. This feature get run
// by an administrator so there's no need to ignore access.
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$options = array(
	"type" => "object",
        "subtype" => 'file',
	"count" => true,
        "metadata_name_value_pairs" => array('name' => 'simpletype', 'value' => 'video'));

$count = elgg_get_entities_from_metadata($options);
               
echo elgg_view("admin/upgrades/view", array(
    	"count" => $count,
	"action" => "action/multimedia/multimedia_file_thumbnails",
));

access_show_hidden_entities($access_status);