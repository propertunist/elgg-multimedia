/*
 *  Copyright (c) 2013 Funny or Die, Inc.
 *  http://www.funnyordie.com
 *  https://github.com/funnyordie/videojs-relatedCarousel/blob/master/LICENSE.md
 */

(function(vjs) {
  var extend = function(obj) {
      var arg, i, k;
      for (i = 1; i < arguments.length; i++) {
        arg = arguments[i];
        for (k in arg) {
          if (arg.hasOwnProperty(k)) {
            obj[k] = arg[k];
          }
        }
      }
      return obj;
    },
    defaults = [
      {
        imageSrc: '',
        title:   '',
        url:     ''
      }
    ];

  vjs.plugin('relatedCarousel', function(options) {
    var player = this,
        settings = extend({}, defaults, options || {});

    var holderDiv = document.createElement('div');
    holderDiv.className = 'vjs-related-carousel-holder';

    var title = document.createElement('h5');
    title.innerHTML = 'More Videos';
    holderDiv.appendChild(title);

    player.el().appendChild(holderDiv);

    for (var i in settings) {
      var img = document.createElement('img');
      img.src = settings[i].imageSrc;
      img.className = 'vjs-carousel-thumbnail';
      img.alt = settings[i].title;

      var anchor = document.createElement('a');
      anchor.href = settings[i].url;
      anchor.appendChild(img);
      anchor.title = settings[i].title;

      holderDiv.appendChild(anchor);
    }


    /* Menu Button */
    var RelatedCarouselButton = vjs.Button.extend({
      init: function(player, options) {
        vjs.Button.call(this, player, options);
      }
    });

    RelatedCarouselButton.prototype.buttonText = 'Related Videos';

    RelatedCarouselButton.prototype.buildCSSClass = function(){
      return 'vjs-related-carousel-button ' + vjs.Button.prototype.buildCSSClass.call(this);
    };

    RelatedCarouselButton.prototype.onClick = function(e){
      holderDiv.classList.toggle('active');
    };

    player.ready(function(){
      var button = new RelatedCarouselButton(player);
      player.controlBar.addChild(button);
    });
  });
}(window.videojs));



