/**
 * Flickerd Theme JS
 *
 * A ZenPhoto.org Theme
 *
 * This is the Javascript for the functionality of the Flickerd theme for Zenphoto.
 *
 * @author      David Miles <david@thatchurch.com>
 * @package     Flickerd
 * @version     1.0.0
 */
(function($) {
    /**
     ** Duplicates & replaces the function on the image.php page
     **
    $.fn.toggleComments = function() {
        $(this).bind('click', function() {
            var commentDiv = $('#comments');
            commentDiv.fadeToggle();
        });
    }
    */
    
    /**
     * Animate boxes for the image page
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
    */
    $.fn.modBox = function() {
        $(this).addClass('metatab closed');
        $('#imagemetadata_data').css('display', 'none');
    }
    
   /**
    * Initiate element functions on DOM ready
    */
    $(document).ready(function() {
        $('#toggle').toggleBox({ toggleBox : '#toggleBox', width : 'toggle', height : 'toggle' });
        $('.metadata_title').modBox();
        $('.metadata_title').toggleBox({ toggleBox : '#imagemetadata_data', height : 'toggle' });
        $('.switch').imageSwitch();
        $('#addComment').commentToggle('#commententry');
        $('#showComments').commentToggle('#comments');
    });
})(jQuery);
