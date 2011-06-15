/**
 * Flickerd Theme JS
 *
 * A ZenPhoto.org Theme
 *
 * This is the Javascript for the functionality of the Flickerd theme for Zenphoto.
 *
 * @author      David Miles <david@thatchurch.com>
 * @package     Flickerd
 * @link        https://github.com/amereservant/FlickerdTheme
 * @version     1.0.2
 */
(function($) {
    /**
     * Animate boxes for the image page
     *
     * @since   1.0.0
     */
    $.fn.toggleBox = function(options) {
        $(this).bind('click', function() {
            var tBox = $(options.toggleBox);
            $this = $(this);
            if($this.hasClass('closed')) {
                var opts = $.extend({ opacity : 1 }, options);
                    
                tBox.animate(opts, 500, function() {
                    $this.removeClass('closed');
                    }
                );
            } else {
                var opts = $.extend({ opacity : 0.1 }, options);
                
                tBox.animate(opts, 500, function() {
                    $this.addClass('closed');
                    }
                );
            }
        });
    }
    
   /**
    * Transistions for the image sizes page for switching between sizes
    *
    * @since   1.0.0
    */
    $.fn.imageSwitch = function() {
        $(this).bind('click', function(e) {
            var src = $(this).attr('href');
            e.preventDefault();
            $('#imgView').fadeOut('fast', function() {
                $('#imageDisplay').addClass('loaderbg');
                var img = new Image();
                $(img).load(function() {
                    $('#imgView').attr('src', src);
                    $('#imgView').fadeIn();
                    $('#imageDisplay').removeClass('loaderbg');
                }).attr('src', src);
            });
        });
    }
    
    /**
     * Toggle Comments sections
     *
     * @since   1.0.0
     */
    $.fn.commentToggle = function(toggleDiv) {
        $(this).bind('click', function(e){
            e.preventDefault();
            var commentDiv = $(toggleDiv);
            
            if(commentDiv.is(':hidden'))
            {
                $(commentDiv).slideDown();
                $(this).text('[ Hide ]');
            } else {
                $(commentDiv).slideUp();
                $(this).text('[ Show ]');
            }
        });
    }
    
   /**
    * Add needed classes to the metadata elements for metadata property toggling
    *
    * @since   1.0.0
    */
    $.fn.modBox = function() {
        $(this).addClass('metatab closed');
        $('#imagemetadata_data').css('display', 'none');
    }
    
   /**
    * Show a larger preview image on mouseover Thumbnails
    *
    * @since    1.0.2
    */
    $.fn.previewImage = function() {
        $(this).bind('mouseover', function() {
            var elem = $(this);
            var timeoutID = window.setTimeout( function() {
                
                var orgsrc = elem.parent('a').attr('href');
                var src    = elem.attr('data-preview_url');
                var width  = elem.width();
                var height = elem.height();
                
                elem.after('<div class="image-overlay-loader loaderbg" style="width:'+ width +'px;height:'+height+'px">'+
                    '<img src="'+orgsrc+'" id="overlayImg" style="display:none" /></div>');
                
                var img = new Image();
                $(img).load(function() {
                    $('#overlayImg').attr('src', src);
                    $('#overlayImg').fadeIn();
                    $('.image-overlay-loader').removeClass('loaderbg').css({'width':img.width, 'height':img.height});
                }).attr('src', src);
                
                $('#overlayImg').bind('mouseleave', function() {
                    $('.image-overlay-loader').remove();
                });
            }, flickerdPreviewDelay);
            elem.bind('mouseleave', function() {
                window.clearTimeout(timeoutID);
           });
        });
    }
    
   /**
    * Initiate element functions on DOM ready
    *
    * @since   1.0.0
    */
    $(document).ready(function() {
        $('#toggle').toggleBox({ toggleBox : '#toggleBox', width : 'toggle', height : 'toggle' });
        $('.metadata_title').modBox();
        $('.metadata_title').toggleBox({ toggleBox : '#imagemetadata_data', height : 'toggle' });
        $('.switch').imageSwitch();
        $('#addComment').commentToggle('#commententry');
        $('#showComments').commentToggle('#comments');
        $('#flkr-feed > ul > li > a > img').previewImage();
    });
})(jQuery);
