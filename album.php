<?php if (!defined('WEBPATH')) die(); 
/**
 * Flickerd Theme
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */
 
// If the layout value isn't being stored, remove !headers_sent() to see if an error is raised.
// It is here to prevent errors from being caused by trying to start a session after they've been sent.
if(!isset($_SESSION) && !headers_sent()) session_start(); 

require 'header.php';

$layout = isset($_SESSION['flickrish_album_layout']) ? $_SESSION['flickrish_album_layout'] : 1;

if(isset($_GET["l"]) && strlen($_GET["l"]) > 0) $layout = $_GET["l"];

$_SESSION['flickrish_album_layout'] = $layout; ?>
<!-- ALBUM PAGE START -->
			<section id="content">
				<nav id="breadcrumbs">
				    <?php printHomeLink('', ' &raquo; '); ?>
					<a href="<?php echo getGalleryIndexURL(); ?>" title="<?php echo getGalleryTitle(); ?> Index">
					<?php echo getGalleryTitle(); ?></a> &raquo; <?php printParentBreadcrumb('',' &raquo; ',' &raquo; '); ?>
					<?php printAlbumTitle(); ?>
				</nav>
			
				<section id="albumTitle">
					<h2><?php printAlbumTitle(true); ?></h2>
					<div id="albumOptions">
					    <?php 
					    if($layout != '1' && $layout != 0) makeAnchorLink( getAlbumLinkURL() . (getOption('mod_rewrite') ? '?':'&amp;') .'l=1', 'Thumbnail View', 'Thumbnail');
					    else echo '<strong>Thumbnail</strong>'; ?>      
					    &nbsp;<span>|</span>&nbsp;
					    <?php
					    if($layout !== '2') makeAnchorLink( getAlbumLinkURL() . (getOption('mod_rewrite') ? '?':'&amp;') .'l=2', 'Detail View', 'Detail');
					    else echo '<strong>Detail</strong>'; ?>
					</div>
				</section>
				
			<?php 
				//show the 75x75 thumbnail grid layout
			if($layout == 1) { ?>
				<?php if(getNumAlbums() > 0) { ?>
				<!-- ALBUMS -->
				<section class="albums">
				    <ul>
					<?php while( next_album(true) ) { ?>
					    <li>
					        <div class="albumPic">
						        <a href="<?php echo getAlbumLinkURL();?>" title="<?php echo getAlbumTitle(); ?>">
							        <img class="albumThumb" height="75" width="75" src="<?php echo getAlbumThumb(); ?>" alt="<?php echo getAlbumTitle(); ?>" />
						        </a>
					        </div>
					        <h4><a href="<?php echo getAlbumLinkURL(); ?>" title="<?php echo getAlbumTitle(); ?>"><?php echo getAlbumTitle(); ?></a></h4>
					        <section class="albumInfo">
					            <strong><?php echo getNumImages(); ?></strong> photos
					        </section>
					    </li>
					<?php } // endwhile ?>
					</ul>
				</section>
				<?php } // endif ?>
				<aside class="albumInfo">
					<?php printCustomAlbumThumbMaxSpace(getAlbumTitle(),250,250); ?>
					<div class="albumDescription">
						<?php printAlbumDesc(true); ?>
					</div>
					<div class="albumStats">
						<?php echo getNumImages(); ?> photos | <?php echo getHitcounter(); ?> views 
					</div>
				</aside>
				<section id="images-grid">
				    <ul>
					<?php while(next_image(true)) { ?>
					    <li>
						    <a href="<?php echo htmlspecialchars(getImageLinkURL()); ?>" title="<?php echo getImageTitle(); ?>" >
								<img height="75" width="75" src="<?php echo getImageThumb(); ?>" alt="<?php echo getImageTitle(); ?>" />
							</a>
						</li>
					<?php } // endwhile ?>
					</ul>
				</section>
				
			<?php } //endif
			
			//show the 24 on the long side thumbnail with info layout
			if($layout == 2) { 
				setOption("images_per_page",21,false); 
				if(getNumAlbums() > 0) { ?>
				<!-- ALBUMS -->
				<section class="albums">
				    <ul>
					<?php while( next_album(false) ) { ?>
					    <li>
					        <div class="albumPic">
						        <a href="<?php echo getAlbumLinkURL(); ?>" title="<?php echo getAlbumTitle(); ?>" >
							        <img class="albumThumb" height="75" width="75" src="<?php echo getAlbumThumb(); ?>" />
						        </a>
					        </div>
					        <h4><a href="<?php echo getAlbumLinkURL(); ?>" title="<?php echo getAlbumTitle(); ?>"><?php echo getAlbumTitle(); ?></a></h4>
					        <section class="albumInfo">
					            <strong><?php echo getNumImages(); ?></strong> photos</span>
					        </section>
					    </li>
					<?php } // endwhile ?>
					</ul>
				</section>
				<?php } // endwhile ?>
				<!-- IMAGES -->
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
			<?php	} //endif ?>
			</section>
<?php require 'footer.php'; ?>
