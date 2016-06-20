<?php
/**
* Elgg multimedia Plugin
* @package multimedia
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
* @author ura soul
*/
// English language file
return array(
		// Processing errors
		'multimedia:real_path_error' => "no file path was available for the file",
		'multimedia:moov_atom:success' => "moov atom successfully moved: %s",
		'multimedia:moov_atom:fail' => "moov atom was not moved: %s",
		// river keys
		'river:create:object:file:video' => "%s uploaded a video file: %s",
		'river:create:object:file:document' => "%s uploaded a document file: %s",
		'river:create:object:file:audio' => "%s uploaded an audio file: %s",
		'river:create:object:file:image' => "%s uploaded an image file: %s",
		'river:create:object:default' => "%s added %s",
		// Size options
		'multimedia:player:size:desc' => "The default player window size for video is 640 x 480.<p>The height and width of both the video and audio player can be set independently.</p>",
		'multimedia:player:size' => "Player Size",
		'multimedia:audio:size' => "Select audio player size (Default H100 x W480):",
		'multimedia:audio:size:height' => "Audio player height",
		'multimedia:audio:size:width' => "Audio player width",
		'multimedia:video:size' => "Select video player size (Default H480 x W640):",
		'multimedia:video:size:height' => "Video player height",
		'multimedia:video:size:width' => "Video player width",
        'multimedia:river_size:video:height' => "Video player height (in river)",
        'multimedia:river_size:video:width' => "Video player width (in river)",
        'multimedia:river_size:audio:height' => "Audio player height (in river)",
        'multimedia:river_size:audio:width' => "Audio player width (in river)",
        'multimedia:audio:river:size' => "select audio player size for river items (default h120 x w320)",
        'multimedia:video:river:size' => "select video player size for river items (default h240 x w320)",
		'multimedia:size:option' => "Note: To reset the height and width to default, empty the fields and save.",
		'multimedia:off' => "Off",
		// Autoplay
		'multimedia:autoplay:autoplay' => "Autoplay/Autostart",
		'multimedia:autoplay:desc' => "Both the video and audio players can be independently set to start automatically or require the user to manually start the media file. By default, autoplay/autostart is set to <em>Off</em>.",
		'multimedia:autoplay:audio' => "Select autoplay for audio files:",
		'multimedia:autoplay:video' => "Select autoplay for video files:",
		'multimedia:autoplay:off' => "Off",
		'multimedia:autoplay:on' => "On",
		'multimedia:admin:river_autoplay' => "Select autoplay for river files - after thumbnails are clicked:",
		// Encoding
		'multimedia:rawurlencode' => "Rawencode",
		'multimedia:urlencode' => "Encode",
		'multimedia:encoding:desc' => "Some browser/player combinations fail to play media files with spaces in the file name. If you have this problem, try selecting one of these options. By default, URL Encoding is set to <em>Off</em>.<br /><br /><em><b>encode:</b></em> This option returns a filename in which all non-alphanumeric characters except <em>-_.</em> have been replaced with a percent (%) sign followed by two hex digits and spaces encoded as plus (+) signs. This differs from the » RFC 3986 encoding (see rawencode) in that for historical reasons, spaces are encoded as plus (+) signs.<br /><em><b>rawencode:</b></em> This option returns the file name in which all non-alphanumeric characters except
   <em>-_.~</em> have been replaced with a percent
   (<em>%</em>) sign followed by two hex digits.  This is the
   encoding described in RFC 3986.",
		'multimedia:encode:encoding' => "Select URL encode function to: Encode, Rawencode or OFF.<br />This affects both the audio and video player simultaneously:",
		'multimedia:encode:encode' => "URL Encoding",
		'file:screenshotfailed' => "Creation of video screenshot failed",
		'multimedia:avconv:file_not_found' => "Video screenshot: error in video path",
	    'multimedia:admin:avconv_path' => "The AVCONV package (previously known as FFMPEG) is required to be installed on your server, to allow thumbnails/screenshots to be created from the uploaded videos. Enter the path to the AVCONV executable file on your server (default = 'usr/bin'):",
	    'multimedia:admin:encoder_name' => "Name of the video encoder package available on the server (either ffmpeg or avconv):",
		'multimedia:uploaded_by' => "uploaded by",
		'multimedia:thumbnail:options' => 'video thumbnails',
		'multimedia:admin:thumb_percent' => 'Choose a frame in the video to use as the source for the thumbnail/screenshot (Enter a percentage between 0 and 100):',
		// embedding
		'multimedia:embed_title' => "An embedded media file",
		'multimedia:embed:invalid_code' => "<h2>That code does not point to a media file that can be embedded.</h2>Ensure you are using the correct embed code by visiting <a href='" . elgg_get_site_url() . "'>the main website</a>.",
		'multimedia:embed:embed-code' => "Embed code: ",
		// watermark
		'multimedia:watermark:options' => "watermarking",
		'multimedia:admin:watermark_path' => "Enter a URL path to an image that can be used for a visual watermark symbol to appear above the playback layer in the media players (image is sized via css and can be resized via css)",
    'multimedia:copy_button' => 'copy the embed code to your clipboard',
    'multimedia:copy_prompt' => 'to copy code to clipboard: hold ctrl+c and then press \'enter\'',
    'multimedia:widths_for_embed' => 'recommended widths (in pixels) to use when embedding the media player',
    'admin:upgrades:videolist_type' => 'upgrade videolist items to allow them to be combined with videos from the elgg file plugin',
    'admin:upgrades:file_thumbs' => 'generate new thumbnails for all videos stored in the files plugin',
);
