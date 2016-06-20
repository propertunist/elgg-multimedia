<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);
$file = elgg_extract('entity', $vars, FALSE);

if (!$file) 
{
    return TRUE;
}
//elgg_dump($full);
$owner = $file->getOwnerEntity();
$container = $file->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($file->description);
$mime = $file->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));
$format_type = substr($mime, (strpos($mime,'/')+1), strlen($mime));
$tags = elgg_view('output/tags', array('tags' => $file->tags));
$mime_label = '<div class="elgg-mime"><span>' . $base_type . ' - ' . $format_type;
if (($base_type == 'video') || ($base_type == 'audio'))
{
    if (!$file->duration)
    {
       $file->duration = multimedia_get_video_duration($file->getFilenameOnFilestore(), true);
    }
    $mime_label .=  ' : <span class="elgg-media-length">' . secondsToWords($file->duration) . '</span>';
}   

$mime_label .= '</span></div>';

$owner_link = elgg_view('output/url', array(
	'href' => "file/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));

$date = elgg_view_friendly_time($file->time_created);

$comments_count = $file->countComments();
//only display if there are commments
if ($comments_count != 0) 
{
    $text = elgg_echo("comments") . " ($comments_count)";
    $comments_link = elgg_view('output/url', array(
            'href' => $file->getURL() . '#file-comments',
            'text' => $text,
            'is_trusted' => true,
    )); 
} 
else 
{
    $comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'file',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date<br/>$mime_label $duration $comments_link $categories $tags";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) 
{
    $metadata = '';
}

if ($full && !elgg_in_context('gallery')) 
{
  //  elgg_dump('object file: begin');
  //  elgg_dump('object file: mime base type= ' . $base_type);
    $body = '';
    if (elgg_view_exists("file/specialcontent/$base_type")) {
      //  elgg_dump('object file: specialcontent mime found');
            $content = elgg_view("file/specialcontent/$base_type", $vars);
    }
    elseif (elgg_view_exists("file/specialcontent/$base_type/default")) 
    {
      //  elgg_dump('object file: specialcontent view found');
        $content = elgg_view("file/specialcontent/$base_type/default", $vars);
    }
    $file_icon = elgg_view_entity_icon($file, 'small');
    $params = array(
            'entity' => $file,
            'title' => false,
            'metadata' => $metadata,
            'subtitle' => $subtitle,
    );
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);

    $text = elgg_view('output/longtext', array('value' => $file->description));
    $body .= $text;
    $owner_icon = elgg_view_entity_icon($owner, 'small');
    $entity_info = elgg_view_image_block($owner_icon, $summary);

echo <<<HTML
$content
$entity_info
$body
HTML;
    
    
} elseif (elgg_in_context('gallery')) 
{
    echo '<div class="file-gallery-item">';
    echo '<h3>' . elgg_view('output/url', array('text' => $file->title, 'href'=>$file->getURL())) . '</h3>';
    echo elgg_view_entity_icon($file, 'large');
    echo "<p class='subtitle'>$owner_link $date</p>";
    echo '</div>';
} else 
{
    // brief view
    $params = array(
            'entity' => $file,
            'metadata' => $metadata,
            'subtitle' => $subtitle,
            'content' => $excerpt,
    );
    $params = $params + $vars;
    $list_body = elgg_view('object/elements/summary', $params);
    $file_icon = elgg_view_entity_icon($file, 'master');
    echo elgg_view_image_block($file_icon, $list_body);
}
