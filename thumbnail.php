<?php
/**
 * Elgg file thumbnail
 *
 * @package ElggFile
 */

 require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
 \Elgg\Application::start();
 
// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
$size = get_input('size', 'master');

$file = get_entity($file_guid);
if (!$file || $file->getSubtype() != "file") {
	exit;
}

$simpletype = $file->simpletype;

if (($simpletype == "image")||($simpletype == "video")) {

	// Get file thumbnail
	switch ($size) {
		case "small":
			$thumbfile = $file->smallthumb;
			break;
		case "medium":
			$thumbfile = $file->mediumthumb;
			break;
		case "large":
			$thumbfile = $file->largethumb;
			break;
		default:
		case "master":
			$thumbfile = $file->thumbnail;
			break;

	}

        if (!thumbfile)
        {
            $thumbfile = $file->thumbnail;
        }

	// Grab the file
	if ($thumbfile && !empty($thumbfile)) {

		$readfile = new ElggFile();
		$readfile->owner_guid = $file->owner_guid;
		$readfile->setFilename($thumbfile);

    if ($simpletype =='image')
		  $mime = $file->getMimeType();
    else
      $mime = 'image/jpeg';
		$contents = $readfile->grabFile();

		// caching images for 10 days
		header("Content-type: $mime");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}
}
