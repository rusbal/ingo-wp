<?php if ( is_user_logged_in() ) : ?>

	<fieldset>
		<label><?php _e( 'Your account', 'wpsight-dashboard' ); ?></label>
		<div class="field account-sign-in">

			<?php printf( __( 'You are signed in as <strong>%s</strong>.', 'wpsight-dashboard' ), wp_get_current_user()->display_name ); ?> 			
			<a href="<?php echo apply_filters( 'wpsight_submit_listing_logout_url', esc_url( wp_logout_url( get_permalink() ) ) ); ?>"><?php _e( 'Sign out', 'wpsight-dashboard' ); ?></a>

		</div>
	</fieldset>

<?php else : ?>

	<fieldset>
		<label><?php _e( 'Have an account?', 'wpsight-dashboard' ); ?></label>
		<div class="field account-sign-in">

			<a class="button alt small" href="<?php echo apply_filters( 'submit_listing_form_login_url', wp_login_url( get_permalink() ) ); ?>"><?php _e( 'Sign in', 'wpsight-dashboard' ); ?></a>

			<?php if ( wpsight_is_registration_enabled() ) : ?>

				<?php printf( '- ' . __( 'If you don&rsquo;t have an account, you can %screate one below by entering your username and email address. An auto-generated password will be emailed to you.', 'wpsight-dashboard' ), wpsight_is_account_required() ? '' : __( 'optionally', 'wpsight-dashboard' ) . ' ' ); ?>

			<?php elseif ( wpsight_is_account_required() ) : ?>

				<?php echo apply_filters( 'submit_listing_form_login_required_message', '&nbsp;' . __( 'You must sign in to create a new listing.', 'wpsight-dashboard' ) ); ?>

			<?php endif; ?>
		</div>
	</fieldset>
	
	<?php if ( wpsight_is_registration_enabled() ) : ?>

		<fieldset>
			<label><?php _ex( 'Username', 'submit form label', 'wpsight-dashboard' ); echo apply_filters( 'submit_listing_form_required_label', ( wpsight_is_account_required() ) ? '<span class="required">*</span>' : '' ); ?></label>
			<div class="field">
				<input type="text" class="input-text" name="create_account_login" id="account_login" placeholder="<?php esc_attr_e( 'yourusername', 'wpsight-dashboard' ); ?>" value="<?php if ( ! empty( $_POST['create_account_login'] ) ) echo sanitize_text_field( stripslashes( $_POST['create_account_login'] ) ); ?>" <?php if( wpsight_is_account_required() ) echo 'required '; ?>/>
			</div>
		</fieldset>
		
		<fieldset>
			<label><?php _ex( 'Email', 'submit form label', 'wpsight-dashboard' ); echo apply_filters( 'submit_listing_form_required_label', ( wpsight_is_account_required() ) ? '<span class="required">*</span>' : '' ); ?></label>
			<div class="field">
				<input type="email" class="input-text" name="create_account_email" id="account_email" placeholder="<?php esc_attr_e( 'your@email.com', 'wpsight-dashboard' ); ?>" value="<?php if ( ! empty( $_POST['create_account_email'] ) ) echo sanitize_text_field( stripslashes( $_POST['create_account_email'] ) ); ?>" <?php if( wpsight_is_account_required() ) echo 'required '; ?>/>
			</div>
		</fieldset>

		<?php do_action( 'wpsight_register_form' ); ?>

	<?php endif; ?>

<?php endif; ?>