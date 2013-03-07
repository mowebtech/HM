<?php
/*
  If you would like to edit this file, copy it to your current theme's directory and edit it there.
  Theme My Login will always look in your theme's directory first, before using this default template.
 */

$user_role = reset($profileuser->roles);
if (is_multisite() && empty($user_role)) {
    $user_role = 'subscriber';
}
$level_id_var = $user_role_var = wp_get_current_user()->membership_level->id;
$user_role_var = wp_get_current_user()->membership_level->name;


$user_can_edit = false;
foreach (array('posts', 'pages') as $post_cap)
    $user_can_edit |= current_user_can("edit_$post_cap");

$profileuser = get_userdata($current_user->ID);
?>

<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>">
    <?php //$template->the_action_template_message( 'profile_update' ); ?>

    <div id="your-profile" >
        <?php wp_nonce_field('update-user_' . $current_user->ID) ?>


        <?php if ($template->options['show_gravatar']) : ?>
            <div class="tml-user-avatar"><?php $template->the_user_avatar('130'); ?></div>
        <?php endif; ?>

        <h3><?php _e('Name', 'theme-my-login') ?></h3>

        <table class="form-table">

            <tr>
                <td><label for="user_login"><?php _e('Username', 'theme-my-login'); ?></label></td>
                <td><?php echo esc_attr($profileuser->user_login); ?> </br></td>
            </tr>


        </table>



        <h3><?php _e('About Yourself', 'theme-my-login'); ?></h3>

        <table class="form-table">

            <tr >
                <td><label for="dateofbirth"><?php _e('Date of Birth', 'theme-my-login'); ?></label></td>


                <td><?php echo esc_attr($profileuser->dateofbirth) ?></td>


            </tr>    

            <tr style="vertical-align: top;">
                <td><label for="description"><?php _e('About you', 'theme-my-login'); ?></label></td>
                <td><?php echo esc_attr($profileuser->about_you); ?></td>
            </tr>


            <tr>
                <td><label for="experience_level"><?php _e('Experience Level', 'theme-my-login'); ?></label></td>
                <td>

                    <?php echo $profileuser->experience_level; ?>
                </td>
            </tr>

            <tr>
                <td><label for="location"><?php _e('Location', 'theme-my-login'); ?>*</label></td>
                <td><?php echo esc_html($profileuser->location); ?></td>
            </tr>
            <?php //do_action('personal_options', $profileuser); ?>

        </table>

        <?php if ($user_role_var == 'Pro') {
            ?>
            <h3><?php _e('Full Address', 'theme-my-login'); ?></h3>
            <table class="form-table">
                <tr>
                    <td><label for="address1"><?php _e('Address Line 1', 'theme-my-login'); ?></label></td>
                    <td><?php echo esc_html($profileuser->address1); ?></td>
                </tr>
                <tr>
                    <td><label for="address2"><?php _e('Address Line 2', 'theme-my-login'); ?></label></td>
                    <td><?php echo esc_html($profileuser->address2); ?></td>
                </tr>
                <tr>
                    <td><label for="town"><?php _e('Town', 'theme-my-login'); ?></label></td>
                    <td><?php echo esc_html($profileuser->town); ?></td>
                </tr>
                <tr>
                    <td><label for="county"><?php _e('County', 'theme-my-login'); ?></label></td>
                    <td><?php echo esc_html($profileuser->county); ?></td>
                </tr>

                <tr>
                    <td><label for="postcode"><?php _e('Postcode', 'theme-my-login'); ?></label></td>
                    <td><?php echo esc_html($profileuser->postcode); ?></td>
                </tr>
                <tr>
                    <td><label for="dire_to_venue"><?php _e('Direction to venue', 'theme-my-login'); ?></label></td>
                    <td><?php echo esc_html($profileuser->dire_to_venue); ?></td>
                </tr>
            </table>

            <h3><?php _e('Contact Info', 'theme-my-login') ?></h3>

            <table class="form-table">
                <tr>
                    <td><label for="email"><?php _e('E-mail', 'theme-my-login'); ?> <span class="description"><?php _e('(required)', 'theme-my-login'); ?></span></label></td>
                    <td><?php echo esc_attr($profileuser->user_email) ?></td>
                </tr>

                <tr>
                    <td><label for="url"><?php _e('Website', 'theme-my-login') ?></label></td>
                    <td><?php echo esc_attr($profileuser->url) ?></td>
                </tr>

                <?php
                if (function_exists('_wp_get_user_contactmethods')) :
                    foreach (_wp_get_user_contactmethods() as $name => $desc) {
                        ?>
                        <tr>
                            <td><label for="<?php echo $name; ?>"><?php echo apply_filters('user_' . $name . '_label', $desc); ?></label></td>
                            <td><?php echo esc_attr($profileuser->$name) ?></td>
                        </tr>
                        <?php
                    }
                endif;
                ?>
                <tr>
                    <td><label for="telephone_num"><?php _e('Telephone Number', 'theme-my-login') ?></label></td>
                    <td><?php echo esc_attr($profileuser->telephone_num) ?></td>
                </tr>
                <tr>
                    <td><label for="opening_hour"><?php _e('Opening Hour', 'theme-my-login') ?></label></td>
                    <td><?php echo esc_attr($profileuser->opening_hour) ?></td>
                </tr>
            </table>
        <?php }
        ?>
        <table class="form-table-edit">
            <tr>
                <td>
                    <a href="<?php echo wp_login_url();?>&action=profile&level=<?php echo $level_id_var;?>">Edit Profile</a>
                </td>
            </tr> 
        </table>






    </div>

</div>

