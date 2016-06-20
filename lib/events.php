<?php
/**
 * All event handler functions are bundled in this file
 */

/**
 * Listen to the upgrade event to make sure upgrades can be run
 *
 * @param string $event  the name of the event
 * @param string $type   the type of the event
 * @param null   $object nothing
 *
 * @return void
 */
function multimedia_upgrade_system_event_handler($event, $type, $object) {
	error_log('multimedia upgrade event');
	// Upgrade also possible hidden entities. This feature get run
	// by an administrator so there's no need to ignore access.
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	
	// register an upgrade script

        $path = "admin/upgrades/videolist_items";
        $upgrade = new ElggUpgrade();
        if (!$upgrade->getUpgradeFromPath($path)) {
                $upgrade->setPath($path);
                $upgrade->title = "multimedia videolist items upgrade";
                $upgrade->description = "run this script to make sure videolist items are merged with videos from the file plugin.";

                $upgrade->save();
        }
	
        $path = "admin/upgrades/file_thumbnails";
        $upgrade = new ElggUpgrade();
        if (!$upgrade->getUpgradeFromPath($path)) {
                $upgrade->setPath($path);
                $upgrade->title = "multimedia videolist items upgrade";
                $upgrade->description = "run this script to make sure videolist items are merged with videos from the file plugin.";

                $upgrade->save();
        }        
	
	access_show_hidden_entities($access_status);
}
