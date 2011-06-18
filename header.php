<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
<?php 
// Build page title
$imgtitle = getBareImageTitle();
$albtitle = getBareAlbumTitle();
$title    = ($imgtitle ? $imgtitle .' &laquo; ':'');
$title   .= ($albtitle ? $albtitle .' &laquo; ':'') . getGalleryTitle();
$delay    = getOption('flickerd_preview_delay');
$delay    = $delay ? $delay : 500;
?>
<title><?php echo $title; ?></title>
<?php echo $flkr->getStylesheets(); ?>
<script type="text/javascript">flickerdPreviewDelay = '<?php echo $delay; ?>';</script>
<?php zp_apply_filter('theme_head'); ?>
<script src="<?php echo $_zp_themeroot; ?>/js/modernizr-1.7.min.js" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot; ?>/js/sitejs.min.js" type="text/javascript"></script>
<?php printRSSHeaderLink('Gallery',gettext('Gallery RSS')); ?>
<?php if(isset($_GET['album'])) printRSSHeaderLink('Album', getAlbumTitle()); ?>
<?php if(getOption('flickerd_use_fb_like') && isset($is_image_page) && $is_image_page) { ?>
<meta property="og:title" content="<?php echo $imgtitle; ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo fullImageLinkURL(getMainSiteURL(), $_zp_current_image->getImageLink()); ?>" />
<meta property="og:image" content="<?php echo fullImageLinkURL(getMainSiteURL(), getFullImageURL()); ?>" />
<meta property="og:site_name" content="<?php echo getGalleryTitle(); ?>" />
<meta property="fb:admins" content="1107773734" />
<?php } ?>
</head>

<body<?php 
// Add an ID for the gallery index page's CSS
if(!isset($_GET['album'])) echo ' id="pg-'. getCurrentPage() .'"'; ?>>
<?php zp_apply_filter('theme_body_open'); ?>
<div id="container">
    <header>
		<h1><?php printFlkrTitle(); ?></h1>
		
		<nav id="menuBar">
			<ul class="flat menu">
				<li><span class="menuitem"><a href="<?php echo getGalleryIndexURL(); ?>" title="Home">Home</a></span></li>
				<?php if(zp_loggedin()) { ?><li><span class="admintoolbox"><?php printAdminToolbox(); ?></span></li><?php }?>
			</ul>
			<ul class="flat search">
				<li>
					<?php if( getOption('Allow_search')) {
					    printSearchForm(NULL, 'Search', NULL, '', $_zp_themeroot .'/images/searchopt.png'); ?>
					    <div class="searchopts"></div>
				    <?php } ?>
				</li>					
			</ul>
			<div class="clear"></div>
		</nav>
	</header>
<!-- // HEADER END // -->
