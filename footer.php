<!-- // FOOTER START // -->
	<div class="clear"></div>
	</div><!-- #container -->
	<footer>
	    <?php if(isset($_GET['album'])) { ?>
	    <p><?php printRSSLink('Album','','feed',' - subscribe to the album ' . getAlbumTitle(),true) ?></p>
	    <?php } // endif ?>
	    <p class="copyright">Copyright &copy; <?php echo date('Y'); ?> - All rights reserved.&nbsp; | &nbsp;Powered by <a href="http://zenphoto.org" title="ZenPhoto">ZenPhoto</a></p>
	</footer>
	<?php zp_apply_filter('theme_body_close'); ?>
</body>
</html>
