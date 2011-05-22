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

// If sizes are being requested, let the sizes.php handle the rest of the output.
if(isset($_GET['sizes']) && strlen($_GET['sizes']) >0 )
{
    require 'sizes.php';
    exit();
} ?>
<!-- // IMAGE PAGE // -->
		<script type="text/javascript">
		function toggleComments() { 
			var commentDiv = document.getElementById("comments");
			if (commentDiv.style.display == "block") { 
				commentDiv.style.display = "none";
			} else { 
				commentDiv.style.display = "block"; 
			} 
		} 
		</script>
	
			<section id="content">
				<nav id="breadcrumbs">
				    <?php printHomeLink('', ' &raquo; '); ?>
					<a href="<?php echo getGalleryIndexURL(); ?>" title="<?php echo getGalleryTitle(); ?> Index">
					<?php echo getGalleryTitle(); ?></a> &raquo; <?php printParentBreadcrumb('',' &raquo; ',' &raquo; '); ?>
					<?php printAlbumBreadcrumb('', ' &raquo; '); ?>
					<?php echo getImageTitle(); ?>
				</nav>
				<section id="imageSide">
					<h2 id="imageTitle"><?php printImageTitle(true); ?></h2>
					 <ul id="buttonbar">
					    <li class="sizesbutton">
					        <a href="<?php echo $_zp_current_image->getImageLink() . (getOption('mod_rewrite') ? '?':'&'); ?>sizes=1" >&nbsp;</a>
					    </li>
				    </ul>
				    <div style="min-height:<?php echo isLandscape() ? '333' : '500'; ?>px;">
					    <?php printCustomSizedImageMaxSpace(getImageTitle(),500,500); ?>
					</div>
					
					<p class="dateuploaded"><?php printImageDesc(true); ?></p>
					
					<?php if(function_exists('printCommentForm')) {
					    $flkr->printFlkrComments( $_zp_current_image->getComments() ); // Doesn't require printCommentForm() function,
					                                                                   // but we'll hide it if it's disabled.
					    printCommentForm(false, '<h3>'. gettext('Add Comment:') .' <a href="#" id="addComment" class="iblock">[ Show ]</a></h3>');
					} ?>
					
				</section>
				
				<aside id="imageSidebar">
					<div class="imageInfo">
						<img class="smBuddy" src="<?php echo $_zp_themeroot; ?>/images/buddyicon.jpg" alt="Buddy Icon" />
						<div class="dateTaken">Date: <?php $datetime = date_create(getImageDate()); echo date_format($datetime, 'F j, Y'); ?></div>
						<div class="clear"></div>
					</div>
					
					<section id="imageBrowser">
						<h3><a href="<?php echo getAlbumLinkURL(); ?>"><?php echo getAlbumTitle(); ?></a></h3>
						<div id="toggle">&nbsp;</div>
						<div id="toggleBox">
						    <ul id="thumbBoxes">
							    <li>
								    <?php if( hasPrevImage() ){ 
								        $prevImg = $_zp_current_image->getPrevImage();
								    ?>
							        <a href="<?php echo getPrevImageURL(); ?>">
							            <img src="<?php echo getPrevImageThumb(); ?>" width="75" height="75" alt="<?php echo $prevImg->getTitle(); ?>" />
							        </a><?php } else { ?>
							        <img src="<?php echo $_zp_themeroot; ?>/images/first_photo.gif" width="75" height="75" alt="First Image" />
								    <?php } ?>
								</li>
								<li>
								    <?php if( hasNextImage() ){ 
								        $nextImg = $_zp_current_image->getNextImage();
								    ?>
								    <a href="<?php echo getNextImageURL(); ?>">
								        <img src="<?php echo getNextImageThumb(); ?>" width="75" height="75" alt="<?php echo $nextImg->getTitle(); ?>" />
								    </a><?php } else { ?>
								    <img src="<?php echo $_zp_themeroot; ?>/images/last_photo.gif" width="75" height="75" alt="Last Image" />
								    <?php } ?>
						        </li>
							</ul>
							<nav id="thumbNav">
						        <span id="browsePrev">
								<?php if( hasPrevImage() ){ ?>
									<a href="<?php echo getPrevImageURL(); ?>">&nbsp;</a> 
								<?php }?>
								</span>
								<span><a href="<?php echo getAlbumLinkURL(); ?>">browse</a></span>
								<span id="browseNext">
								<?php if( hasNextImage() ){ ?>
									<a href="<?php echo getNextImageURL(); ?>">&nbsp;</a>
								<?php }?>
								</span> 
							</nav>
				        </div>
					</section>
					
					<section id="tags" class="picInfo">
						<h4>Tags</h4>
						<?php printTags('links', NULL, 'taglist', ', ', true, '', '<em>'. gettext('(No tags...)') .'</em>'); ?>
					</section>
					
					<?php if(function_exists('printRating')) { ?>
					<section id="rating" class="picInfo">
					    <h4>Rating</h4>
					    <?php printRating(); ?>
					</section>
					<?php } ?>
					
					<section id="additionalInfo" class="picInfo">
						<h4>Additional Info</h4>
						<ul><?php
						    if(($taken_with = GetEXIFCameraModel()) != false) { ?>
						    <li>Taken with a <?php echo $taken_with; ?></li>
						    <?php }
						        // Use the EXIFDateTimeOriginal to get the date it was actually taken on.
						        // If this isn't available, we'll leave this off since it won't be the
						        // actual time the photo was taken on.
						        if($_zp_current_image->get('EXIFDateTimeOriginal')) {
						            $takentime = $_zp_current_image->get('EXIFDateTimeOriginal'); ?>
							<li>Taken on <?php  echo date('F j, Y', strtotime($takentime)); ?></li>
							<?php } ?>
							<li class="noliStyle"><?php printImageMetadata('More properties', false); ?></li>
							<?php if($flkr->getOption('hitcounter', true)) { ?>
							<li>Viewed <?php echo getHitcounter(); ?> time<?php echo (getHitcounter() == '1' ? '':'s'); ?></li>
							<?php } ?>
						</ul>
					</section>
					
				</aside>
				
			</section>
<?php require 'footer.php'; ?>
