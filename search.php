<?php  if (!defined('WEBPATH')) die(); 
/**
 * Flickerd Theme - Search Results
 *
 * This file provides the output for search results.
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */
require 'header.php';
?>
<!-- // SEARCH PAGE // -->
<section id="content">
<?php
    if (($total = getNumImages() + getNumAlbums()) > 0) {
        if (isset($_REQUEST['date'])) {
            $searchwords = getSearchDate();
        } else { 
            $searchwords = getSearchWords();
        }
        echo '<p class="results">'. sprintf(gettext('There are <strong>%2$u</strong> matches for "<em>%1$s</em>".'), $searchwords, $total) .'</p>';
    }
    if(getNumAlbums() > 0) { ?>
    <h2>Albums</h2>
    <section class="albums">
        <ul>
    <?php while( next_album() ) { ?>
            <li>
                <div class="albumPic">
                    <a href="<?php echo html_encode(getAlbumLinkURL()); ?>" title="<?php echo gettext('View album:') . getAnnotatedAlbumTitle(); ?>">
                        <img class="albumThumb" height="75" width="75" src="<?php echo getAlbumThumb(); ?>" alt="<?php echo getAnnotatedAlbumTitle(); ?>" />
                    </a>
                </div>
                <h4><a href="<?php echo html_encode(getAlbumLinkURL());?>" title="<?php echo gettext('View album:') . getAnnotatedAlbumTitle(); ?>"><?php printAlbumTitle(); ?></a></h4>
                <section class="albumInfo">
                    <strong><?php echo getNumImages(); ?></strong> photos
                </section>
            </li>
    <?php } // endwhile ?>
        </ul>
    </section>
    <?php } // endif ?>
    
    <?php if(getNumImages() > 0) { ?>
    <!-- IMAGES -->
    <h2>Images</h2>
	<section class="images">
		<ul>
		<?php while (next_image()) { 
		    $views    = getHitcounter($_zp_current_image);
		    $comments = getCommentCount($_zp_current_image); ?>
		    <li>
		        <a href="<?php echo htmlspecialchars(getImageLinkURL()); ?>" title="<?php echo getImageTitle(); ?>">
						<?php printCustomSizedImageMaxSpace(getImageTitle(),240,240); ?>
				</a>
			    <h4><?php echo getImageTitle(); ?></h4>
			    <div class="imageDescription"><?php echo getContentShorten(stripPTags(getImageDesc($_zp_current_image)), 100, 
			        '<a href="'. htmlspecialchars(getImageLinkURL()) .'">...</a>'); ?></div>
			    <p class="dateuploaded">Uploaded on <span><?php  echo date('F j, Y', $_zp_current_image->data['mtime']); ?></span></p>
			    <p class="viewinfo">
			        <?php echo $views; ?> view<?php echo $views != '1' ? 's':''; ?> &nbsp;|&nbsp; 
			        <?php echo $comments == null ? 0 : $comments; ?> comment<?php echo $comments != '1' ? 's':''; ?>
			    </p>
		    </li>
		<?php } // endwhile ?>
		</ul>
		<div class="clear"></div>
	</section>
    <?php if(hasPrevPage() || hasNextPage()) printPageListWithNav('&laquo; Prev', 'Next &raquo;', false , 'pagelist', 'pagelist'); ?>
    <p id="itemCount">(<?php echo getNumImages(); ?> items)</p>
    <?php } // endif ?>
    <?php if ($total == 0) {
        echo "<p>".gettext("Sorry, no image matches found. Try refining your search.")."</p>";
    }
?>
</section>
<?php require 'footer.php'; ?>
