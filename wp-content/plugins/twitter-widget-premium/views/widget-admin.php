<?php
if ( count($users_with_twitter_accounts) > 0 ) {
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', $TwitterPro->pluginDomain ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

	<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of Tweets to Display:', $TwitterPro->pluginDomain ); ?></label> <input type="text" value="<?php echo $count; ?>" name="<?php echo $this->get_field_name('count'); ?>" id="<?php echo $this->get_field_id('count'); ?>" size="3" /></p>

	<p><input type="checkbox" value="true" name="<?php echo $this->get_field_name('displayAuthor'); ?>" id="<?php echo $this->get_field_id('displayAuthor'); ?>" <?php if ($displayAuthor) { echo 'checked'; } ?> /> <label for="<?php echo $this->get_field_id('displayAuthor'); ?>"><?php _e( 'Display Author Name', $TwitterPro->pluginDomain ); ?></label></p>

	<p><input type="checkbox" value="true" name="<?php echo $this->get_field_name('displayPhoto'); ?>" id="<?php echo $this->get_field_id('displayPhoto'); ?>" <?php if ($displayPhoto) { echo 'checked'; } ?> /> <label for="<?php echo $this->get_field_id('displayPhoto'); ?>"><?php _e( 'Display Author Photo', $TwitterPro->pluginDomain ); ?></label></p>

	<p><input type="checkbox" value="true" name="<?php echo $this->get_field_name('displayDate'); ?>" id="<?php echo $this->get_field_id('displayDate'); ?>" <?php if ($displayDate) { echo 'checked'; } ?> /> <label for="<?php echo $this->get_field_id('displayDate'); ?>"><?php _e( 'Display Time and Date of Tweet', $TwitterPro->pluginDomain ); ?></label></p>

	<p><input type="checkbox" value="true" name="<?php echo $this->get_field_name('excludeReplies'); ?>" id="<?php echo $this->get_field_id('excludeReplies'); ?>" <?php if ($excludeReplies) { echo 'checked'; } ?> /> <label for="<?php echo $this->get_field_id('excludeReplies'); ?>"><?php _e( 'Exclude @replies', $TwitterPro->pluginDomain ); ?></label></p>
	
	<p><label for="<?php echo $this->get_field_id('users'); ?>"><?php _e( 'Users', $TwitterPro->pluginDomain ); ?>:</label><?php

	// TODO: link to select all or select none that checks and unchecks all the boxes.  Default to all selected on new widgets.
	foreach( $TwitterPro->getTwitterUsers() as $k => $userId) {
		echo "<p><input type=\"checkbox\" name=\"".$this->get_field_name('users')."[]\" id=\"".$this->get_field_id('users')."\" value=\"$userId\" ";
		if (in_array($userId,$users)) {
			echo "checked";
		}
		echo "/> ";
		$user = get_userdata( $userId );
		echo $user->display_name . " ";
		sp_the_linked_twitter_username( $userId );
		echo "</p>\n";
	}
} else {
	echo "<p>" . __( 'Sorry, at least one user needs to have a working twitter account configured for this widget to function properly.  Please visit the configuration panel or your user settings to enter a twitter account.', $TwitterPro->pluginDomain ) . "</p>";
	echo "<p><a href=\"options-general.php?page=" . $TwitterPro->pluginDomain . "\">" . __( 'Add Twitter accounts in the Twitter administration panel', $TwitterPro->pluginDomain ) . "</a></p>";
	echo "<p><a href=\"profile.php\">" . __( 'Add your Twitter username to your user profile', $TwitterPro->pluginDomain ) . "</a></p>";
}
?>