<?php


function multimedia_page_handler($page) 
{
      global $CONFIG;
      $page[0] = (int) $page[0];
	  $entity = get_entity($page[0]);
      $autoplay = (int) get_input('autoplay',0);

      
	  if ($entity instanceof ElggObject)
	  {
          $subtype = $entity->getSubtype();
              
          if ($subtype == 'file')
          {
              elgg_set_page_owner_guid($entity->getContainerGUID());
              $container = $entity->getContainerEntity();
              $owner = $entity->getOwnerEntity();

              if ($entity->title)
                $vars['title'] = $entity->title . ' ' .  elgg_echo('by') . ' ' . $owner->name . ' | ' . $CONFIG->sitename;
              else
                $vars['title'] = elgg_echo('multimedia:embed_title');    	
             
              $mime = $entity->mimetype;
              $base_type = substr($mime, 0, strpos($mime,'/'));
              $vars['iframe'] = true;
              $vars['entity'] = $entity;
              if ($autoplay)
                $vars['autoplay'] = $autoplay;
        	  switch ($base_type)
        	  {
        	  	case 'video':
                {
                    $vars['player'] = elgg_view('multimedia/player', $vars);
        			break;
                }
        		case 'audio':
                {
                    $vars['player'] = elgg_view('multimedia/player', $vars);
        			break;
                }
        		default: 
        		{
        		    elgg_echo('multimedia:embed:invalid_code');
        		    return false;
                }
        	  }
 
              echo elgg_view('multimedia/embed', $vars);
        	  return true;
    	  }
          else  
            return false;
	  }
	  else  
  		return false;
}

function multimedia_correct_moov_atom($inFile)
{
    if (!class_exists('Qtfaststart')) {
        require dirname(dirname(__FILE__)) . '/classes/qt-faststart.php/lib/Qtfaststart.class.php';
    }
    
    /**
     * instanciate qt-faststart.php
     */
    $qtfaststart = Qtfaststart::getInstance();
    
    /**
     * read file, preprocess (parse atoms/boxes)
     */
    $result = $qtfaststart->setInput($inFile);
    if ($result !== true) {
       // die($result);
        elgg_dump(elgg_echo('multimedia:moov_atom:fail', array($result)) . ': ' . $file);
        register_error(elgg_echo('multimedia:moov_atom:fail', array($result)));
        $response = false;
    }
    
    /**
     * set the output filename and path
     */
     
    $outFile = $inFile . '-moov.mp4';
    $result = $qtfaststart->setOutput($outFile);
    if ($result !== true) {
        //die($result);
        elgg_dump(elgg_echo('multimedia:moov_atom:fail', array($result)) . ': ' . $file);
        register_error(elgg_echo('multimedia:moov_atom:fail', array($result)));
        $response = false;        
    }
    
    /**
     * moov positioning fix
     */
    $result = $qtfaststart->fix();
    if ($result !== true) {
            elgg_dump(elgg_echo('multimedia:moov_atom:fail', array($result)) . ': ' . $file);
            register_error(elgg_echo('multimedia:moov_atom:fail', array($result)));
            $response = false;
       // echo $result;
    } else {
           elgg_dump(elgg_echo('multimedia:moov_atom:success', array($result)) . ': ' . $file);
           register_error(elgg_echo('multimedia:moov_atom:success', array($result)));
           $response = true;
    }

    return $response;
}

function multimedia_get_video_thumbnail_for_file_plugin($file, $percentage, $prefix, $filestorename)
{
    global $CONFIG;
    if ((!$file)||(!$prefix))
        return false;     

    if (!$percentage)
        $percentage = 50;
        
    $video_path = $file->getFilenameOnFilestore();        
    $filename = $file->filename;        
    $filename = elgg_substr($filename, elgg_strlen($prefix));
    $avconv_dir = elgg_get_plugin_setting('avconv_path', 'multimedia');
 
 
    if((!$avconv_dir)||($avconv_dir == '')) return;
    
    if (!file_exists('/' . $avconv_dir . '/avconv'))
    {
        elgg_dump(elgg_echo('multimedia:avconv:processor_not_found'));    
        return false;
    }
    
    
    if (!class_exists('FFmpeg')) {
        require dirname(dirname(__FILE__)) . '/classes/ffmpeg.class.php';
    }

    if (class_exists('FFmpeg'))
    {
       // elgg_dump('--------------------------');
        if ((!file_exists($video_path)) && (!is_file($video_path)))
        {
            elgg_dump(elgg_echo('multimedia:avconv:file_not_found') . ': ' . $file);
            register_error(elgg_echo('multimedia:avconv:file_not_found'));
            return false;
        }

        $width = '640';
        if($plugin_video_wd = elgg_get_plugin_setting("video_wd", "multimedia")){
            $width = $plugin_video_wd;
        }
        $height = '480';
        if($plugin_video_ht = elgg_get_plugin_setting("video_ht", "multimedia")){
            $height = $plugin_video_ht;
        }
                
        $ffmpeg = new FFmpeg;
        $size =  $width . 'x' . $height;
        

        $thumb_path = $video_path. '-masterthumb'.'.jpg';
        $thumb_filename = $prefix . 'masterthumb' . $filestorename.'.jpg';
  //      elgg_dump('$thumb_filename = ' . $thumb_filename);
        // Get the position, a percentage of the way in the video
        $duration = multimedia_get_video_duration($video_path, true);
        $file->duration = $duration;
        $position = ($duration * ($percentage / 100));
        // create new file object to hold master thumbnail
        $thumb = new ElggFile();
        $thumb->setMimeType('image/jpeg');
        //$thumb->owner_guid = $file->guid;
        $thumb->owner_guid = $file->owner_guid;
        $thumb->setFilename($thumb_filename);
        $thumb->open("write");
        $thumb->close();
  //      elgg_dump('$thumb->getFilenameOnFilestore() = ' . $thumb->getFilenameOnFilestore());
        // create thumbnail
        $ffmpeg->input($video_path)->thumb($size , $position, 1)->output('"'.$thumb_path. '"','jpg')->ready();   
     //   elgg_dump($ffmpeg->command);

        // move master thumbnail to filestore location of new file object         
        rename($thumb_path,$thumb->getFilenameOnFilestore());
        // set addresses of thumbnails for original file
     //   $file->thumbnail = $thumb_filename;
        $file->largethumb = $thumb_filename;
          

        $thumbsmall = get_resized_image_from_existing_file($thumb->getFilenameOnFilestore(), 120, 90, true);
        if ($thumbsmall) {
            $thumb->setFilename($prefix."smallthumb".$filestorename.'.jpg');
            $thumb->open("write");
            $thumb->write($thumbsmall);
            $thumb->close();
            $file->smallthumb = $prefix."smallthumb".$filestorename.'.jpg';
            $file->thumbnail = $prefix."smallthumb".$filestorename.'.jpg';
            unset($thumbsmall);
        }

        unset($thumb);
        return true;
    }
    else {
        return false;
    }
}

function multimedia_get_video_duration($video_path, $seconds)
{
    $avconv_dir = '/'.'usr/bin';
    if(!$avconv_dir) return;

    ob_start();
    $command = "\"{$avconv_dir}/avconv\" -i \"$video_path\" 2>&1";
 //   elgg_dump('duration command = ' . $command);
    passthru($command);
    $result = ob_get_contents();
    ob_end_clean();
   // elgg_dump('duration result = ' . $result);
    preg_match('/Duration: (.*?),/', $result, $matches);
    $duration = $matches[1];

    if($seconds)
    {
        $duration_array = explode(':', $duration);
        $duration = $duration_array[0] * 3600 + $duration_array[1] * 60 + $duration_array[2];
    }
    return $duration;
}

/**
 * Override the default entity icon for files
 *
 * Plugins can override or extend the icons using the plugin hook: 'file:icon:url', 'override'
 *
 * @return string Relative URL
 */
function multimedia_file_icon_url_override($hook, $type, $returnvalue, $params) {
    $file = $params['entity'];
    $size = $params['size'];

    if (elgg_instanceof($file, 'object', 'file')) {

        // thumbnails get first priority
        if ($file->thumbnail) {

    
            $ts = (int)$file->icontime;
            
            return "mod/multimedia/thumbnail.php?file_guid=$file->guid&size=$size&icontime=$ts";
        }

        $mapping = array(
            'application/excel' => 'excel',
            'application/msword' => 'word',
            'application/ogg' => 'music',
            'application/pdf' => 'pdf',
            'application/powerpoint' => 'ppt',
            'application/vnd.ms-excel' => 'excel',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.oasis.opendocument.text' => 'openoffice',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
            'application/x-gzip' => 'archive',
            'application/x-rar-compressed' => 'archive',
            'application/x-stuffit' => 'archive',
            'application/zip' => 'archive',

            'text/directory' => 'vcard',
            'text/v-card' => 'vcard',

            'application' => 'application',
            'audio' => 'music',
            'text' => 'text',
            'video' => 'video',
        );

        $mime = $file->mimetype;
        if ($mime) {
            $base_type = substr($mime, 0, strpos($mime, '/'));
        } else {
            $mime = 'none';
            $base_type = 'none';
        }

        if (isset($mapping[$mime])) {
            $type = $mapping[$mime];
        } elseif (isset($mapping[$base_type])) {
            $type = $mapping[$base_type];
        } else {
            $type = 'general';
        }

        if ($size == 'large') {
            $ext = '_lrg';
        } else {
            $ext = '';
        }
        
        $url = "mod/file/graphics/icons/{$type}{$ext}.gif";
        $url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);
        return $url;
    }
}

/**
 *
 * @convert seconds to hours minutes and seconds
 *
 * @param int $seconds The number of seconds
 *
 * @return string
 *
 */
function secondsToWords($seconds)
{
    /*** return value ***/
    $ret = "";

    /*** get the hours ***/
    $hours = intval(intval($seconds) / 3600);
    if($hours > 0)
    {
        if ($hours > 1)
            $ret .= "$hours hours ";
        else 
            $ret .= "$hours hour ";
    }
    /*** get the minutes ***/
    $minutes = bcmod((intval($seconds) / 60),60);
    if($hours > 0 || $minutes > 0)
    {
        if ($minutes > 1)
            $ret .= "$minutes minutes ";
        else 
            $ret .= "$minutes minute ";
    }
  
    /*** get the seconds ***/
    $seconds = bcmod(intval($seconds),60);
    if ($seconds > 0)
    {
        if ($seconds > 1)
            $ret .= "$seconds seconds";
        else 
            $ret .= "$seconds second";
    }
    return $ret;
}

?>