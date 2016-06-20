<?php

if (!function_exists('multimedia_correct_moov_atom'))
{
    function multimedia_correct_moov_atom($inFile)
    {
        if (!class_exists('Qtfaststart')) {
            require elgg_get_plugins_path() . 'multimedia/classes/qt-faststart.php/lib/Qtfaststart.class.php';
        }

        /**
         * instanciate qt-faststart.php
         */
        $qtfaststart = Qtfaststart::getInstance();

        /**
         * read file, preprocess (parse atoms/boxes)
         */

        error_log('moov atom - file path = ' . $inFile);

        $result = $qtfaststart->setInput($inFile);
        if ($result !== true) {
           // die($result);
            error_log(elgg_echo('multimedia:moov_atom:fail', array($result)) . ': ' . $inFile);
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
            error_log(elgg_echo('multimedia:moov_atom:fail', array($result)) . ': ' . $file);
            register_error(elgg_echo('multimedia:moov_atom:fail', array($result)));
            $response = false;
        }

        /**
         * moov positioning fix
         */
        $result = $qtfaststart->fix();
        if ($result !== true) {
                error_log(elgg_echo('multimedia:moov_atom:fail', array($result)) . ': ' . $file);
                register_error(elgg_echo('multimedia:moov_atom:fail', array($result)));
                $response = false;
           // echo $result;
        } else {
               error_log(elgg_echo('multimedia:moov_atom:success', array($result)) . ': ' . $file);
               register_error(elgg_echo('multimedia:moov_atom:success', array($result)));
               $response = true;
        }

        return $response;
    }
}

if (!function_exists('multimedia_get_video_thumbnail_for_file_plugin'))
{
    function multimedia_get_video_thumbnail_for_file_plugin($file, $percentage, $prefix, $filestorename)
    {
        if ((!$file)||(!$prefix))
        {
            return false;
        }

        if (!$percentage)
        {
            $percentage = 50;
        }

        $video_path = $file->getFilenameOnFilestore();

        error_log('video_path = ' . $video_path);
        error_log('filestorename = ' . $filestorename);
        $filename = $file->filename;
        $filename = elgg_substr($filename, elgg_strlen($prefix));
        $avconv_dir = elgg_get_plugin_setting('avconv_path', 'multimedia');
        $encoder_name = elgg_get_plugin_setting('encoder_name', 'multimedia');

        if((!$avconv_dir)||($avconv_dir == ''))
        {
            error_log(elgg_echo('multimedia:avconv:processor_not_found'));
            return false;
        }

        if ((!file_exists('/' . $avconv_dir . '/avconv'))&&(!file_exists('/' . $avconv_dir . '/' . $encoder_name)))
        {
            error_log(elgg_echo('multimedia:avconv:processor_not_found'));
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
                error_log(elgg_echo('multimedia:avconv:file_not_found') . ': ' . $file);
                register_error(elgg_echo('multimedia:avconv:file_not_found'));
                return false;
            }
/*
            $width = '640';
            if($plugin_video_wd = elgg_get_plugin_setting("video_wd", "multimedia")){
                $width = $plugin_video_wd;
            }
            $height = '480';
            if($plugin_video_ht = elgg_get_plugin_setting("video_ht", "multimedia")){
                $height = $plugin_video_ht;
            }
*/
            $ffmpeg = new FFmpeg;
      //      $size =  $width . 'x' . $height;


            $thumb_path = $video_path. '-masterthumb'.'.jpg';
            $thumb_filename = $prefix . 'masterthumb' . $filestorename.'.jpg';
      //      elgg_dump('$thumb_filename = ' . $thumb_filename);
            // Get the position, a percentage of the way in the video
            $duration = multimedia_get_video_duration($video_path, true);
            $file->duration = $duration;
            $position = ($duration * ($percentage / 100));

            error_log('thumb_filename = ' . $thumb_filename);

            // create new file object to hold master thumbnail
            $thumb = new ElggFile();
            $thumb->setMimeType('image/jpeg');
            //$thumb->owner_guid = $file->gelgg_dumpuid;
            $thumb->owner_guid = $file->owner_guid;
            $thumb->setFilename($thumb_filename);
            $thumb->open("write");
            $thumb->close();
      //      elgg_dump('$thumb->getFilenameOnFilestore() = ' . $thumb->getFilenameOnFilestore());
            // create thumbnail
            $ffmpeg->input($video_path)->thumb(null , $position, 1)->output('"'.$thumb_path. '"','jpg')->ready();
         //   elgg_dump($ffmpeg->command);

            // move master thumbnail to filestore location of new file object
            rename($thumb_path,$thumb->getFilenameOnFilestore());

            // set addresses of thumbnails for original file
            $file->thumbnail = $thumb_filename;

            $icon_sizes = elgg_get_config("icon_sizes");

            $thumblarge = get_resized_image_from_existing_file($thumb->getFilenameOnFilestore(), $icon_sizes['large']['w'], $icon_sizes['large']['h'], false);
            if ($thumblarge) {
                $thumb->setFilename($prefix."largethumb".$filestorename.'.jpg');
                $thumb->open("write");
                $thumb->write($thumblarge);
                $thumb->close();
                $file->largethumb = $prefix."largethumb".$filestorename.'.jpg';
                unset($thumblarge);
            }

            $thumbmedium = get_resized_image_from_existing_file($thumb->getFilenameOnFilestore(), $icon_sizes['medium']['w'], $icon_sizes['medium']['h'], false);
            if ($thumbmedium) {
                $thumb->setFilename($prefix."mediumthumb".$filestorename.'.jpg');
                $thumb->open("write");
                $thumb->write($thumbmedium);
                $thumb->close();
                $file->mediumthumb = $prefix."mediumthumb".$filestorename.'.jpg';
                unset($thumbmedium);
            }

            $thumbsmall = get_resized_image_from_existing_file($thumb->getFilenameOnFilestore(), $icon_sizes['small']['w'], $icon_sizes['small']['h'], false);
            if ($thumbsmall) {
                $thumb->setFilename($prefix."smallthumb".$filestorename.'.jpg');
                $thumb->open("write");
                $thumb->write($thumbsmall);
                $thumb->close();
                $file->smallthumb = $prefix."smallthumb".$filestorename.'.jpg';
                unset($thumbsmall);
            }

            unset($thumb);
            return true;
        }
        else
        {
            return false;
        }
    }
}

if (!function_exists('multimedia_get_video_duration'))
{
    function multimedia_get_video_duration($video_path, $seconds)
    {
        $avconv_dir = elgg_get_plugin_setting('avconv_path', 'multimedia');
        $encoder_name = elgg_get_plugin_setting('encoder_name', 'multimedia');
        if(!$avconv_dir)
        {
            return;
        }
        if(!$encoder_name)
        {
            return;
        }

        ob_start();
        $command = "\"/{$avconv_dir}/{$encoder_name}\" -i \"$video_path\" 2>&1";
        passthru($command);
        $result = ob_get_contents();
        ob_end_clean();
        preg_match('/Duration: (.*?),/', $result, $matches);
        $duration = $matches[1];

        if($seconds)
        {
            $duration_array = explode(':', $duration);
            $duration = $duration_array[0] * 3600 + $duration_array[1] * 60 + $duration_array[2];
        }
        return $duration;
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
if (!function_exists('secondsToWords'))
{
    function secondsToWords($seconds)
    {
        /*** return value ***/
        $ret = "";

        /*** get the hours ***/
        $hours = intval(intval($seconds) / 3600);
        if($hours > 0)
        {
            if ($hours > 1)
            {
                $ret .= "$hours hours ";
            }
            else
            {
                $ret .= "$hours hour ";
            }
        }
        /*** get the minutes ***/
        $minutes = bcmod((intval($seconds) / 60),60);
        if($hours > 0 || $minutes > 0)
        {
            if ($minutes > 1)
            {
                $ret .= "$minutes minutes ";
            }
            else
            {
                $ret .= "$minutes minute ";
            }
        }

        /*** get the seconds ***/
        $seconds = bcmod(intval($seconds),60);
        if ($seconds > 0)
        {
            if ($seconds > 1)
            {
                $ret .= "$seconds seconds";
            }
            else
            {
                $ret .= "$seconds second";
            }
        }
        return $ret;
    }
}
