<?php
/**
 * Elgg file play audio & video files.
 *
 * @package ElggFile
 */

if (!class_exists('VideoStream')) {
    require elgg_get_plugins_path() . 'multimedia/classes/videostream.php';
}

//ob_end_clean();

// Get the guid
$file_guid = get_input("guid");

// Get the file
$file = get_entity($file_guid);
if (!elgg_instanceof($file, 'object', 'file')) {
	register_error(elgg_echo("file:downloadfailed"));
	forward();
}

$mime = $file->getMimeType();
if (!$mime) {
	$mime = "application/octet-stream";
}

$stream = new VideoStream($file->getFilenameOnFilestore());
$stream->start();
