<?php

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
        if ($type == 'music')
            $url = "mod/ureka_theme/_graphics/icons/file/{$type}{$ext}.png";
        else
            $url = "mod/file/graphics/icons/{$type}{$ext}.gif";
        $url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);
        return $url;
    }
}
