<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/

$user_role = reset( $profileuser->roles );
if ( is_multisite() && empty( $user_role ) ) {
	$user_role = 'subscriber';
}
//buy user 1
if('administrator' == $user_role)
{
    require 'profile-form.php';
}else{
    
    require 'profile-form-level1.php';
}    
?>

