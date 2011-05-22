<?php  if (!defined('WEBPATH')) die(); 
/**
 * Flickerd Theme
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */
require 'header.php'; 
?>
<!-- // INDEX PAGE // -->
			<section id="content">
			    <?php if(getCurrentPage() == '1') { ?>
			    <aside id="albums">
				    <ul>
					    <?php while(next_album()) { ?>
					    <li class="album">
						    <div class="albumPic">
							    <a href="<?php echo getAlbumLinkURL(); ?>" title="<?php echo getAlbumTitle(); ?>">
								    <img class="albumThumb" height="75" width="75" src="<?php echo getAlbumThumb(); ?>" alt="<?php echo getAlbumTitle(); ?>" />
							    </a>
						    </div>
						    <section class="albumInfo">
						        <h4><a href="<?php echo getAlbumLinkURL(); ?>" title="<?php echo getAlbumTitle(); ?>"><?php echo getAlbumTitle(); ?></a></h4>
						        <?php 
							    $subalbumcnt = getNumAlbums(); 
							    $photocnt    = getNumImages();
							        
							    if($subalbumcnt > 0) echo $subalbumcnt .' album'. ($subalbumcnt != '1' ? 's':'') .'<br />';
							    if($photocnt > 0)    echo $photocnt .' photos'; ?>
						    </section>
						    <div class="clear"></div>
						</li>
					    <?php } // endwhile ?>
			        </ul>
				</aside><!-- #albums -->
			    <?php } // endif current page ?>
			    <?php $flkr->printFlkrFeed(); ?>
				<p id="itemCount">(<?php echo $flkr->count; ?> items)</p>
			</section><!-- #content -->
<?php require 'footer.php'; ?>
