
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

$profileuser = get_user_to_edit($current_user->ID);


  
?>

<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php 
            if($_GET['level']=='2' && wp_get_current_user()->membership_level->name!='Pro')
            {
                echo "<p class=error>You can not access this page.<br></p>";
                exit;
            }  
        ?>
	<?php 
              if(isset($_GET['error']) && $_GET['error']!='' && $_GET['error']=='location')
              {    ?>
                    <p class="error"><b>Error:</b> Location can not be empty.<br></p>
              <?php }elseif(isset($_GET['error']) && $_GET['error']!='' && $_GET['error']=='invalid'){?>
                    <p class="error"><b>Error:</b> Password dose not match.<br></p>
              <?php }else{
                  $template->the_errors();
              }
              
        ?>
        
	<form id="your-profile" action="<?php $template->the_action_url( 'profile_update' )?>" method="post">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ) ?>
		<p>
                        <input type="hidden" name="level" value="<?php echo $_GET['level']?>" />
			<input type="hidden" name="from" value="profile_update" />
			<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
		</p>

		

		<!--<h3><?php _e( 'Personal Options', 'theme-my-login' ); ?></h3>-->

		
		

		<?php //do_action( 'profile_personal_options', $profileuser ); ?>

		<h3><?php _e( 'Name', 'theme-my-login' ) ?></h3>

		<table class="form-table">
		<tr>
			<td><label for="user_login"><?php _e( 'Username', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled="disabled" class="regular-text" /> </br>
                            <span class="description"><?php _e( 'Your username cannot be changed.', 'theme-my-login' ); ?></span></td>
		</tr>

		
		</table>

		

		<h3><?php _e( 'About Yourself', 'theme-my-login' ); ?></h3>

		<table class="form-table">
                    
                <tr >
                        <td><label for="dateofbirth"><?php _e( 'Date of Birth', 'theme-my-login' ); ?></label></td>
			
			
			<td>
                            
                            <input type="text" name="dateofbirth" id="dateofbirth" value="<?php echo esc_attr( $profileuser->dateofbirth ) ?>" class="regular-text" />
			</td>
                
		
		</tr>    
                    
		<tr>
			<td><label for="description"><?php _e( 'About you', 'theme-my-login' ); ?></label></td>
			<td><textarea name="about_you" id="description" rows="5" cols="30"><?php echo esc_html( $profileuser->about_you ); ?></textarea><br />
			<span class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'theme-my-login' ); ?></span></td>
		</tr>

                <?php $experience_level_arr = array("I'm a business providing sporty/healthy living events","I want to learn a new sport",
                                                    "Just for fun/social meet ups","Beginner","Intermediate","Expert","Disabled");?>                
		<tr>
			<td><label for="experience_level"><?php _e( 'Experience Level', 'theme-my-login' ); ?></label></td>
			<td>
                            
                            <select name="experience_level" id="experience_level">
                                <!--<option value=""></option> -->  
                            <?php foreach($experience_level_arr as $experience_value){?>
                            <option <?php selected( $profileuser->experience_level, $experience_value ); ?> value="<?php echo $experience_value;?>"><?php echo $experience_value;?></option>
                            <!--<input type="text" name="experience_level" id="experience_level" value="<?php echo esc_html( $profileuser->experience_level ); ?>" autocomplete="off" />-->
                            <?php }?>
                            </select>
			</td>
		</tr>
                
                <tr>
			<td><label for="location"><?php _e( 'Location', 'theme-my-login' ); ?>*</label></td>
			<td><input type="text" name="location" id="location" value="<?php echo esc_html( $profileuser->location ); ?>" autocomplete="off" />
				
			</td>
		</tr>
		<?php //do_action('personal_options', $profileuser); ?>
                
		</table>
                
                <?php if($_GET['level']=='2')
                {?>
                <h3><?php _e( 'Full Address', 'theme-my-login' ); ?></h3>
                <table class="form-table">
                <tr>
			<td><label for="address1"><?php _e( 'Address Line 1', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="address1" id="address1" value="<?php echo esc_html( $profileuser->address1 ); ?>" autocomplete="off" />
				
			</td>
		</tr>
                <tr>
			<td><label for="address2"><?php _e( 'Address Line 2', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="address2" id="address1" value="<?php echo esc_html( $profileuser->address2 ); ?>" autocomplete="off" />
				
			</td>
		</tr>
                <tr>
			<td><label for="town"><?php _e( 'Town', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="town" id="town" value="<?php echo esc_html( $profileuser->town ); ?>" autocomplete="off" />
				
			</td>
		</tr>
                <tr>
			<td><label for="county"><?php _e( 'County', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="county" id="county" value="<?php echo esc_html( $profileuser->county ); ?>" autocomplete="off" />
				
			</td>
		</tr>
              
                <tr>
			<td><label for="postcode"><?php _e( 'Postcode', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="postcode" id="postcode" value="<?php echo esc_html( $profileuser->postcode ); ?>" autocomplete="off" />
				
			</td>
		</tr>
                <tr>
			<td><label for="dire_to_venue"><?php _e( 'Direction to venue', 'theme-my-login' ); ?></label></td>
			<td><input type="text" name="dire_to_venue" id="dire_to_venue" value="<?php echo esc_html( $profileuser->dire_to_venue ); ?>" autocomplete="off" />
				
			</td>
		</tr>
                </table>
                
                <h3><?php _e( 'Contact Info', 'theme-my-login' ) ?></h3>

		<table class="form-table">
		

		<tr>
			<td><label for="url"><?php _e( 'Website', 'theme-my-login' ) ?></label></td>
			<td><input type="text" name="url" id="url" value="<?php echo esc_attr( $profileuser->url ) ?>" class="regular-text code" /></td>
		</tr>

		<?php if ( function_exists( '_wp_get_user_contactmethods' ) ) :
			foreach ( _wp_get_user_contactmethods() as $name => $desc ) {
		?>
		<tr>
			<td><label for="<?php echo $name; ?>"><?php echo apply_filters( 'user_'.$name.'_label', $desc ); ?></label></td>
			<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $profileuser->$name ) ?>" class="regular-text" /></td>
		</tr>
		<?php
			}
			endif;
		?>
                <tr>
			<td><label for="telephone_num"><?php _e( 'Telephone Number', 'theme-my-login' ) ?></label></td>
			<td><input type="text" name="telephone_num" id="telephone_num" value="<?php echo esc_attr( $profileuser->telephone_num ) ?>" class="regular-text code" /></td>
		</tr>
                <tr>
			<td><label for="opening_hour"><?php _e( 'Opening Hour', 'theme-my-login' ) ?></label></td>
			<td><input type="text" name="opening_hour" id="opening_hour" value="<?php echo esc_attr( $profileuser->opening_hour ) ?>" class="regular-text code" /></td>
		</tr>
		</table>
                <?php }
                ?>
                
               
                
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
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Profile', 'theme-my-login' ); ?>" name="submit" />
		</p>
	</form>
</div>
<script type='text/javascript' src='http://192.168.1.50/projects/jems/wp-content/plugins/metronet-profile-picture/js/mpp.js?ver=1.0.17'></script>