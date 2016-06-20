define(function(require)
{
	var $ = require('jquery');
	var elgg = require('elgg');
  var videojs = require('videojs');
  var logobrand = require('video-logobrand');
  var watermark = require('video-watermark');

  $(document).ready(function() {
    if (multimedia_div = $('#mediaplayer'))
    {
    	videojs.options.flash.swf = $(multimedia_div).attr('data-swf');
      video = document.querySelector('video');
    	player = videojs(video);
      file_title = $(multimedia_div).attr('data-title');
      owner_name = $(multimedia_div).attr('data-owner');
      owner_url = $(multimedia_div).attr('data-owner-url');
      watermark_path = $(multimedia_div).attr('data-watermark');
      view_url = $(multimedia_div).attr('data-href');

    	player.logobrand({
      	"image": elgg.config.wwwroot + 'mod/ureka_theme/_graphics/ureka.png',
        "destination": view_url });

    	player.watermark({
    		"file": watermark_path,
    	 	"url": owner_url,
    		"className":"vjs-watermark",
    		"clickable": true });

    	var link = document.createElement("a");
      link.href = view_url;
      $(link).addClass("vjs-title");
      link.text = file_title + ' - ' + elgg.echo('multimedia:uploaded_by') + ' ' + owner_name;

    	player.el().appendChild(link);

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
    }
  });
});
