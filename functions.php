<?php
/**
 * Flickerd Theme Functions
 *
 * These functions provide the custom functionality of the Flickerd theme.
 * It also loads and istantiates the {@link flickerd} class, which further extends
 * the theme's functionality and is used throughout the theme.
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
define('FLICKERD_PATH', realpath(dirname(__FILE__)) . DS);

require_once FLICKERD_PATH . 'flickerd.class.php';


/**
 * Get The EXIF Camera Model
 *
 * Returns the Camera Model from the EXIF data and also compares it against a custom list
 * of camera models to correct oddly-named camera models.
 * You may add additional camera models to the $models array, but please be sure to
 * contact the Theme author so it can be added to the theme for others to benefit from
 * if it's a correction.
 *
 * @param   void
 * @return  string      The camera model if found, (bool)false if not
 * @since   1.0.0
 */
function getEXIFCameraModel()
{
	$models = array(
	    'SQ907B EZ-Cam'     => 'Fisher-Price Kid Tough Camera',
	);
	
	$t_EXIF = getImageMetaData();
	$camera = isset($t_EXIF['EXIFModel']) ? $t_EXIF['EXIFModel'] : '';
	if(isset($models[$camera])) $camera = $models[$camera];
	
	return strlen($camera) > 0 ? $camera : false;
}

/**
 * Calculate Image Width/Height
 *
 * This calculates the width and height attributes for an <img> element since there
 * doesn't appear to be a built-in function to do this for a custom image.
 * Since it makes for faster browser load times for images to have these, then this
 * function does the dirty work.
 *
 * @param   $h      int     The original image height
 * @param   $w      int     The original image width
 * @param   $max    int     The new images max width/height.
 *                          This will be the largest value for either one and the aspect
 *                          ratio will automatically determine the smaller value.
 * @return  string          The attributes like so: 'width="300" height="230"';
 * @since   1.0.0
 */
function getImageWH($h, $w, $max)
{
    $ratio  = $h > $w ? ($h/$w) : ($w/$h);
    $width  = $h > $w ? floor($max/$ratio) : $max;
    $height = $h < $w ? floor($max/$ratio) : $max;
    
    return 'width="'. $width .'" height="'. $height .'"';
}


/**
 * Create Comment Field
 *
 * This function is used to create the form input fields for the comment form and reduce
 * the redundant syntax in the template file.
 * It is used in {@link comment_form.php}.
 *
 * @param   string  $name       The name attribute for the input field
 * @param   string  $id         A unique id attribute for the input field
 * @param   string  $class      Any CSS classes that should be applied to the element
 * @param   string  $value      The value for the field
 * @param   string  $type       Form input type.  Accepted values are 'text'.  Default is 'text'.
 * @param   bool    $disabled   If the field should be disabled from user-input
 * @return  string              Prints out the HTML syntax for the field unless an error occurs
 */
function flkrPrintCommentField( $name, $id, $class, $value, $type='text', $disabled=false )
{
    // Check for a valid type, return false on failure
    if(!in_array($type, array('text'))) return false;
    
    $format    = '<input type="%s" name="%s" id="%s"%s value="%s"%s />';
    $dis_class = ' disabled-field'; // CSS class applied to disabled fields for styling purposes
    
    $output = sprintf($format, $type, $name .($disabled ? '-disabled':''),
        $id .($disabled ? '-disabled':''), ' class="inputbox'. ($disabled ? $dis_class:'') .'"', 
        $value, ' size="22"'.($disabled ? ' disabled':''));
    
    if($disabled) {
        $output .= "\n";
        $output .= sprintf($format, 'hidden', $name, $id, '', $value, '');
    }
    
    echo $output;
    return true;
}

/**
 * Print Flickerd Gallery Title
 *
 * This highlights part of the gallery title with the pink color like the Flickr title.
 * It replaces the built-in function getGalleryTitle() and should be used in place of it
 * for the header <h1></h1> tags.
 *
 * The text will be wrapped with <span></span> tags with the class <strong>flkrpink</strong>,
 * which should be defined in the stylesheet.
 *
 * How many letters to change the color on can be set in the Admin panel under 'Options > Theme'.
 *
 * @param   void
 * @return  string      HTML string with the gallery title highlighted
 * @since   1.0.0
 */
function printFlkrTitle()
{
    global $flkr;
    
    $color = '<span class="flkrpink">'. substr(getGalleryTitle(), -$flkr->getOption('title_highlight_count')) . '</span>';
	echo substr_replace(getGalleryTitle(), $color, -$flkr->getOption('title_highlight_count'));
}

/**
 * Make Anchor Link
 *
 * Simple as that, makes an anchor link.
 *
 * @param   string  $url        The URL for the anchor link
 * @param   string  $title      The title attribute
 * @param   string  $content    The content that goes between the anchor link tags
 * @return  string              Completed anchor link
 * @since   1.0.0
 */
function makeAnchorLink( $url, $title, $content )
{
    printf('<a href="%s" title="%s">%s</a>', $url, $title, $content);
}

/**
 * Strip Paragraph Tags
 *
 * This is used primarily in the "Detail" view for the images to remove the <p></p> tags
 * from the description so the description is shorter and looks better.
 *
 * @param   string  $desc   The description to strip tags from
 * @return  string          The stripped string with the <p></p> tags removed
 * @since   1.0.0
 */
function stripPTags( $desc )
{
    $out = str_replace('</p>', htmlentities('&nbsp;'), $desc);
    $out = str_replace('<p>', '', $out);
    return $out;
}


$flkr = new flickerd;
