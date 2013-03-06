<link rel='stylesheet' id='thickbox-css'  href='<?php echo includes_url('js/thickbox/thickbox.css')?>' type='text/css' media='all' />

<?php

        $profileuser = get_userdata($current_user->ID);
        do_action( 'show_user_profile', $profileuser );
        
?>
<div style="clear: both"></div>
<p class="skip_url"  style="padding-left: 88px;"><a href="<?php $template->the_action_url( 'profile_update' )?>">You can skip this</a></p>

<script type='text/javascript'>
/* <![CDATA[ */
var commonL10n = {"warnDelete":"You are about to permanently delete the selected items.\n  'Cancel' to stop, 'OK' to delete."};var pwsL10n = {"empty":"Strength indicator","short":"Very weak","bad":"Weak","good":"Medium","strong":"Strong","mismatch":"Mismatch"};var thickboxL10n = {"next":"Next >","prev":"< Prev","image":"Image","of":"of","close":"Close","noiframes":"This feature requires inline frames. You have iframes disabled or your browser does not support them.","loadingAnimation":"http:\/\/192.168.1.50\/projects\/jems\/wp-includes\/js\/thickbox\/loadingAnimation.gif","closeImage":"http:\/\/192.168.1.50\/projects\/jems\/wp-includes\/js\/thickbox\/tb-close.png"};/* ]]> */
</script>

<script type='text/javascript' src='<?php echo includes_url('js/thickbox/thickbox.js')?>'></script>


