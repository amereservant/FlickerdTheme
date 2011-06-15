<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php zp_apply_filter('theme_head'); ?>
	<title><?php $mainsite = getMainSiteName(); echo (empty($mainsite))?gettext("Zenphoto gallery"):$mainsite; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
	<link rel="stylesheet" href="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/admin.css" type="text/css" />
	<script type="text/javascript" src="<?php echo  $_zp_themeroot ?>/scripts/bluranchors.js"></script>
</head>
<body>
<?php zp_apply_filter('theme_body_open'); ?>
    <div id="loginform">
        <p><img src="<?php echo WEBPATH .'/'. ZENFOLDER; ?>/images/zen-logo.png" alt="Zen Photo" width="200" height="47" /></p>
		<?php printPasswordForm(NULL, $show); ?>
	</div>

<?php
//printFooter(false);
zp_apply_filter('theme_body_close');
?>

</body>
</html>
