<?php

  $content = '<html xmlns="http://www.w3.org/1999/xhtml"><head>';
  $content .= '<title>' . $vars['title'] . '</title>';
  $content .= '<meta http-equiv="CACHE-CONTROL" content="NO-CACHE" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
  $content .= '<style type="text/css">
  body { background-color: #000; margin: 0px; padding: 0px;font:100%/1.4 Verdana,sans-serif!important;color:#eee;min-width:298px;}
  a {color:#fdae04!important;text-decoration:none!important;} 
  a:hover {text-decoration:underline!important; color:#ff6600!important;}';
  $content .= elgg_view('multimedia/css');
  $content .= '</style>';
  $content .= '<script type="text/javascript" src="' . elgg_get_site_url() . 'vendors/jquery/jquery-1.6.4.min.js"></script>';
  $content .= '<script type="text/javascript" src="' . elgg_get_site_url() . 'mod/multimedia/vendors/video-js/video.js"></script>';
  $content .= '<script type="text/javascript" src="' . elgg_get_site_url() . 'mod/multimedia/vendors/video-js/plugins/videojs.logobrand.js"></script>';
  $content .= '<script type="text/javascript" src="' . elgg_get_site_url() . 'mod/multimedia/vendors/video-js/plugins/videojs.watermark.js"></script>';
  $content .= '<script type="text/javascript" src="' . elgg_get_site_url() . 'mod/multimedia/vendors/video-js/plugins/videojs.persistvolume.js"></script>';
  $content .= '</head>';
  $content .= '<body>';
  $content .= $vars['player'];
  $content .= '</body>';
  $content .= '</html>';
  echo $content;
?>