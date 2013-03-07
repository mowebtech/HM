
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>


<script type="text/javascript">
                    $(document).ready(function() {
                    $('#dateofbirth').datepicker({
                       yearRange: '1947:2060',
				changeMonth: true,
				changeYear: true,
				
				dateFormat: "dd-mm-yy"
                    });
});
                </script>
<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/

$user_role = reset( $profileuser->roles );
if ( is_multisite() && empty( $user_role ) ) {
	$user_role = 'subscriber';
}



$user_can_edit = false;
foreach ( array( 'posts', 'pages' ) as $post_cap )
	$user_can_edit |= current_user_can( "edit_$post_cap" );

$profileuser = get_userdata($current_user->ID);

?>

<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php //$template->the_action_template_message( 'profile_update' ); ?>
	<?php 
             if(isset($_GET['error']) && $_GET['error']!='' && $_GET['error']=='passowrd'){?>
                    <p class="error"><b>Error:</b> Password dose not match.<br></p>
              <?php }elseif(isset($_GET['error']) && $_GET['error']!='' && $_GET['error']=='email_null'){?>
                  <p class="error"><b>Error:</b> Please type your e-mail address.<br></p>
              <?php }elseif(isset($_GET['error']) && $_GET['error']!='' && $_GET['error']=='invalid'){?>
                  <p class="error"><b>Error:</b> The email address isn&#8217;t correct.<br></p>
              <?php }elseif(isset($_GET['error']) && $_GET['error']!='' && $_GET['error']=='already'){?>
                  <p class="error"><b>Error:</b> This email is already registered, please choose another one.<br></p>
              <?php }elseif(isset($_GET['success']) && $_GET['success']!='' && $_GET['success']=='success'){?>
                  <p><font style="color: green;">Update successfully.</font><br></p>
              <?php }
              
        ?>
        
	<form id="your-profile" action="<?php $template->the_action_url( 'preference_update' )?>" method="post">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ) ?>
		<p>
                        
			<input type="hidden" name="from" value="preference_update" />
			<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
		</p>

		

		
                
                <h3><?php _e( 'My preference', 'theme-my-login' ) ?></h3>
                <table class="form-table">
                    <tr>
                        <td></td>
                    </tr>
                </table>
                <table class="form-table">
                    <tr>
                        <td></td>
                    </tr>
                </table>
		<table class="form-table">
		<tr>
			<td><label for="email"><?php _e( 'E-mail', 'theme-my-login' ); ?> <span class="description"><?php _e( '(required)', 'theme-my-login' ); ?></span></label></td>
			<td><input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" /></td>
		</tr>

		
                    <tr style="vertical-align:  top">
                        <td><label for="pass1"><?php _e('New Password'); ?></label></td>
                        <td><input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /> <br />
                            <span class="description"><?php _e("If you would like to change the password type a new one. Otherwise leave this blank."); ?></span><br />
                            <input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" /><br />
                            <span class="description"><?php _e("Type your new password again."); ?></span><br />
                            <!--<p class="description indicator-hint"><?php _e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).'); ?></p>-->
                        </td>
                         
                </tr>
                
                
                </table>
                
                <?php //do_action( 'register_form' ); // Wordpress hook?>
                
                <?php
                       // do_action( 'show_user_profile', $profileuser );
		?>
                
                

		<?php if ( count( $profileuser->caps ) > count( $profileuser->roles ) && apply_filters( 'additional_capabilities_display', true, $profileuser ) ) { ?>
		<br class="clear" />
			<table width="99%" style="border: none;" cellspacing="2" cellpadding="3" class="editform">
				<tr>
					<th scope="row"><?php _e( 'Additional Capabilities', 'theme-my-login' ) ?></td>
					<td><?php
					$output = '';
					global $wp_roles;
					foreach ( $profileuser->caps as $cap => $value ) {
						if ( !$wp_roles->is_role( $cap ) ) {
							if ( $output != '' )
								$output .= ', ';
							$output .= $value ? $cap : "Denied: {$cap}";
						}
					}
					echo $output;
					?></td>
				</tr>
			</table>
		<?php } ?>
               

		<p class="submit">
			<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Prefrence', 'theme-my-login' ); ?>" name="submit" />
		</p>
	</form>
</div>
