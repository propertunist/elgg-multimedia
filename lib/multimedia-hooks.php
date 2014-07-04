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
                    
                    if ($debug)
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

                  //  header('X-Accel-Redirect: "' . $file_path . '"');                    
                    
                    // fix for IE https issue
                    //header("Pragma: public");
                    
                    //header("Content-type: $mime");
                    //if (strpos($mime, "image/") !== false || $mime == "application/pdf") {
                        //header("Content-Disposition: inline; filename=\"$filename\"");
                    //} else {
                        //header("Content-Disposition: attachment; filename=\"$filename\"");
                    //}

                    break;
               
        }
        
        return $result;
    }
}
