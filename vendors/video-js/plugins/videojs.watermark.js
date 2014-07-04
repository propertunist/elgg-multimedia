//console.log('watermark: Start');

(function() {
 // console.log('watermark: Init defaults');
  var defaults = {
        file: 'Owned_Stamp.png',
        xpos: 0,
        ypos: 0,
        xrepeat: 0,
        opacity: 100,
        title: '',
        media_source: ''
    },
    extend = function() {
      var args, target, i, object, property;
      args = Array.prototype.slice.call(arguments);
      target = args.shift() || {};
      for (i in args) {
        object = args[i];
        for (property in object) {
          if (object.hasOwnProperty(property)) {
            if (typeof object[property] === 'object') {
              target[property] = extend(target[property], object[property]);
            } else {
              target[property] = object[property];
            }
          }
        }
      }
      return target;
    };

  /**
   * register the thubmnails plugin
   */
  videojs.plugin('watermark', function(options) {
   // console.log('watermark: Register init');

    var settings, video, div, img, title, media_source;
    settings = extend(defaults, options);

    /* Grab the necessary DOM elements */
    video = this.el();

    // create the watermark element
    div = document.createElement('div');
    img = document.createElement('img');
    link = document.createElement('a');
    div.appendChild(link);
    link.appendChild(img);
    img.className = 'vjs-watermark';
    img.src = options.file;
    link.href = options.link;
    div_title = document.createElement('div');
    div_title.className = 'vjs-title';
    link_title = document.createElement('a');
    div_title.appendChild(link_title);
    link_title.innerHTML = options.title;
    link_title.href = options.media_source;
    //img.style.bottom = "0";
    //img.style.right = "0";
    if ((options.ypos == 0) && (options.xpos == 0)) // Top left
    {
      img.style.top = "0";
      img.style.left = "0";
    }
    else if ((options.ypos == 0) && (options.xpos == 100)) // Top right
    {
      img.style.top = "0";
      img.style.right = "0";
    }
    else if ((options.ypos == 100) && (options.xpos == 100)) // Bottom right
    {
      img.style.bottom = "0";
      img.style.right = "0";
    }
    else if ((options.ypos == 100) && (options.xpos == 0)) // Bottom left
    {
      img.style.bottom = "0";
      img.style.left = "0";
    }
    else if ((options.ypos == 50) && (options.xpos == 50)) // Center
    {
      img.style.top = (this.height()/2)+"px";
      img.style.left = (this.width()/2)+"px";
    }
    div.style.opacity = options.opacity;
	div_title.style.opacity = options.opacity;
    //div.style.backgroundImage = "url("+options.file+")";
    //div.style.backgroundPosition.x = options.xpos+"%";
    //div.style.backgroundPosition.y = options.ypos+"%";
    //div.style.backgroundRepeat = options.xrepeat;
    //div.style.opacity = (options.opacity/100);

    // add the watermark to the player
    video.appendChild(div_title);
    video.appendChild(div);

	var useractive = true;
	player.on('useractive', function(){
		useractive = true;
		div.style.display = "";
		div_title.style.display = "";
	});
	player.on('userinactive', function(){
		useractive = false;
		if (!player.paused()){ //do not hide if player is paused
			div.style.display = "none";
			div_title.style.display = "none";
		}
	});
	player.on('pause', function(){
		div.style.display = "";
		div_title.style.display = "";
	});
	player.on('play', function(){
		if (!useractive) //Don't hide image while user is active i.e. right when play is clicked
		{
			div.style.display = "none";
			div_title.style.display = "none";
		}
	});

  });
})();
