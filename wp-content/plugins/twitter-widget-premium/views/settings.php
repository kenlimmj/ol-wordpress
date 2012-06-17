<div class="snp_settings wrap">

<?php screen_icon(); ?><h2><?php printf( '%s Settings', $this->pluginName ); ?></h2>

<?php
$options = $this->getOptions();
?>
<div class="form">
	<h3><?php _e('Need a hand?',$this->pluginDomain); ?></h3>
	<p><?php printf( __( 'If you’re stuck on these options, please <a href="%s">check out the documentation</a>. Or, go to the <a href="%s">support forum</a>.', $this->pluginDomain ), $this->pluginUrl . '/readme.txt', $this->supportUrl ); ?></p>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<?php wp_nonce_field($this->_nonce); ?>

	<h3><?php _e('Settings', $this->pluginDomain); ?></h3>
	<table class="form-table <?php echo $this->pluginDomain; ?>-form">
		<tr>
	        <td>
				<?php
				$field_id = $this->_options_css;
				$field_name = $field_id;
				$checked = ( $options[ $field_name ] ) ? 'checked' : '';
				$field_label = __('Use Plugin CSS',$this->pluginDomain);
				?>
				<input type="checkbox" name="<?php echo $field_name; ?>" value="true" id="<?php echo $field_id; ?>" <?php echo $checked; ?> /> 
				<label for="<?php echo $field_id; ?>"><?php echo $field_label; ?></label>
	        </td>
		</tr>
		<tr>
	        <td>
				<?php 
				$field_id = $this->_options_widget;
				$field_name = $field_id;
				$checked = ( $options[ $field_name ] ) ? 'checked' : '';
				$field_label = __('Enable Widget',$this->pluginDomain);
				?>
				<input type="checkbox" name="<?php echo $field_name; ?>" value="true" id="<?php echo $field_id; ?>" <?php echo $checked; ?> /> 
				<label for="<?php echo $field_id; ?>"><?php echo $field_label; ?></label>
	        </td>
		</tr>
		<tr>
	    	<td>
	    		<input id="<?php echo $this->pluginDomain; ?>-save" class="button-primary" type="submit" name="<?php echo $this->pluginDomain; ?>-save" value="<?php _e('Save Changes', $this->pluginDomain); ?>" />
	        </td>
	    </tr>
	</table>

	<h3><?php _e('User Twitter Accounts', $this->pluginDomain); ?></h3>
	<table class="form-table <?php echo $this->pluginDomain; ?>-form">
		<tr>
			<th><?php _e('Wordpress User',$this->pluginDomain); ?></th>
			<th><?php _e('Twitter Username',$this->pluginDomain); ?></th>
		</tr>
		<?php foreach( get_users_of_blog() as $k => $user ) { 
		$user = get_userdata( $user->user_id );		
		?>
		<tr>
	        <td><label for="<?php echo $this->pluginDomain; ?>-text-input-<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></label></td>
	        <td><input 
				type="text" 
				name="<?php echo $this->_key_user . '-' . $user->ID; ?>" 
				class="<?php echo $this->pluginDomain; ?>-text-input" 
				id="<?php echo $this->pluginDomain; ?>-text-input-<?php echo $user->ID; ?>" 
				value="<?php echo $this->getTwitterUserName( $user->ID ); ?>" 
			/></td>
		</tr>
		<?php } ?>

		<tr>
	    	<td colspan="2">
	    		<input id="<?php echo $this->pluginDomain; ?>-save" class="button-primary" type="submit" name="<?php echo $this->pluginDomain; ?>-save" value="<?php _e('Save Changes', $this->pluginDomain); ?>" />
	        </td>
	    </tr>
	</table>

	</form>
</div>
</div>