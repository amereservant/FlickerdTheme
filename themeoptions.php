<?php  if (!defined('WEBPATH')) die(); 
/**
 * Flickerd Theme - Theme Options
 *
 * Plug-in for theme option handling in the administrative area.
 * The Admin Options page tests for the presence of this file in a theme folder
 * If it is present it is linked to with a require_once call.
 * If it is not present, no theme options are displayed.
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

class ThemeOptions {

   /**
    * Class Constructor
    *
    * This is ran when the class is istantiated and will set any <strong>Theme Options</strong>
    * and standard <strong>Options</strong> defaults.
    *
    * @param    void
    * @return   void
    * @access   public
    * @since    1.0.0
    */
	public function __construct()
	{
		setThemeOptionDefault('Allow_search', 1);
		setThemeOptionDefault('hitcounter',   1);
		setThemeOptionDefault('comments',     1);
		setThemeOptionDefault('ratings',      1);
		setThemeOptionDefault('title_highlight_count', 2);
		setThemeOptionDefault('flkr_css_mtime', time());
		setThemeOptionDefault('gmap_width', 300);
		setThemeOptionDefault('flickerd_preview_delay', 700);
		setThemeOptionDefault('flickerd_use_fb_like', 1);
		setThemeOption('zenpage',      1); // This never get's turned off.
	}
   
   /**
    * Create A Hyperlink
    *
    * This is a duplicate of the printLink() function in ZenPhoto's functions.php file.
    * The difference being is it returns the link as a string instead of printing it.
    *
    * @param    string  $url    The URL for the 'href' attribute
    * @param    string  $text   The text between the anchor tags.
    * @param    string  $title  The title attribute value
    * @param    string  $class  The 'class' attribute
    * @param    string  $id     The 'id' attribute
    * @return   string          The completed HTML anchor link
    * @access   private
    * @since    1.0.0
    */
    private function _getLink( $url, $text, $title=NULL, $class=NULL, $id=NULL )
    {
        return '<a href="' . html_encode($url) . '"' .
	            (($title) ? ' title="' . html_encode($title) . '"' : '') .
	            (($class) ? ' class="' .$class .'"' : '') .
	            (($id) ? ' id="'. $id .'"' : '') . '>' .
	            $text . '</a>';
    }
    
   /**
    * Get Options Supported
    *
    * This is used by ZenPhoto and called when the admin <strong>Options</strong> tab
    * is loaded and will be displayed under the <strong>Options > Theme</strong> tab
    * below the <em>Custom theme options</em> section.
    *
    * For more info, see {@link http://www.zenphoto.org/news/zenphoto-plugin-architecture#plugin-options}.
	*
	* @param    void
	* @return   array   An array of the option names and descriptions the theme supports.
	* @access   public
	* @since    1.0.0
	*/
	public function getOptionsSupported()
	{
		$admin_root = WEBPATH .'/' . ZENFOLDER .'/';
		
		return array(	gettext('Allow search') => array(
		                    'key' => 'Allow_search', 
		                    'type' => OPTION_TYPE_CHECKBOX, 
		                    'desc' => 'Check to enable the <strong>search</strong> form.<br /> If disabled, the search form will be removed.<br />'.
		                              $this->_getLink( $admin_root .'admin-options.php?page=options&tab=search', gettext('Change search options'),
		                              gettext('Search options')),
		                ),
		                gettext('Views counter') => array(
		                    'key'   => 'hitcounter',
		                    'type'  => OPTION_TYPE_CHECKBOX,
		                    'desc'  => 'Check to enable <strong>hitcounter</strong> plugin for this theme.<br />' .
		                                'This is required to display and track the image/album views.<br />' .
		                               'This is independent from the setting on the '. 
		                               $this->_getLink( $admin_root .'admin-plugins.php', gettext('Plugins'), gettext('Plugins Page')) . ' page.<br />' .
		                               $this->_getLink( $admin_root .'admin-options.php?page=options&tab=plugin&show-hitcounter#hitcounter', 
		                                      gettext('Change plugin options'), gettext('Hitcounter Plugin Options')),
		                ),
		                gettext('Comments') => array(
		                    'key'   => 'comments',
		                    'type'  => OPTION_TYPE_CHECKBOX,
		                    'desc'  => 'Check to enable the <strong>comment_form</strong> plugin for this theme.<br />' .
		                               'This is required to display comments and comment forms for images and albums.<br />' .
		                               'This is independent from the setting on the '.
		                               $this->_getLink( $admin_root .'admin-plugins.php', gettext('Plugins'), gettext('Plugins Page')) . ' page.<br />' .
		                               $this->_getLink( $admin_root .'admin-options.php?page=options&tab=plugin&show-comment_form#comment_form',
		                                      gettext('Change plugin options'), gettext('Comment Form Plugin Options')),
		                 ),
		                 gettext('Facebook Like') => array(
		                    'key'   => 'flickerd_use_fb_like',
		                    'type'  => OPTION_TYPE_CHECKBOX,
		                    'desc'  => 'Check to enable the <strong>Facebook Like</strong> to appear in the right sidebar when viewing images.<br />' .
		                               'NOTE: The option setting '. $this->_getLink( $admin_root .'admin-options.php?page=options&tab=gallery',
		                                      gettext('Website URL'), gettext('Website URL')) .' MUST be set or else the <em>Send</em> button will fail.',
		                 ),
		                 gettext('ZenPage') => array(
		                    'key'   => 'zenpage',
		                    'type'  => 2,
		                    'desc'  => '<span style="color:#B70000">THIS IS REQUIRED</span> for the Flickerd theme to work, therefore this field is READONLY' .
		                               ' and cannot be changed here.<br />' .
		                               ' This is independent from the setting on the '.
		                               $this->_getLink( $admin_root .'admin-plugins.php', gettext('Plugins'), gettext('Plugins Page')) . ' page.<br />',
		                 ),
		                 gettext('Ratings') => array(
		                    'key'   => 'ratings',
		                    'type'  => OPTION_TYPE_CHECKBOX,
		                    'desc'  => 'Check to enable the <strong>rating</strong> plugin for this theme.<br />' .
		                               'This is required to display ratings for photos and albums.<br />' .
		                               'This is independent from the setting on the '.
		                               $this->_getLink( $admin_root .'admin-plugins.php', gettext('Plugins'), gettext('Plugins Page')) . ' page.<br />',
		                               $this->_getLink( $admin_root .'admin-options.php?page=options&tab=plugin&show-rating#rating',
		                                      gettext('Change plugin options'), gettext('Ratings Plugin Options')),
		                 ),
		                 gettext('Title Highlight') => array(
		                    'key'   => 'title_highlight_count',
		                    'type'  => 2,
		                    'desc'  => 'Specify how many letters of the Gallery title should be highlighted the <span style="color:#FF0084">Flickr Pink</span> color, '.
		                               'counting from <em>right-to-left</em>.<br />  If the number is <strong>0</strong>, no letters will be highlighted.',
	                    ),
	                    gettext('GoogleMap Width') => array(
	                        'key'   => 'gmap_width',
	                        'type'  => 2,
	                        'desc'  => 'Specify the width <em>(in pixels)</em> for the Google Map display for individual images.  Default is <strong>300px.</strong><br />'.
	                                   'Requires the GoogleMap Plugin to be enabled.<br />' .
	                                   'Additional settings can be found on the ' .
	                                   $this->_getLink( $admin_root .'admin-options.php?page=options&tab=plugin&show-GoogleMap#GoogleMap', 
	                                    gettext('GoogleMap plugin options'), gettext('GoogleMap Plugin Options')) .' page.',
	                    ),
	                    gettext('Preview Image Delay') => array(
	                        'key'   => 'flickerd_preview_delay',
	                        'type'  => 2,
	                        'desc'  => 'The number of milliseconds for the image preview to appear.<br />' .
	                                   'When hovering over a thumbnail in the flickerd stream, this is how long it will wait to load and show the larger image.',
	                    ), 
             );
	}
    
   /**
    * Handle Custom Options
    *
    * This is called when 'type' value of an option is '2' and allows for customizing the input field.
    * This method must output the correct HTML syntax for the input field.
    *
    * @param    string  $option         The option name or 'key' parameter for the option.
    * @param    mixed   $currentValue   The current value for the option
    * @return   string                  Outputs the string necessary for the option field if it exists.
    * @access   public
    * @since    1.0.0
    */
	public function handleOption( $option, $currentValue )
	{
		if( $option == 'zenpage' )
		    echo '<input type="checkbox" id="zp_plugin_zenpage" value="1" name="zp_plugin_zenpage" ' .
	             ($currentValue == 1 ? 'checked':'') .' READONLY />';
	    
	    if( $option == 'title_highlight_count' )
	        echo '<input type="text" id="title_highlight_count" value="'. $currentValue .'" size="3" ' .
	             'name="title_highlight_count" />';
	    
	    if( $option == 'gmap_width' )
	        echo '<input type="text" id="gmap_width" value="'. $currentValue .'" size="5" name="gmap_width" />';
	    
	    if( $option == 'flickerd_preview_delay' )
	        echo '<input type="text" id="flickerd_preview_delay" value="'. $currentValue .'" size="3" name="flickerd_preview_delay" />';
	    return;
	}
}

