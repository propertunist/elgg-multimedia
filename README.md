multimedia for elgg 1.8
-----------------------

author: ura soul
website: https://www.infiniteeureka.com

summary
-------

this plugin uses the multimedia media player to play audio and video files that are uploaded to the standard elgg file plugin.

features:
--------

* generates thumbnail screenshot for uploaded videos, using avconv (previously known as FFmpeg).
* plays many video/audio web file formats.
* full screen mode.
* multiple platforms supported - native, browser, ios, android, html5, flash.
* supports watermark symbol.
* places the file title over the video in appropriate ways.
* adds an embed code box underneath the media player - to allow iframe embeds of your media to be added to external websites.
* supports pseudo streaming (requires server configuration). 
* adds thumbnails for files to the river create events.

todo:
-----

* add support for playlists
* integrate with videolist
* allow a way to choose the best screenshot/thumbnail - either choice of 3 screenshots or some other way.
* add a thumbnail processing queue
* allow upload of custom screenshot
* integrate with an upload manager, to allow resume functionality of large uploads and also progress bars
* add sidebar box for 'other videos in this zone' and 'other audio in this zone'
* detect streaming capabalities in admin panel
* allow playback of files from within river
* possibly add support for media detection in other social sites, so that elgg video files that are shared to these sites are detected and played in news feeds, in place of showing a thumbnail.


install notes
-------------

1. upload the extracted plugin files to your server's 'mod' folder.
2. activate the plugin via the admin->plugins page.
3. edit the plugin options page via the admin area. 
4. ensure that the avconv package is installed on your server and that the path to avconv is correct for your server (without this, the video thumbnails will not be created).
5. ensure that the 'passthru' command is allowed in the server's php.ini file. passthru allows the php code to trigger command line processes - this is necessary to initiate avconv when creating video thumbnails.

