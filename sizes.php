<?php  if (!defined('WEBPATH')) die(); 
/**
 * Flickerd Theme - Search Results
 *
 * This file provides the output for the different image sizes.
 * It get's called from the images.php file, so therefore the header.php file isn't
 * required in this file.
 *
 * @package     Flickerd
 * @version     1.0.0
 * @author      David Miles <david@thatchurch.com>
 * @link        http://github.com/amereservant/FlickerdTheme
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!-- // SIZES PAGE - Called from images page // -->		
		    <section id="content">
			    <ul id="sizetable">
			        <li><h3>Sizes</h3>
			            <ul>
			                <li><span class="sizeTitle"><a class="switch" href="<?php echo htmlentities(getCustomSizedImageMaxSpace(75,75)); ?>" title="Thumbnail">Thumbnail</a></span>
								<?php
										if(isLandscape()) {
											$tw = 75;
											$tr = $tw/getFullWidth();
											$th = intval(getFullHeight() * $tr);
										}else{
											$th = 75;
											$tr = $th/getFullHeight();
											$tw = intval(getFullWidth() * $tr);
										}
									?>
								<span class="imagedimension"><?php echo $tw .' x '. $th; ?></span>
						    </li>
						    <?php if(getDefaultWidth() < getFullWidth() || getDefaultHeight() < getFullHeight()) { ?>
						    <li><span class="sizeTitle"><a class="switch" href="<?php echo htmlentities(getDefaultSizedImage()); ?>" title="Medium">Medium</a></span>
							    <span class="imagedimension"><?php echo getDefaultWidth() .' x '. getDefaultHeight(); ?></span>
						    </li>
						    <?php 
						    } if(getFullWidth() > 1000 || getFullHeight() > 1000) { 
							    if(isLandscape()) {
								    $lw = 1000;
								    $lr = $lw/getFullWidth();
								    $lh = intval(getFullHeight() * $lr);
							    }else{
								    $lh = 1000;
								    $lr = $lh/getFullHeight();
								    $lw = intval(getFullWidth() * $lr);
							    } ?>
						    <li><span class="sizeTitle"><a class="switch" href="<?php echo htmlentities(getCustomSizedImageMaxSpace(1000, 1000)); ?>" title="Large">Large</a></span>
							    <span class="imagedimension"><?php echo $lw . ' x ' . $lh ?></span>
							</li>
						<?php } ?>
						    <li><span class="sizeTitle"><a class="switch" href="<?php echo htmlentities(getFullImageURL()); ?>" title="Original">Original</a></span>
								<span class="imagedimension"><?php echo getFullWidth() .' x '. getFullHeight(); ?></span>
							</li>
						</ul>
					</li>
				</ul>
				<a href="<?php echo $_zp_current_image->getImageLink(); ?>" title="Go back to image details" style="display:block">&lt;&lt; Go Back</a>
				<section id="imageDisplay">
			        <img id="imgView" src="<?php echo htmlentities(getDefaultSizedImage()); ?>" alt="Preview" />
	            </section>
	        </section>
<?php require 'footer.php'; ?>
