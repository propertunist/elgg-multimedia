<?php
/**
* Elgg multimedia Plugin
* @package multimedia
*/

// Load multimedia javascript
elgg_load_js('multimedia');

// Set location variables
$swf_url =  elgg_get_site_url() . 'mod/multimedia/vendors/video-js/video-js.swf';
$owner = $vars['entity']->getOwnerEntity();
$file_url = elgg_get_site_url() . 'file/download/'.$vars['entity']->getGUID();
$view_url = elgg_get_site_url() . 'file/view/'.$vars['entity']->getGUID();
$callback_url = elgg_get_site_url();


$dbprefix = $CONFIG->dbprefix;
// Go to DB and pull down filename data
$result = mysql_query("SELECT {$dbprefix}metastrings.string
FROM {$dbprefix}metastrings
LEFT JOIN {$dbprefix}metadata
ON {$dbprefix}metastrings.id = {$dbprefix}metadata.value_id
LEFT JOIN {$dbprefix}objects_entity
ON {$dbprefix}metadata.entity_guid = {$dbprefix}objects_entity.guid
WHERE ({$dbprefix}objects_entity.guid = '{$vars['entity']->getGUID()}') AND ({$dbprefix}metastrings.string LIKE 'file/%')");
// Check query ran and result is populated
if (!$result) {
// Query failed, return to origin page with error
	register_error(elgg_echo('multimedia:dbase:runerror'));
	forward(REFERER);
}
// Query worked but returned empty row, slightly unneccesary, but anyway...
if (mysql_num_rows($result) == 0) {
	register_error(elgg_echo('multimedia:dbase:notvalid'));
	forward(REFERER);
}

// Dump results into array
$row = mysql_fetch_array($result);

// Filename details
$ext = pathinfo($row['string'], PATHINFO_EXTENSION);
$filename = substr(pathinfo($row['string'], PATHINFO_FILENAME).".". pathinfo($row['string'], PATHINFO_EXTENSION),10);

// Should we urlencode the filename?
$pathfile = $file_url."/".$filename;
if($plugin_encode = elgg_get_plugin_setting("encode", "multimedia")){
	if($plugin_encode == 'urlencode') {
		$pathfile = $file_url."/".urlencode($filename);
	}
	elseif($plugin_encode == 'rawurlencode') {
		$pathfile = $file_url."/".rawurlencode($filename);
	}
	else  {
		$pathfile = $file_url."/".$filename;
	}
}

$mime = $vars['entity']->getMimeType();
if (!$mime) {
    $mime = "application/octet-stream";
}

if (!$vars['autoplay'])
    $vars['autoplay'] = 'false';

if ($vars['autoplay'] == 0)
{
    $vars['autoplay'] = 'false';
}
else
{
    $vars['autoplay'] = 'true';
}

if($vars['iframe'])
{
        $vars['width'] = '100%';
        $vars['height'] = '100%';
        $callback_url = $view_url;
}


?>

<!-- Place holder for multimedia-->
<div class="multimedia<?php if ($vars['iframe']) echo ' elgg-media-embed' ; ?>" id="mediaplayer">
    <video id="multimedia_1" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="<?php echo $vars['width']; ?>" height="<?php echo $vars['height']; ?>"
  poster="<?php echo  $vars['entity']->getIconURL('large'); ?>"
  data-setup='{"controls": true, "autoplay":<?php echo $vars['autoplay']; ?>,"preload": "auto" }'>
  <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
  <source src="<?php echo $pathfile; ?>" type='<?php echo $mime; ?>' />
  </video>
</div>
<!-- Set options for multimedia -->
    <script type="text/javascript">
             $(document).ready(function() {
                  videojs.options.flash.swf = "<?php echo $swf_url; ?>";
                 <?php //if ($vars['iframe']) echo 'iframe: true,' ; ?>
           //       title: '<?php echo '<b>' . $vars['entity']->title . '</b> <i>- ' . elgg_echo('multimedia:uploaded_by') . ' ' . $owner->name . '</i>'; ?>',
              
                  $(".embedcode").click(function() {
                        if(!$(this).hasClass("selected")) {
                            $(this).select();
                            $(this).addClass("selected");
                        }
                    });
                    $(".embedcode").blur(function() {
                        if($(this).hasClass("selected")) {
                            $(this).removeClass("selected");
                        }
                    });
             });
            
    </script>