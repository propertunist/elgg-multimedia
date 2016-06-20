/*
 * multimedia AMD JS init
 */
define(function(require)
{
    var $ = require('jquery');
    var elgg = require('elgg');

    copyToClipboard = function(text) {
        window.prompt(elgg.echo('multimedia:copy_prompt'), text);
    };

    $(document).ready(function(){
        // replace audio/video player in multimedia river entries
        $(".elgg-river-media a").click(function(e){
            e.preventDefault();
            alert($(this).attr('data-media'));
            $(this).text($(this).attr('data-media'));
        });

        var embedSizes = {560:315, 640:360, 853:480, 1280:720},
            embedWidth = 0,
            embedHeight = 0;

        $('.embed-width-select').change(function(){
            embedWidth = $('.embed-width-select').val();
            embedHeight = embedSizes[embedWidth];

            if ((embedHeight == 0)||(embedHeight == ''))
            {
                embedWidth = 640;
                embedHeight = 360;
            }
            $("#embedcode").val($("#embedcode").val().replace(/width="\d+"/g, 'width=\"' + embedWidth + '\"'));
            $("#embedcode").val($("#embedcode").val().replace(/height="\d+"/g, 'height=\"' + embedHeight + '\"'));
        });
    });
});
