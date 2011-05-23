<?php
/**
 * FlickrZen - Comment Form Template
 *
 * This template overrides the default comment form template and eliminates the usage
 * of tables for formatting/styling the form.
 * It also uses the theme function {@link flkrPrintCommentField()}, which simplifies the
 * syntax by letting the function generate the redundant code.
 *
 * @version 1.0.0
 * @author  Amereservant <david@amereservant.com>
 */
?>
<!-- // COMMENT FORM START // -->
<form id="commentform" action="#" method="post">
    <input type="hidden" name="comment" value="1" />
	<input type="hidden" name="remember" value="1" />
	<p>
	    <label for="name"><?php echo gettext("Name:"); ?></label>
	    <?php flkrPrintCommentField( 'name', 'name', '', $stored['name'], 'text', $disabled['name'] ); ?>
        
        <?php if( getOption('comment_form_anon') && !$disabled['anon'] ) { ?>
	    <label for="anon">(<input type="checkbox" name="anon" id="anon" value="1"<?php echo ($stored['anon'] ? ' checked="checked"':''); ?> />
	        <?php echo gettext("<em>anonymous</em>"); ?>)
	     </label>
        <?php } ?>
	</p><!-- End of name field -->
					    
    <p>
        <label for="email"><?php echo gettext("E-Mail:"); ?></label>
        <?php flkrPrintCommentField( 'email', 'email', '', html_encode($stored['email']), 'text', $disabled['email'] ); ?>
    </p><!-- End of email field -->
					
	<p>
	    <label for="website"><?php echo gettext("Site:"); ?></label>
		<?php flkrPrintCommentField( 'website', 'website', '', html_encode($stored['website']), 'text', $disabled['website'] ); ?>
	</p><!-- End of website field -->
	
	<?php if(getOption('comment_form_addresses')) {	?>
	<p>
	    <label for="comment_form_street-0"><?php echo gettext('street:'); ?></label>
	    <?php flkrPrintCommentField( '0-comment_form_street', 'comment_form_street', '', html_encode($stored['street']), 'text', $disabled['street'] ); ?>
	</p><!-- End of street field -->
	
	<p>
	    <label for="comment_form_city"><?php echo gettext('city:'); ?></label>
	    <?php flkrPrintCommentField( '0-comment_form_city', 'comment_form_city', '', html_encode($stored['city']), 'text', $disabled['city'] ); ?>
	</p><!-- End of city field -->
	
	<p>
	    <label for="comment_form_state"><?php echo gettext('state:'); ?></label>
		<?php flkrPrintCommentField( '0-comment_form_state', 'comment_form_state', '', html_encode($stored['state']), 'text', $disabled['state'] ); ?>
	</p><!-- End of state field -->
	
	<p>
	    <label for="comment_form_country"><?php echo gettext('country:'); ?></label>
		<?php flkrPrintCommentField( 'comment_form_country', 'comment_form_country', '', html_encode($stored['country']), 'text', $disabled['country'] ); ?>
    </p><!-- End of country field -->
	
	<p>
	    <label for="comment_form_postal"><?php echo gettext('postal code:'); ?></label>
	    <?php flkrPrintCommentField( '0-comment_form_postal', 'comment_form_postal', '', html_encode($stored['postal']), 'text', $disabled['postal'] ); ?>
	 </p><!-- End of postal field -->
	<?php 
	    } // END OF 'comment_form_addresses' OPTION //
		
		if (getOption('Use_Captcha')) {
			$captchaCode = generateCaptcha($img); ?>
			<p>
			    <img src="<?php echo $img; ?>" alt="Captcha Code" id="captcha-code" /><br />
			    <label for="code"><?php echo gettext("Enter CAPTCHA:"); ?></label>
			    <?php flkrPrintCommentField( 'code', 'code', '', '' ); ?>
				<input type="hidden" name="code_h" value="<?php echo $captchaCode; ?>" />
			</p>
		<?php }
			
	    if( getOption('comment_form_private') && !$disabled['private'] ) { ?>
			<p>
			    <label for="private"><?php echo gettext("Private comment:"); ?></label>
				<input type="checkbox" id="private" name="private" value="1"<?php if ($stored['private']) echo ' checked="checked"'; ?> />
		    </p>
	    <?php } ?>
	<p>
	    <textarea name="comment" rows="6" cols="42" class="textarea_inputbox"><?php echo $stored['comment'] . $disabled['comment']; ?></textarea>
	</p>
	<p>						
	    <input type="submit" class="pushbutton"  value="<?php echo gettext('Add Comment'); ?>" />
    </p>
</form>
<!-- // COMMENT FORM END // -->
