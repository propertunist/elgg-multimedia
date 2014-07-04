<?php

function multimedia_file_route_hook($hook, $type, $return_value, $params)
{
        $result = $return_value;
        
        if($page = elgg_extract("segments", $return_value)){
            
            switch ($page[0]){
                 case "stream":
                    // Get the guid
                    $file_guid = $page[1];
                    
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
                 //   $file->d
                    $originalfilename = $file->originalfilename;
                    $filestorename = $file->getFilenameOnFilestore();
                    $filename = $file->getFilename();
                    $data_path = elgg_get_data_path();
                    $file_path = elgg_get_site_url()  . 'stream/' . substr($filestorename, strlen($data_path),(strlen($filestorename)-strlen($data_path)));

                    $debug = true;
                    
            /*        if ($debug)
                    {

                        echo '<pre>';
                        echo 'filename = ';
                        var_dump($filename);
                        echo '<br/>';
                        echo 'filestorename = ';
                        var_dump($filestorename);
                        echo '<br/>';
                        echo 'data path = ';
                        var_dump($data_path);     
                        echo '<br/>';
                        echo 'file path = ';
                        var_dump($file_path);                                    
                        echo '</pre>';
                        exit;
                    }
*/
                    // Clears the cache and prevent unwanted output
                    ob_clean();
                    
                    header('X-Accel-Redirect: "' . $file_path . '"');                    
                    /*
                    //@ini_set('error_reporting', E_ALL & ~ E_NOTICE);
                    //@apache_setenv('no-gzip', 1);
                    //@ini_set('zlib.output_compression', 'Off');
                     
                    $file = $filestorename; // The media file's location
                    $mime = "application/octet-stream"; // The MIME type of the file, this should be replaced with your own.
                    $size = filesize($file); // The size of the file
                  //    if(session_status() == PHP_SESSION_NONE)
                    //        session_write_close();

                    // Send the content type header
                    header('Content-type: ' . $mime);
                    
                    // Check if it's a HTTP range request
                    if(isset($_SERVER['HTTP_RANGE'])){
                        if ($debug)
                        {
                            header('poop: is range request');
                        }
                        // Parse the range header to get the byte offset
                        $ranges = array_map(
                            'intval', // Parse the parts into integer
                            explode(
                                '-', // The range separator
                                substr($_SERVER['HTTP_RANGE'], 6) // Skip the `bytes=` part of the header
                            )
                        );
                     
                        // If the last range param is empty, it means the EOF (End of File)
                        if(!$ranges[1]){
                            $ranges[1] = $size - 1;
                        }
                     
                        // Send the appropriate headers
                        header('HTTP/1.1 206 Partial Content');
                        header('Accept-Ranges: bytes');
                        header('Content-Length: ' . ($ranges[1] - $ranges[0])); // The size of the range
                     
                        // Send the ranges we offered
                        header(
                            sprintf(
                                'Content-Range: bytes %d-%d/%d', // The header format
                                $ranges[0], // The start range
                                $ranges[1], // The end range
                                $size // Total size of the file
                            )
                        );
                     
                        // It's time to output the file
                        $f = fopen($file, 'rb'); // Open the file in binary mode
                        $chunkSize = 8192; // The size of each chunk to output
                     
                        // Seek to the requested start range
                        fseek($f, $ranges[0]);
                     
                        // Start outputting the data
                        while(true){
                            // Check if we have outputted all the data requested
                            if(ftell($f) >= $ranges[1]){
                                break;
                            }
                     
                            // Output the data
                            echo fread($f, $chunkSize);
                     
                            // Flush the buffer immediately
                            @ob_flush();
                            flush();
                        }
                    }
                    else {
                        // It's not a range request, output the file anyway
                        if ($debug)
                        {
                            header('poop: not-range-request');
                        }
                        header('Content-Length: ' . $size);
                     
                        // Read the file
                        @readfile($file);
                     
                        // and flush the buffer
                        @ob_flush();
                        flush();
                    }
                    
                    
                    
                    // fix for IE https issue
                 //   header("Pragma: public");
                    
                  //  header("Content-type: $mime");
                 //   if (strpos($mime, "image/") !== false || $mime == "application/pdf") {
                 //       header("Content-Disposition: inline; filename=\"$filename\"");
                 //   } else {
                 //       //header("Content-Disposition: attachment; filename=\"$filename\"");
                //    }
                   // header("Accept-Ranges: byte");
                     */
                     
                    break;
               
        }
        
        return $result;
    }
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
