<?php
/**
 * File river view: includes thumbnail replacement for audio/video files
 */

elgg_require_js('multimedia/init');
$object = $vars['item']->getObjectEntity();
$object_guid = $object->getGUID();
$excerpt = strip_tags($object->description);
$excerpt = '<div class="elgg-river-excerpt">' . elgg_get_excerpt($excerpt,230) . '</div>';
$thumbnail = '<div class="elgg-river-thumb elgg-river-media">';

if (!$autoplay = elgg_get_plugin_setting('river_autoplay','multimedia'))
    $autoplay = 0;

if (($object->simpletype == 'video')||($object->simpletype == 'audio'))
{
    if ($object->simpletype == 'video')
    {
        if (!$height = elgg_get_plugin_setting('river_height_v','multimedia'))
            $height = 240;
        if (!$width = elgg_get_plugin_setting('river_width_v','multimedia'))
            $width = 320;
    }
    else
    {
        if (!$height = elgg_get_plugin_setting('river_height_a','multimedia'))
            $height = 120;
        if (!$width = elgg_get_plugin_setting('river_width_a','multimedia'))
            $width = 320;
    }

    $media = '<iframe class=\\"elgg-media-iframe\\" width=\\"' . $width . '\\" height=\\"' . $height . '\\" src=\\"' . elgg_get_site_url() . 'media-embed/' . $object_guid;
    if ($autoplay==1)
        $media .= '?autoplay=1';
    $media .= '\\"></iframe>';

    $size = 'large';

    $thumbnail .= elgg_view('output/url', array(
      'href' => $object->getURL(),
      'data-media' => $media,
      'text' => elgg_view('output/media_thumb', array(
      'src' => $object->getIconURL($size),
      'alt' => $object->title))));
}
else
{
	$thumbnail .= elgg_view('icon/object/file', array(
    'entity' => $object,
    'size' => 'large',
    ));
}

$thumbnail .= '</div>';

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $thumbnail . $excerpt,
));
echo $script;
