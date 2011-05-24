<?php
/**
 * Flickerd Class - ZenPhoto Theme
 *
 * PHP5
 *
 * This class provides the core functionality for the Flickerd ZenPhoto theme.
 * It provides a group of methods that tailor this theme to resemble Flickr.
 *
 * It is a rewrite of the Flickrish theme by {@link http://code.google.com/p/flickrish/ Matt Munday},
 * so credit is due to him for the foundation material used to make this theme.
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.0
 */
class flickerd
{
   /**
    * ALL Images
    *
    * @var      array
    * @access   private
    * @since    1.0.0
    */
    private $_imgs = array();
    
   /**
    * Image Count
    *
    * @var      int
    * @access   public
    * @since    1.0.0
    */
    public $count = NULL;
    
   /**
    * Theme Name - (from getOption('current_theme'))
    *
    * @var      string
    * @access   private
    * @since    1.0.0
    */
    private $_theme_name = NULL;
   
   /**
    * Loaded Options (Stores already-loaded options)
    *
    * @var      array
    * @access   private
    * @since    1.0.0
    */
    private $_options = array();
    
   /**
    * Theme Stylesheets
    *
    * @var      array
    * @access   public
    * @since    1.0.0
    */
    public $stylesheets = array();
   
   /**
    * Class Constructor
    *
    * @param    void
    * @return   void
    * @since    1.0.0
    */
    public function __construct()
    {
        $this->_loadSettings();
        
        // Add them in the order they are to be loaded.
        $this->stylesheets = array(
                                    array('file'    => 'reset.css',
                                          'media'   => 'all'
                                    ),
                                    array('file'    => 'theme.css',
                                          'media'   => 'screen'
                                    ),
                                );
    }
    
   /**
    * Get Recent Images
    *
    * Fetches ALL uploaded images and returns them sorted by the order they were uploaded.
    * This is the core function for creating the image feed for the Flickerd theme.
    *
    * @param   void
    * @return  array   An array containing all of the images for the gallery.
    * @since   1.0.0
    */
    private function _getFlckImages()
    {
        global $_zp_gallery;
	    if( zp_loggedin() )
	    {
		    $albumWhere     = " AND albums.folder != ''";
		    $imageWhere     = "";
		    $passwordcheck  = "";
	    } 
	    else
	    {
		    $passwordcheck  = '';
		    $albumscheck    = query_full_array("SELECT * FROM " . prefix('albums'). " ORDER BY title");
		
		    foreach( $albumscheck as $albumcheck )
		    {
			    if( !checkAlbumPassword($albumcheck['folder'], $hint) )
			    {
				    $albumpasswordcheck = " AND albums.id != ".$albumcheck['id'];
				    $passwordcheck      = $passwordcheck.$albumpasswordcheck;
			    }
		    }
		    $albumWhere = " AND albums.folder != '' AND albums.show = 1". $passwordcheck;
		    $imageWhere = " AND images.show = 1";
	    }
	    
	    
	    $images = query_full_array("SELECT images.albumid, images.filename AS filename, images.mtime as mtime, images.title AS title, " .
	            "albums.folder AS folder, images.show, albums.show, albums.password FROM " .
	        	prefix('images') ." AS images, ". prefix('albums') ." AS albums " .
			    " WHERE images.albumid = albums.id " . $imageWhere . $albumWhere .
			    " AND albums.folder != '' ORDER BY images.id DESC");
		
	    foreach( $images as $imagerow )
	    {
		    $filename       = $imagerow['filename'];
		    $albumfolder2   = $imagerow['folder'];
		    $desc           = $imagerow['title'];
		
		    // Album is set as a reference, so we can't re-assign to the same variable!
		    $image          = newImage( new Album($_zp_gallery, $albumfolder2), $filename );
		    $imageArray[]   = $image;
	    }
	    
	    $this->_imgs = $imageArray;
	    $this->count = count($imageArray);
	    return $imageArray;
    }
    
   /**
    * Print Flickred Image Feed
    *
    * This creates an unordered list of all of the images based on the current page
    * and the 'images_per_page' setting and also calls the {@link _flkrCreatePagination()}
    * method to add pagination below the images for navigation.
    *
    * This should probably ONLY be called on the index.php page since it's made to mimick
    * Flickr's default view and the pagination conflicts with other pagination on the same
    * page at this point.  See {@see _flkrCreatePagination()} notes for more details on that.
    *
    * @param    void
    * @return   string      Prints the HTML for the image feed and the pagination.
    * @access   public
    * @since    1.0.0
    * @TODO     Fix the search URL for the date added links.
    */
    function printFlkrFeed()
    {
        
	    $images     = $this->_getFlckImages();
	    $width      = 240;
	    $height     = 240;
	    $content    = "\n" .'<div id="flkr-feed">'. "\n\t<ul>\n";
	
        $lif = <<<________EOD
            <li>
                <a href="%s" title="%s"><img src="%s" alt="%2\$s" %s /></a>
                <h4>%2\$s</h4>
                <p class="pic-info">Uploaded on %s</p>
                <p class="pic-info-bottom">%s | %s Comments</p>
            </li>
________EOD;

        /**
         * Date Uploaded search doesn't work because it needs to search for all dates
         * within the same day range.  Searching by mtime will only return the one image.
         * It has been removed for now and may or may not be re-added later depending
         * on how practical/complicated it is to implement this feature.
         */

        $num_per_page   = getOption('images_per_page');
        $offset         = getCurrentPage() - 1;
        $offset         = $offset * $num_per_page;
        
        $images = array_slice($images, $offset, $num_per_page);
        
	    foreach ( $images as $image ) 
	    {
		    $link   = html_encode( $image->getImageLink() );
		    $title  = html_encode( $image->getTitle() );
		    $img    = html_encode( $image->getCustomImage(240, $width, $height, NULL, NULL, NULL, NULL, TRUE, NULL, $title, 'feed-thumb') );
		    $date   = zpFormattedDate( getOption('date_format'), $image->data['mtime'] ); // Date Uploaded
            $hits   = $image->get("hitcounter");
		
		    if( empty($hits) ) $hits = '0';
		
		    $hits  .= $hits == '1' ? ' View':' Views';
		    //$search = getSearchURL( "{$image->data['mtime']}", '', 'mtime', '' ); // UNIX timestamp, therefore only one result
		    $count  = $image->getCommentCount();
		    $size   = getImageWH( $image->getHeight(), $image->getWidth(), 240 );
		    $content .= sprintf( $lif, $link, $title, $img, $size, $date, $hits, $count );
		}
	    $content .= "\n\t</ul>\n</div><!-- #flkr-feed -->\n";
	    
	    echo $content;
	    $this->_flkrCreatePagination('&laquo; Prev', 'Next &raquo;', 'pagelist', 'pagelist');
	    return;
    }
    
   /**
    * Flickerd Feed Pagination
    *
    * Prints a full page navigation including previous and next page links with a list 
    * of all pages in between for the Flickerd feed page.
    * This is a rewrite of the printPageListWithNav() function.
    *
    * NOTE: This will effect any other pagination on the same page as the page this is called on,
    * so therefore this should be the only pagination on the page it's called on.
    *
    * @param    string  $prevtext   The text to use for the previous link
    * @param    string  $nexttext   The text to use for the next link
    * @return   string              Prints the HTML for the generated pagination
    * @access   private
    * @since    1.0.0
    */
    private function _flkrCreatePagination($prevtext, $nexttext)
    {
	    // Make sure the $_imgs property has been populated, if not, do so.
	    if( count($this->_imgs) < 1 ) {
            if( !is_array($this->_getFlckImages()) ) return false;
        }
        
        $max_per_page   = getOption('images_per_page');
	    $total          = ceil($this->count/$max_per_page); // Total # of pages
	    $current        = getCurrentPage();
	    
	    // Define sprintf formats	    
	    $navf  = "<div id=\"flkr-pagination\" class=\"pagelist\">\n\t<ul class=\"pagelist\">%s\t</ul>\n</div>";
        $lif   = "\t\t<li%s>%s</li>\n";
        $linkf = '<a href="%s" title="%s">%s</a>';
        
        // Create Previous Link
        $prevlink   = hasPrevPage() ? sprintf($linkf, getPageURL($current-1, $total), gettext('Previous Page'), $prevtext) : 
                      '<span class="disabledlink">'. $prevtext . '</span>';
        $li_content = sprintf($lif, ' class="prev"', $prevlink);
        
        // Create First Link - Not in loop because of pagination count limit and we always show 1
        $flink       = $current == '1' ? '1' : sprintf($linkf, getPageURL(1, $total), gettext("Page 1"), 1);
	    $li_content .= sprintf($lif, ' class="'. ($current == '1' ? 'current':'first') .'"', $flink);
        
        /**
         * Determine the start/stop values for the paginated range.
         * Since the values will be surrounded by '...' values depending on the range,
         * we need to adjust how many pagniated values should be generated.
         */
        $start  = $current > 5 && $total > 10 ? ($current - 2 > $total - 6 ? $total-6 : $current-2) : 2;
        $end    = $total > 10 && $current > 4 ? ($current + 3 > $total ? $total : $current + 3) : ($total > 9 ? 9 : $total);
        
        if($start > 2) $li_content .= sprintf($lif, '', '...');
        
	    for($i=$start; $i <= $end; $i++)
	    {
		    if($i == $total) continue; // Not sure why, but $i < $end doesn't work (must be <= )
		                               // when the last item is selected so this must be used.
		    $cur = $i == $current;
		    if(!$cur) $title = sprintf(ngettext('Page %1$u','Page %1$u', $i),$i);
		
		    $li_content .= sprintf($lif, ($cur ? ' class="current"' : ''), ($cur ? $i : sprintf($linkf, getPageURL($i, $total), $title, $i)) );
	    }
	    
	    if($end + 1 < $total) $li_content .= sprintf($lif, '', '...');
	    
	    // Create Last Item Link
	    $llink       = $current == $total ? $total : sprintf($linkf, getPageURL($total, $total), gettext('Page '. $total), $total);
	    $li_content .= sprintf($lif, ' class="'. ($current == $total ? 'current':'last') .'"', $llink);
	    
	    // Create Next Link
        $nextlink    = $current < $total ? sprintf($linkf, getPageURL($current+1, $total), gettext('Next Page'), $nexttext) :
                       '<span class="disabledlink">'. $nexttext .'</span>';
        $li_content .= sprintf($lif, ' class="next"', $nextlink);
	   
	    printf($navf, $li_content);
    }
    
   /**
    * Get Theme Option
    *
    * This is used to check the options for the Flickerd theme, which corresponds with
    * {@link themeoptions.php} and the values set there.
    * These options are configured in the admin panel via 'Options' > 'Theme'.
    *
    * The second parameter, $is_bool, provides the ability to specify if the return value
    * should be converted to a bool true|false except when it's a NULL value.  This is useful
    * for conditional testing such as:
    * <code>
        if($flkr->getOption( 'example_option', true)) { // Do something here... }
      </code>
    *
    * This will also compare the option to any associated plugin options (if applicable)
    * and perform any logic associated with them if neccessary, that way the logic is
    * kept to a minimum in the design and performed here instead.
    *
    * @param    string  $option     The name of the option being checked.
    * @param    bool    $is_bool    Should the value return as a (bool)true|false ?
    *                               This is useful for checkbox values where the value will be
    *                               either '1' or '0'.
    * @return   array|bool          An array|bool is returned if the option exists.
    *                               False will be returned if the option doesn't
    *                               exists, such as a mis-spelled $option parameter.
    * @access   public
    * @since    1.0.0
    */
    public function getOption( $option, $is_bool=false )
    {
        // An array of abbreviated options.
        // The key value can be used for calling the option instead of spelling it out.
        $abbreviations = array(
            'search'     => array( 'Allow_search',           1),
            'hitcounter' => array( 'zp_plugin_hitcounter',   THEME_PLUGIN|1 ),
            'comments'   => array( 'zp_plugin_comment_form', 5|ADMIN_PLUGIN|THEME_PLUGIN ),
            'zenpage'    => array( 'zp_plugin_zenpage',      THEME_PLUGIN|1 ),
            'ratings'    => array( 'zp_plugin_rating',       5|ADMIN_PLUGIN|THEME_PLUGIN ),
        );
        
        if(isset($abbreviations[$option]))
            $opt = $abbreviations[$option];
        
        // Get theme name this way for flexibility if theme get's renamed.
        if(is_null($this->_theme_name)) 
            $this->_theme_name = getOption('current_theme');
        
        // Check if the option has already been retrieved first
        if(!isset($this->_options[$option]))
        {
            $value = getThemeOption($option, NULL, $this->_theme_name);
            
            if(isset($abbreviations[$option])) {
                $opt[] = $value;
            } else {
                $opt = $value;
            }
            $this->_options[$option] = $opt;
        }
        
        $value = $this->_options[$option];
        
        if(is_null($value)) return FALSE;
        
        if($is_bool) {
            if(!is_array($value)) return (bool)$value;
            return (bool)$value[2];
        }
        return $value;
    }
    
   /**
    * Print Flickerd-styled Comments
    *
    * This method is used for styling comments for the current image.
    * It should be passed the value of <strong>$_zp_current_image->getComments()</strong>
    * so it can then format the comments if any exist.
    *
    * The main purpose of this is to replace the default output if printCommentForm()'s first
    * parameter is set to true so that javascript action can be added to toggle the comments.
    *
    * @param    array   $comments   An array of comments, the value of $_zp_current_image->getComments()
    * @return   string              Prints the formatted comments HTML
    * @access   public
    * @since    1.0.0
    * @TODO     Add integration with the 'options > plugin > comment_form' options so the
    *           options there are observed and honored such as <em>Toggled comment block</em>.
    */
    public function printFlkrComments( $comments )
    {
        $count = count($comments);
        if(!is_array($comments)) return false;
        if($count < 1)
        {
            echo '<h3>No Comments</h3>';
            return;
        }
        $output  = '<h3>'. $count .' Comment'. ($count > 1 ? 's':'') .': <a href="#" id="showComments">[ Show ]</a></h3>' ."\n";
        $output .= '<ul id="comments">' ."\n";
        
        $format  = "\t". '<li class="comment" id="comment_%s">';
        $format .= '<p class="commentinfo"><strong>%s</strong> said on <span class="commentdate">%s</span></p>';
        $format .= '<p class="commenttext iblock">%s</p></li>'. "\n";
        
        foreach($comments as $comment)
        {
            if( isset($comment['website']) && strlen($comment['website']) > 0 )
                $name = '<a href="'. htmlentities($comment['website']) .'" target="_blank()" rel="nofollow">' .
                        $comment['name'] .'</a>';
            else
                $name = $comment['name'];
            
            $output .= sprintf($format, $comment['id'], $name, date('F j, Y', strtotime($comment[5])), $comment['comment']);
        }
        
        $output .= "</ul>\n";
        echo $output;
        return;
    }
    
   /**
    * Compress & Combine Stylesheets
    *
    * This method attempts to combine and compress the theme's stylesheets and write
    * a new file if the theme's css directory is writable.
    * If it's not, it will return the syntax for all of the stylesheets
    *
    * @param    void
    * @return   string      Stylesheet link tags for the compressed or uncompressed stylesheets
    * @access   public
    * @since    1.0.0
    */
    public function getStylesheets()
    {
        global $_zp_themeroot;
        $format   = '<link type="text/css" rel="stylesheet" href="%s" media="%s" />' ."\n";
        $css_dir  = 'css/';
        $css_path = realpath(FLICKERD_PATH . $css_dir) . DS;
        $css_url  = $_zp_themeroot .'/'. $css_dir;
        
        if(count($this->stylesheets) < 1) return false;
        
        $output = '';
        
        if(!is_dir($css_path) || !is_writable($css_path))
        {
            foreach($this->stylesheets as $sheet)
            {
                $output .= sprintf($format, $css_url . $sheet['file'], $sheet['media']);
            }
            return $output;
        }
        
        if(file_exists($css_path . 'stylesheet.css')) {
            
            $this->getOption('flkr_css_mtime');
            $mtime = 0;
            
            // Iterate over the stylesheets and add the mtime to detect any changes
            foreach($this->stylesheets as $sheet)
            {
                $mtime += filemtime($css_path . $sheet['file']);
                
            }
            
            // No changes, so just use previously-generated stylesheet file
            if( $mtime == $this->getOption('flkr_css_mtime') )
            {
                $output .= sprintf($format, $css_url . 'stylesheet.css', 'all');
                return $output;
            }
        }
        
        $mtime = 0;
        
        // Make new stylesheet.css file
        ob_start();
            ob_start( array($this, '_flkrcompress') );
            foreach($this->stylesheets as $sheet)
            {
                $mtime += filemtime($css_path . $sheet['file']);
                @include $css_path . $sheet['file'];
            }
            ob_end_flush();
        $css = ob_get_clean();
        
        // Store the new mtime
        setThemeOption('flkr_css_mtime', $mtime);
        
        // Write new stylesheet.css file
        file_put_contents($css_path .'stylesheet.css', $css);
        
        return sprintf($format, $css_url .'stylesheet.css', 'all');
    }
    
   /**
    * Load and Set Theme Options
    *
    * This method is called by the classes' {@link __construct()} method so that all
    * theme-only settings are set and required plugins are loaded.
    *
    * This loads plugins independent from the plugin settings page so even if a plugin
    * is disabled, yet the theme's options page has it enabled, it will load it.
    *
    * @param    void
    * @return   void
    * @access   private
    * @since    1.0.0
    */
    private function _loadSettings()
    {
        // Load ZenPage Plugin
        $zenpage = $this->getOption('zenpage');
        setOption($zenpage[0], $zenpage[1], false);
        
        // Check/Load Search Form Plugin
        $search = $this->getOption('search');
        if($search[2])
            setOption($search[0], $search[1], false);
        
        // Check/Load Hitcounter Plugin
        $hitcounter = $this->getOption('hitcounter');
        if($hitcounter[2])
            setOption($hitcounter[0], $hitcounter[1], false);
        
        // Check/Load Comment Form
        $comments = $this->getOption('comments');
        if($comments[2])
            setOption($comments[0], $comments[1], false);
        
        // Check/Load Ratings Plugin
        $ratings = $this->getOption('ratings');

        if($ratings[2])
            setOption($ratings[0], $ratings[1], false);
        
        return;
    }
   /**
    * Compress CSS
    *
    * This function is used by the flickerd class to compress CSS files.
    * It is the callback function for ob_start().
    *
    * @param    string  $buffer     The buffered content to compress
    * @return   string              The stripped CSS syntax
    * @access   private
    * @since    1.0.0
    */
    private function _flkrcompress( $buffer )
    {
        /* remove comments */
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        $buffer = str_replace(array(' {', '{ '), '{', $buffer);
        $buffer = str_replace('; ', ';', $buffer);
        $buffer = str_replace(array(';}', ' }'), '}', $buffer);
        $buffer = str_replace(', ', ',', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        return $buffer;
    }  
}
