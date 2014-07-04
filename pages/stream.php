<?php
/**
 * Elgg file stream.
 *
 * @package ElggFile
 */

// Get the guid
$file_guid = get_input("guid");

// Get the file
$file = get_entity($file_guid);
if (!$file) {
  //  register_error(elgg_echo("file:downloadfailed"));
  //  forward();
  exit;
}

$mime = $file->getMimeType();
if (!$mime) {
    $mime = "application/octet-stream";
}

$filename = $file->originalfilename;
echo '<pre>';
var_dump($filename);
echo '</pre>';
exit;
// fix for IE https issue
//header("Pragma: public");

//header("Content-type: $mime");
//if (strpos($mime, "image/") !== false || $mime == "application/pdf") {
    //header("Content-Disposition: inline; filename=\"$filename\"");
//} else {
    //header("Content-Disposition: attachment; filename=\"$filename\"");
//}
header("X-Accel-Redirect: /stream/" . $filename);
//ob_clean();
//flush();
//readfile($file->getFilenameOnFilestore());
//exit;
