<?php
	global $post, $gateway, $wpdb, $besecure, $discount_code, $pmpro_level, $pmpro_levels, $pmpro_msg, $pmpro_msgt, $pmpro_review, $skip_account_fields, $pmpro_paypal_token, $pmpro_show_discount_code, $pmpro_error_fields, $pmpro_required_billing_fields, $pmpro_required_user_fields, $wp_version;	
	
	//this var stores fields with errors so we can make them red on the frontend
	$pmpro_error_fields = array();			
	
	//blank array for required fields, set below
	$pmpro_required_billing_fields = array();
	$pmpro_required_user_fields = array();
	
	//was a gateway passed?
	if(!empty($_REQUEST['gateway']))
		$gateway = $_REQUEST['gateway'];
	elseif(!empty($_REQUEST['review']))
		$gateway = "paypalexpress";
	else
		$gateway = pmpro_getOption("gateway");			
	
	//set valid gateways - the active gateway in the settings and any gateway added through the filter will be allowed
	if(pmpro_getOption("gateway", true) == "paypal")
		$valid_gateways = apply_filters("pmpro_valid_gateways", array("paypal", "paypalexpress"));
	else
		$valid_gateways = apply_filters("pmpro_valid_gateways", array(pmpro_getOption("gateway", true)));
		
	//let's add an error now, if an invalid gateway is set
	if(!in_array($gateway, $valid_gateways))
	{	
		$pmpro_msg = "Invalid gateway.";
		$pmpro_msgt = "pmpro_error";
	}	
	
	//what level are they purchasing? (discount code passed)
	if(!empty($_REQUEST['level']) && !empty($_REQUEST['discount_code']))
	{
		$discount_code = preg_replace("/[^A-Za-z0-9]/", "", $_REQUEST['discount_code']);
		$discount_code_id = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes WHERE code = '" . $discount_code . "' LIMIT 1");
		
		//check code
		$code_check = pmpro_checkDiscountCode($discount_code, (int)$_REQUEST['level'], true);		
		if($code_check[0] == false)
		{
			//error
			$pmpro_msg = $code_check[1];
			$pmpro_msgt = "pmpro_error";
			
			//don't use this code
			$use_discount_code = false;
		}
		else
		{			
			$sqlQuery = "SELECT l.id, cl.*, l.name, l.description, l.allow_signups FROM $wpdb->pmpro_discount_codes_levels cl LEFT JOIN $wpdb->pmpro_membership_levels l ON cl.level_id = l.id LEFT JOIN $wpdb->pmpro_discount_codes dc ON dc.id = cl.code_id WHERE dc.code = '" . $discount_code . "' AND cl.level_id = '" . (int)$_REQUEST['level'] . "' LIMIT 1";			
			$pmpro_level = $wpdb->get_row($sqlQuery);
			
			//if the discount code doesn't adjust the level, let's just get the straight level
			if(empty($pmpro_level))
				$pmpro_level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '" . (int)$_REQUEST['level'] . "' LIMIT 1");

			//filter adjustments to the level
			$pmpro_level->code_id = $discount_code_id;
			$pmpro_level = apply_filters("pmpro_discount_code_level", $pmpro_level, $discount_code_id);
			
			$use_discount_code = true;
		}	
	}
		
	//what level are they purchasing? (no discount code)
	if(empty($pmpro_level) && !empty($_REQUEST['level']))
	{
		$pmpro_level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '" . $wpdb->escape($_REQUEST['level']) . "' AND allow_signups = 1 LIMIT 1");	
	}
	elseif(empty($pmpro_level))
	{
		//check if a level is defined in custom fields
		$default_level = get_post_meta($post->ID, "pmpro_default_level", true);
		if(!empty($default_level))
		{
			$pmpro_level = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '" . $wpdb->escape($default_level) . "' AND allow_signups = 1 LIMIT 1");	
		}
	}
	
	//filter the level (for upgrades, etc)
	$pmpro_level = apply_filters("pmpro_checkout_level", $pmpro_level);		
		
	if(empty($pmpro_level->id))
	{
		wp_redirect(pmpro_url("levels"));
		exit(0);
	}		
		
	global $wpdb, $current_user, $pmpro_requirebilling;	
	if(!pmpro_isLevelFree($pmpro_level) && $gateway != "check")
	{
		//require billing and ssl
		$pagetitle = "Checkout: Payment Information";
		$pmpro_requirebilling = true;
		$besecure = pmpro_getOption("use_ssl");
		/*
		if($gateway != "paypalexpress" || (!empty($_REQUEST['gateway']) && $_REQUEST['gateway'] != "paypalexpress"))
			$besecure = true;			
		else
			$besecure = false;				
		*/
	}
	else
	{
		//no payment so we don't need ssl
		$pagetitle = "Setup Your Account";
		$pmpro_requirebilling = false;
		$besecure = false;		
	}
		
	//code for stripe (unless the level is free)
	if($gateway == "stripe" && !pmpro_isLevelFree($pmpro_level))
	{
		//stripe js library
		wp_enqueue_script("stripe", "https://js.stripe.com/v1/", array(), NULL);
		
		//stripe js code for checkout
		function pmpro_stripe_javascript()
		{
		?>
		<script type="text/javascript">
			// this identifies your website in the createToken call below			
			Stripe.setPublishableKey('<?php echo pmpro_getOption("stripe_publishablekey"); ?>');
			
			var pmpro_require_billing = true;
												
			jQuery(document).ready(function() {
				jQuery(".pmpro_form").submit(function(event) {
								
				//double check in case a discount code made the level free				
				if(pmpro_require_billing)
				{
					Stripe.createToken({
						number: jQuery('#AccountNumber').val(),
						cvc: jQuery('#CVV').val(),
						exp_month: jQuery('#ExpirationMonth').val(),
						exp_year: jQuery('#ExpirationYear').val(),
						name: jQuery.trim(jQuery('#bfirstname').val() + ' ' + jQuery('#blastname').val())					
						<?php
							$pmpro_stripe_verify_address = apply_filters("pmpro_stripe_verify_address", true);
							if(!empty($pmpro_stripe_verify_address))
							{
							?>
							,address_line1: jQuery('#baddress1').val(),
							address_line2: jQuery('#baddress2').val(),
							address_zip: jQuery('#bzipcode').val(),
							address_state: jQuery('#bstate').val(),					
							address_country: jQuery('#bcountry').val()
						<?php
							}
						?>					
					}, stripeResponseHandler);

					// prevent the form from submitting with the default action
					return false;
				}
				else
					return true;	//not using Stripe anymore
				});
			});

			function stripeResponseHandler(status, response) {
				if (response.error) {
					// re-enable the submit button
                    jQuery('.pmpro_btn-submit-checkout').removeAttr("disabled");

					//hide processing message
					jQuery('#pmpro_processing_message').css('visibility', 'hidden');
					
					// show the errors on the form
					alert(response.error.message);
					jQuery(".payment-errors").text(response.error.message);
				} else {
					var form$ = jQuery(".pmpro_form");					
					// token contains id, last4, and card type
					var token = response['id'];					
					// insert the token into the form so it gets submitted to the server
					form$.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
										
					//insert fields for other card fields
					form$.append("<input type='hidden' name='CardType' value='" + response['card']['type'] + "'/>");
					form$.append("<input type='hidden' name='AccountNumber' value='XXXXXXXXXXXXX" + response['card']['last4'] + "'/>");
					form$.append("<input type='hidden' name='ExpirationMonth' value='" + response['card']['exp_month'] + "'/>");
					form$.append("<input type='hidden' name='ExpirationYear' value='" + response['card']['exp_year'] + "'/>");							
					
					// and submit
					form$.get(0).submit();
				}
			}
		</script>
		<?php
		}
		add_action("wp_head", "pmpro_stripe_javascript");
		
		//don't require the CVV
		function pmpro_stripe_dont_require_CVV($fields)
		{
			unset($fields['CVV']);			
			return $fields;
		}
		add_filter("pmpro_required_billing_fields", "pmpro_stripe_dont_require_CVV");
	}
		
	//get all levels in case we need them
	global $pmpro_levels;
	$pmpro_levels = pmpro_getAllLevels();	
	
	//should we show the discount code field?
	if($wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes LIMIT 1"))
		$pmpro_show_discount_code = true;
	else
		$pmpro_show_discount_code = false;
	$pmpro_show_discount_code = apply_filters("pmpro_show_discount_code", $pmpro_show_discount_code);
		
	//by default we show the account fields if the user isn't logged in
	if($current_user->ID)
	{
		$skip_account_fields = true;
	}
	else
	{
		$skip_account_fields = false;
	}	
	//in case people want to have an account created automatically
	$skip_account_fields = apply_filters("pmpro_skip_account_fields", $skip_account_fields, $current_user);
	
	//some options
	global $tospage;
	$tospage = pmpro_getOption("tospage");
	if($tospage)
		$tospage = get_post($tospage);
	
	//load em up (other fields)
	global $username, $password, $password2, $bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $bconfirmemail, $CardType, $AccountNumber, $ExpirationMonth, $ExpirationYear;
	
	if(isset($_REQUEST['order_id']))
		$order_id = $_REQUEST['order_id'];
	else
		$order_id = "";
	if(isset($_REQUEST['bfirstname']))
		$bfirstname = trim(stripslashes($_REQUEST['bfirstname']));	
	else
		$bfirstname = "";
	if(isset($_REQUEST['blastname']))
		$blastname = trim(stripslashes($_REQUEST['blastname']));	
	else
		$blastname = "";
	if(isset($_REQUEST['fullname']))
		$fullname = $_REQUEST['fullname'];		//honeypot for spammers
	if(isset($_REQUEST['baddress1']))
		$baddress1 = trim(stripslashes($_REQUEST['baddress1']));		
	else
		$baddress1 = "";
	if(isset($_REQUEST['baddress2']))
		$baddress2 = trim(stripslashes($_REQUEST['baddress2']));
	else
		$baddress2 = "";
	if(isset($_REQUEST['bcity']))
		$bcity = trim(stripslashes($_REQUEST['bcity']));
	else
		$bcity = "";
	if(isset($_REQUEST['bstate']))
		$bstate = trim(stripslashes($_REQUEST['bstate']));
	else
		$bstate = "";
	if(isset($_REQUEST['bzipcode']))
		$bzipcode = trim(stripslashes($_REQUEST['bzipcode']));
	else
		$bzipcode = "";
	if(isset($_REQUEST['bcountry']))
		$bcountry = trim(stripslashes($_REQUEST['bcountry']));
	else
		$bcountry = "";
	if(isset($_REQUEST['bphone']))
		$bphone = trim(stripslashes($_REQUEST['bphone']));
	else
		$bphone = "";
	if(isset($_REQUEST['bemail']))
		$bemail = trim(stripslashes($_REQUEST['bemail']));
	else
		$bemail = "";
	if(isset($_REQUEST['bconfirmemail_copy']))
		$bconfirmemail = $bemail;	
	elseif(isset($_REQUEST['bconfirmemail']))
		$bconfirmemail = trim(stripslashes($_REQUEST['bconfirmemail']));
	else
		$bconfirmemail = "";
		
	if(isset($_REQUEST['CardType']))
		$CardType = $_REQUEST['CardType'];
	else
		$CardType = "";
	if(isset($_REQUEST['AccountNumber']))
		$AccountNumber = trim($_REQUEST['AccountNumber']);
	else
		$AccountNumber = "";
	if(isset($_REQUEST['ExpirationMonth']))
		$ExpirationMonth = $_REQUEST['ExpirationMonth'];
	else
		$ExpirationMonth = "";
	if(isset($_REQUEST['ExpirationYear']))
		$ExpirationYear = $_REQUEST['ExpirationYear'];
	else
		$ExpirationYear = "";
	if(isset($_REQUEST['CVV']))
		$CVV = trim($_REQUEST['CVV']);
	else
		$CVV = "";
	
	if(isset($_REQUEST['discount_code']))
		$discount_code = trim($_REQUEST['discount_code']);
	else
		$discount_code = "";
	if(isset($_REQUEST['username']))
		$username = trim($_REQUEST['username']);
	else
		$username = "";
	if(isset($_REQUEST['password']))
		$password = $_REQUEST['password'];
	else
		$password = "";
	if(isset($_REQUEST['password2_copy']))
		$password2 = $password;	
	elseif(isset($_REQUEST['password2']))
		$password2 = $_REQUEST['password2'];
	else
		$password2 = "";
	if(isset($_REQUEST['tos']))
		$tos = $_REQUEST['tos'];		
	else
		$tos = "";
	
	//for stripe, load up token values
	if(isset($_REQUEST['stripeToken']))
	{
		$stripeToken = $_REQUEST['stripeToken'];				
	}
	
	//_x stuff in case they clicked on the image button with their mouse
	if(isset($_REQUEST['submit-checkout']))
		$submit = $_REQUEST['submit-checkout'];
	if(empty($submit) && isset($_REQUEST['submit-checkout_x']) )
		$submit = $_REQUEST['submit-checkout_x'];	
	if(isset($submit) && $submit === "0") 
		$submit = true;	
	elseif(!isset($submit))
		$submit = false;
		
	//require fields
	$pmpro_required_billing_fields = array(
		"bfirstname" => $bfirstname,
		"blastname" => $blastname,
		"baddress1" => $baddress1,
		"bcity" => $bcity,
		"bstate" => $bstate,
		"bzipcode" => $bzipcode,
		"bphone" => $bphone,
		"bemail" => $bemail,
		"bcountry" => $bcountry,
		"CardType" => $CardType,
		"AccountNumber" => $AccountNumber,
		"ExpirationMonth" => $ExpirationMonth,
		"ExpirationYear" => $ExpirationYear,
		"CVV" => $CVV
	);
	$pmpro_required_billing_fields = apply_filters("pmpro_required_billing_fields", $pmpro_required_billing_fields);		
	$pmpro_required_user_fields = array(
		"username" => $username,
		"password" => $password,
		"password2" => $password2,
		"bemail" => $bemail,
		"bconfirmemail" => $bconfirmemail
	);
	$pmpro_required_user_fields = apply_filters("pmpro_required_user_fields", $pmpro_required_user_fields);
	
	//check their fields if they clicked continue
	if($submit && $pmpro_msgt != "pmpro_error")
	{		
		//if we're skipping the account fields and there is no user, we need to create a username and password
		if($skip_account_fields && !$current_user->ID)
		{
			$username = pmpro_generateUsername($bfirstname, $blastname, $bemail);
			if(empty($username))
				$username = pmpro_getDiscountCode();
			$password = pmpro_getDiscountCode() . pmpro_getDiscountCode();	//using two random discount codes
			$password2 = $password;
		}	
				
		if($pmpro_requirebilling && $gateway != "paypalexpress" && $gateway != "paypalstandard")
		{									
			//if using stripe lite, remove some fields from the required array
			$pmpro_stripe_lite = apply_filters("pmpro_stripe_lite", false);
			if($pmpro_stripe_lite && $gateway == "stripe")
			{
				//some fields to remove
				$remove = array('bfirstname', 'blastname', 'baddress1', 'bcity', 'bstate', 'bzipcode', 'bphone', 'bcountry', 'CardType');
				
				//if a user is logged in, don't require bemail either				
				if(!empty($current_user->user_email))
				{
					$remove[] = 'bemail';
					$bemail = $current_user->user_email;
					$bconfirmemail = $bemail;
				}
				
				//remove the fields
				foreach($remove as $field)
					unset($pmpro_required_billing_fields[$field]);
			}
			
			//filter							
			foreach($pmpro_required_billing_fields as $key => $field)
			{
				if(!$field)
				{																				
					$pmpro_error_fields[] = $key;					
				}
			}
		}
		
		//check user fields
		if(empty($current_user->ID))
		{			
			foreach($pmpro_required_user_fields as $key => $field)
			{
				if(!$field)
				{																				
					$pmpro_error_fields[] = $key;					
				}
			}
		}
			
		if(!empty($pmpro_error_fields))
		{
			pmpro_setMessage("Please complete all required fields.", "pmpro_error");
		}				
		if(!empty($password) && $password != $password2)
		{			
			pmpro_setMessage("Your passwords do not match. Please try again.", "pmpro_error");
			$pmpro_error_fields[] = "password";
			$pmpro_error_fields[] = "password2";
		}
		if(!empty($bemail) && $bemail != $bconfirmemail)
		{
			pmpro_setMessage("Your email addresses do not match. Please try again.", "pmpro_error");
			$pmpro_error_fields[] = "bemail";
			$pmpro_error_fields[] = "bconfirmemail";			
		}		
		if(!empty($bemail) && !is_email($bemail))
		{
			pmpro_setMessage("The email address entered is in an invalid format. Please try again.", "pmpro_error");
			$pmpro_error_fields[] = "bemail";
			$pmpro_error_fields[] = "bconfirmemail";				
		}
		if(!empty($tospage) && empty($tos))
		{
			pmpro_setMessage("Please check the box to agree to the " . $tospage->post_title . ".", "pmpro_error");
			$pmpro_error_fields[] = "tospage";					
		}
		if(!in_array($gateway, $valid_gateways))
		{
			pmpro_setMessage("Invalid gateway.", "pmpro_error");			
		}
		if(!empty($fullname))
		{
			pmpro_setMessage("Are you a spammer?", "pmpro_error");			
		}
		
		if($pmpro_msgt == "pmpro_error")
			$pmpro_continue_registration = false;
		else
			$pmpro_continue_registration = true;
		$pmpro_continue_registration = apply_filters("pmpro_registration_checks", $pmpro_continue_registration);
		
		if($pmpro_continue_registration)
		{											
			//if creating a new user, check that the email and username are available
			if(empty($current_user->ID))
			{
				$oldusername = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE user_login = '" . $wpdb->escape($username) . "' LIMIT 1");
				$oldemail = $wpdb->get_var("SELECT user_email FROM $wpdb->users WHERE user_email = '" . $wpdb->escape($bemail) . "' LIMIT 1");
				
				//this hook can be used to allow multiple accounts with the same email address
				$oldemail = apply_filters("pmpro_checkout_oldemail", $oldemail);
			}
			
			if(!empty($oldusername))
			{
				pmpro_setMessage("That username is already taken. Please try another.", "pmpro_error");
				$pmpro_error_fields[] = "username";				
			}
			
			if(!empty($oldemail))
			{
				pmpro_setMessage("That email address is already taken. Please try another.", "pmpro_error");
				$pmpro_error_fields[] = "bemail";						
				$pmpro_error_fields[] = "bconfirmemail";						
			}
			
			//only continue if there are no other errors yet
			if($pmpro_msgt != "pmpro_error")
			{								
				//check recaptch first
				global $recaptcha;
				if(!$skip_account_fields && ($recaptcha == 2 || ($recaptcha == 1 && pmpro_isLevelFree($pmpro_level))))
				{
					global $recaptcha_privatekey;					
					$resp = recaptcha_check_answer($recaptcha_privatekey,
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);
						
					if(!$resp->is_valid) 
					{
						$pmpro_msg = "reCAPTCHA failed. (" . $resp->error . ") Please try again.";
						$pmpro_msgt = "pmpro_error";
					} 
					else 
					{
						// Your code here to handle a successful verification
						if($pmpro_msgt != "pmpro_error")
							$pmpro_msg = "All good!";
					}
				}
				else
				{
					if($pmpro_msgt != "pmpro_error")
						$pmpro_msg = "All good!";										
				}
				
				//no errors yet
				if($pmpro_msgt != "pmpro_error")
				{				
					//save user fields for PayPal Express
					if($gateway == "paypalexpress" || $gateway == "paypalstandard")
					{
						if(!$current_user->ID)
						{
							$_SESSION['pmpro_signup_username'] = $username;
							$_SESSION['pmpro_signup_password'] = $password;
							$_SESSION['pmpro_signup_email'] = $bemail;														
						}
						
						//can use this hook to save some other variables to the session
						do_action("pmpro_paypalexpress_session_vars");							
					}
					
					//special check here now for the "check" gateway
					if($pmpro_requirebilling || ($gateway == "check" && !pmpro_isLevelFree($pmpro_level)))
					{
						$morder = new MemberOrder();			
						$morder->membership_id = $pmpro_level->id;
						$morder->membership_name = $pmpro_level->name;
						$morder->discount_code = $discount_code;
						$morder->InitialPayment = $pmpro_level->initial_payment;
						$morder->PaymentAmount = $pmpro_level->billing_amount;
						$morder->ProfileStartDate = date("Y-m-d") . "T0:0:0";
						$morder->BillingPeriod = $pmpro_level->cycle_period;
						$morder->BillingFrequency = $pmpro_level->cycle_number;
								
						if($pmpro_level->billing_limit)
							$morder->TotalBillingCycles = $pmpro_level->billing_limit;
					
						if(pmpro_isLevelTrial($pmpro_level))
						{
							$morder->TrialBillingPeriod = $pmpro_level->cycle_period;
							$morder->TrialBillingFrequency = $pmpro_level->cycle_number;
							$morder->TrialBillingCycles = $pmpro_level->trial_limit;
							$morder->TrialAmount = $pmpro_level->trial_amount;
						}
						
						//credit card values
						$morder->cardtype = $CardType;
						$morder->accountnumber = $AccountNumber;
						$morder->expirationmonth = $ExpirationMonth;
						$morder->expirationyear = $ExpirationYear;
						$morder->ExpirationDate = $ExpirationMonth . $ExpirationYear;
						$morder->ExpirationDate_YdashM = $ExpirationYear . "-" . $ExpirationMonth;
						$morder->CVV2 = $CVV;												
						
						//stripeToken
						if(isset($stripeToken))
							$morder->stripeToken = $stripeToken;
						
						//not saving email in order table, but the sites need it
						$morder->Email = $bemail;
						
						//sometimes we need these split up
						$morder->FirstName = $bfirstname;
						$morder->LastName = $blastname;						
						$morder->Address1 = $baddress1;
						$morder->Address2 = $baddress2;						
						
						//stripe lite code to get name from other sources if available
						if(!empty($pmpro_stripe_lite) && empty($morder->FirstName) && empty($morder->LastName))
						{
							if(!empty($current_user->ID))
							{									
								$morder->FirstName = get_user_meta($current_user->ID, "first_name", true);
								$morder->LastName = get_user_meta($current_user->ID, "last_name", true);
							}
							elseif(!empty($_REQUEST['first_name']) && !empty($_REQUEST['last_name']))
							{
								$morder->FirstName = $_REQUEST['first_name'];
								$morder->LastName = $_REQUEST['last_name'];
							}
						}
						
						//other values
						$morder->billing = new stdClass();
						$morder->billing->name = $bfirstname . " " . $blastname;
						$morder->billing->street = trim($baddress1 . " " . $baddress2);
						$morder->billing->city = $bcity;
						$morder->billing->state = $bstate;
						$morder->billing->country = $bcountry;
						$morder->billing->zip = $bzipcode;
						$morder->billing->phone = $bphone;
								
						//$gateway = pmpro_getOption("gateway");										
						$morder->gateway = $gateway;
						$morder->setGateway();
													
						//setup level var
						$morder->getMembershipLevel();
						
						//tax
						$morder->subtotal = $morder->InitialPayment;
						$morder->getTax();						
													
						if($gateway == "paypalexpress")
						{
							$morder->payment_type = "PayPal Express";
							$morder->cardtype = "";
							$morder->ProfileStartDate = date("Y-m-d", strtotime("+ " . $morder->BillingFrequency . " " . $morder->BillingPeriod)) . "T0:0:0";
							$morder->ProfileStartDate = apply_filters("pmpro_profile_start_date", $morder->ProfileStartDate, $morder);							
							$pmpro_processed = $morder->Gateway->setExpressCheckout($morder);
						}
						else
						{
							$pmpro_processed = $morder->process();
						}
													
						if(!empty($pmpro_processed))
						{
							$pmpro_msg = "Payment accepted.";
							$pmpro_msgt = "pmpro_success";	
							$pmpro_confirmed = true;
						}			
						else
						{																								
							$pmpro_msg = $morder->error;
							if(empty($pmpro_msg))
								$pmpro_msg = "Unknown error generating account. Please contact us to setup your membership.";
							$pmpro_msgt = "pmpro_error";								
						}	
													
					}		
					else // !$pmpro_requirebilling
					{
						//must have been a free membership, continue							
						$pmpro_confirmed = true;
					}
				}													
			}
		}	//endif($pmpro_continue_registration)		
	}				
		
	//PayPal Express Call Backs
	if(!empty($_REQUEST['review']))
	{	
		if(!empty($_REQUEST['PayerID']))
			$_SESSION['payer_id'] = $_REQUEST['PayerID'];
		if(!empty($_REQUEST['paymentAmount']))
			$_SESSION['paymentAmount'] = $_REQUEST['paymentAmount'];
		if(!empty($_REQUEST['currencyCodeType']))
			$_SESSION['currCodeType'] = $_REQUEST['currencyCodeType'];
		if(!empty($_REQUEST['paymentType']))
			$_SESSION['paymentType'] = $_REQUEST['paymentType'];
		
		$morder = new MemberOrder();
		$morder->getMemberOrderByPayPalToken($_REQUEST['token']);
		$morder->Token = $morder->paypal_token; $pmpro_paypal_token = $morder->paypal_token;				
		if($morder->Token)
		{
			if($morder->Gateway->getExpressCheckoutDetails($morder))
			{
				$pmpro_review = true;
			}
			else
			{
				$pmpro_msg = $morder->error;
				$pmpro_msgt = "error";
			}		
		}
		else
		{
			$pmpro_msg = "The PayPal Token was lost.";
			$pmpro_msgt = "error";
		}
	}
	elseif(!empty($_REQUEST['confirm']))
	{		
		$morder = new MemberOrder();
		$morder->getMemberOrderByPayPalToken($_REQUEST['token']);
		$morder->Token = $morder->paypal_token; $pmpro_paypal_token = $morder->paypal_token;	
		if($morder->Token)
		{		
			//setup values
			$morder->membership_id = $pmpro_level->id;
			$morder->membership_name = $pmpro_level->name;
			$morder->discount_code = $discount_code;
			$morder->InitialPayment = $pmpro_level->initial_payment;
			$morder->PaymentAmount = $pmpro_level->billing_amount;
			$morder->ProfileStartDate = date("Y-m-d") . "T0:0:0";
			$morder->BillingPeriod = $pmpro_level->cycle_period;
			$morder->BillingFrequency = $pmpro_level->cycle_number;
			$morder->Email = $bemail;
			
			//$gateway = pmpro_getOption("gateway");																	
			
			//setup level var
			$morder->getMembershipLevel();			
			
			//tax
			$morder->subtotal = $morder->InitialPayment;
			$morder->getTax();				
			if($pmpro_level->billing_limit)
				$morder->TotalBillingCycles = $pmpro_level->billing_limit;
		
			if(pmpro_isLevelTrial($pmpro_level))
			{
				$morder->TrialBillingPeriod = $pmpro_level->cycle_period;
				$morder->TrialBillingFrequency = $pmpro_level->cycle_number;
				$morder->TrialBillingCycles = $pmpro_level->trial_limit;
				$morder->TrialAmount = $pmpro_level->trial_amount;
			}
						
			if($morder->process())
			{						
				$submit = true;
				$pmpro_confirmed = true;
			
				if(!$current_user->ID)
				{
					//reload the user fields			
					$username = $_SESSION['pmpro_signup_username'];
					$password = $_SESSION['pmpro_signup_password'];
					$password2 = $password;
					$bemail = $_SESSION['pmpro_signup_email'];
					
					//unset the user fields in session
					unset($_SESSION['pmpro_signup_username']);
					unset($_SESSION['pmpro_signup_password']);
					unset($_SESSION['pmpro_signup_email']);
				}
			}
			else
			{								
				$pmpro_msg = $morder->error;
				$pmpro_msgt = "error";
			}
		}
		else
		{
			$pmpro_msg = "The PayPal Token was lost.";
			$pmpro_msgt = "error";
		}
	}
	
	//if payment was confirmed create/update the user.
	if(!empty($pmpro_confirmed))
	{
		//do we need to create a user account?
		if(!$current_user->ID)
		{
			// create user
			if(version_compare($wp_version, "3.1") < 0)
				require_once( ABSPATH . WPINC . '/registration.php');	//need this for WP versions before 3.1
			$user_id = wp_insert_user(array(
							"user_login" => $username,							
							"user_pass" => $password,
							"user_email" => $bemail,
							"first_name" => $bfirstname,
							"last_name" => $blastname)
							);
			if (!$user_id) {
				$pmpro_msg = "Your payment was accepted, but there was an error setting up your account. Please contact us.";
				$pmpro_msgt = "pmpro_error";
			} else {
			
				//check pmpro_wp_new_user_notification filter before sending the default WP email
				if(apply_filters("pmpro_wp_new_user_notification", true, $user_id, $pmpro_level->id))
					wp_new_user_notification($user_id, $password);								
		
				$wpuser = new WP_User(0, $username);
		
				//make the user a subscriber
				$wpuser->set_role("subscriber");
									
				//okay, log them in to WP							
				$creds = array();
				$creds['user_login'] = $username;
				$creds['user_password'] = $password;
				$creds['remember'] = true;
				$user = wp_signon( $creds, false );																	
			}
		}
		else
			$user_id = $current_user->ID;	
		
		if($user_id)
		{				
			//save user id and send PayPal standard customers to PayPal now
			if($gateway == "paypalstandard" && !empty($morder))
			{
				$morder->user_id = $user_id;				
				$morder->saveOrder();
				
				do_action("pmpro_before_send_to_paypal_standard", $user_id, $morder);
				
				$morder->Gateway->sendToPayPal($morder);
			}
			
			//calculate the end date
			if(!empty($pmpro_level->expiration_number))
			{
				$enddate = "'" . date("Y-m-d", strtotime("+ " . $pmpro_level->expiration_number . " " . $pmpro_level->expiration_period)) . "'";
			}
			else
			{
				$enddate = "NULL";
			}
			
			//update membership_user table.
			if(!empty($discount_code) && !empty($use_discount_code))
				$discount_code_id = $wpdb->get_var("SELECT id FROM $wpdb->pmpro_discount_codes WHERE code = '" . $discount_code . "' LIMIT 1");
			else
				$discount_code_id = "";
			
			//set the start date to NOW() but allow filters
			$startdate = apply_filters("pmpro_checkout_start_date", "NOW()", $user_id, $pmpro_level);
			
			$custom_level = array(
				'user_id' => $user_id,
				'membership_id' => $pmpro_level->id,
				'code_id' => $discount_code_id,
				'initial_payment' => $pmpro_level->initial_payment,
				'billing_amount' => $pmpro_level->billing_amount,
				'cycle_number' => $pmpro_level->cycle_number,
				'cycle_period' => $pmpro_level->cycle_period,
				'billing_limit' => $pmpro_level->billing_limit,
				'trial_amount' => $pmpro_level->trial_amount,
				'trial_limit' => $pmpro_level->trial_limit,
				'startdate' => $startdate,
				'enddate' => $enddate);

			if(pmpro_changeMembershipLevel($custom_level, $user_id))
			{
				//we're good
				//add an item to the history table, cancel old subscriptions
				if(!empty($morder))
				{
					$morder->user_id = $user_id;
					$morder->membership_id = $pmpro_level->id;					
					$morder->saveOrder();
				}
			
				//update the current user
				global $current_user;
				if(!$current_user->ID && $user->ID)
					$current_user = $user; //in case the user just signed up
				pmpro_set_current_user();
			
				//add discount code use				
				if($discount_code && $use_discount_code)
				{
					if(!empty($morder->id))
						$code_order_id = $morder->id;
					else
						$code_order_id = "";
						
					$wpdb->query("INSERT INTO $wpdb->pmpro_discount_codes_uses (code_id, user_id, order_id, timestamp) VALUES('" . $discount_code_id . "', '" . $current_user->ID . "', '" . intval($code_order_id) . "', now())");										
				}
			
				//save billing info ect, as user meta																		
				$meta_keys = array("pmpro_bfirstname", "pmpro_blastname", "pmpro_baddress1", "pmpro_baddress2", "pmpro_bcity", "pmpro_bstate", "pmpro_bzipcode", "pmpro_bcountry", "pmpro_bphone", "pmpro_bemail", "pmpro_CardType", "pmpro_AccountNumber", "pmpro_ExpirationMonth", "pmpro_ExpirationYear");
				$meta_values = array($bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $CardType, hideCardNumber($AccountNumber), $ExpirationMonth, $ExpirationYear);						
				pmpro_replaceUserMeta($user_id, $meta_keys, $meta_values);	
				
				//save first and last name fields
				if(!empty($bfirstname))
				{
					$old_firstname = get_user_meta($user_id, "first_name", true);
					if(!empty($old_firstname))
						update_user_meta($user_id, "first_name", $bfirstname);
				}
				if(!empty($blastname))
				{
					$old_lastname = get_user_meta($user_id, "last_name", true);
					if(!empty($old_lastname))
						update_user_meta($user_id, "last_name", $blastname);
				}
						
				//show the confirmation
				$ordersaved = true;
				
				//for Stripe, let's save the customer id in user meta
				if($gateway == "stripe")
				{
					if(!empty($morder->Gateway->customer->id))
					{
						update_user_meta($user_id, "pmpro_stripe_customerid", $morder->Gateway->customer->id);
					}
				}
								
				//hook
				do_action("pmpro_after_checkout", $user_id);						
				
				//setup some values for the emails
				if(!empty($morder))
					$invoice = new MemberOrder($morder->id);						
				else
					$invoice = NULL;
				$current_user->membership_level = $pmpro_level;		//make sure they have the right level info
				
				//send email to member
				$pmproemail = new PMProEmail();				
				$pmproemail->sendCheckoutEmail($current_user, $invoice);
												
				//send email to admin
				$pmproemail = new PMProEmail();
				$pmproemail->sendCheckoutAdminEmail($current_user, $invoice);
												
				//redirect to confirmation
                                
                                //action=profile&level=1
				$rurl = pmpro_url("profile", "?level=" . $pmpro_level->id);
				$rurl = apply_filters("pmpro_confirmation_url", $rurl, $user_id, $pmpro_level);
                                //wp_redirect($rurl);
				wp_redirect(wp_login_url().'&action=profile&level=' . $pmpro_level->id);
				exit;
			}
			else
			{
				//uh oh. we charged them then the membership creation failed
				if($morder->cancel())
				{
					$pmpro_msg = "IMPORTANT: Something went wrong during membership creation. Your credit card authorized, but we cancelled the order immediately. You should not try to submit this form again. Please contact the site owner to fix this issue.";
					$morder = NULL;
				}
				else
				{
					$pmpro_msg = "IMPORTANT: Something went wrong during membership creation. Your credit card was charged, but we couldn't assign your membership. You should not submit this form again. Please contact the site owner to fix this issue.";
				}
			}
		}
	}	
	
	//default values
	if(empty($submit))
	{
		//show message if the payment gateway is not setup yet
		if($pmpro_requirebilling && !pmpro_getOption("gateway", true))
		{
			if(pmpro_isAdmin())			
				$pmpro_msg = "You must <a href=\"" . get_admin_url(NULL, '/admin.php?page=pmpro-membershiplevels&view=payment') . "\">setup a Payment Gateway</a> before any payments will be processed.";
			else
				$pmpro_msg = "A Payment Gateway must be setup before any payments will be processed.";
			$pmpro_msgt = "";
		}
		
		//default values from DB
		$bfirstname = get_user_meta($current_user->ID, "pmpro_bfirstname", true);
		$blastname = get_user_meta($current_user->ID, "pmpro_blastname", true);
		$baddress1 = get_user_meta($current_user->ID, "pmpro_baddress1", true);
		$baddress2 = get_user_meta($current_user->ID, "pmpro_baddress2", true);
		$bcity = get_user_meta($current_user->ID, "pmpro_bcity", true);
		$bstate = get_user_meta($current_user->ID, "pmpro_bstate", true);
		$bzipcode = get_user_meta($current_user->ID, "pmpro_bzipcode", true);
		$bcountry = get_user_meta($current_user->ID, "pmpro_bcountry", true);
		$bphone = get_user_meta($current_user->ID, "pmpro_bphone", true);
		$bemail = get_user_meta($current_user->ID, "pmpro_bemail", true);
		$bconfirmemail = get_user_meta($current_user->ID, "pmpro_bconfirmemail", true);
		$CardType = get_user_meta($current_user->ID, "pmpro_CardType", true);
		//$AccountNumber = hideCardNumber(get_user_meta($current_user->ID, "pmpro_AccountNumber", true), false);
		$ExpirationMonth = get_user_meta($current_user->ID, "pmpro_ExpirationMonth", true);
		$ExpirationYear = get_user_meta($current_user->ID, "pmpro_ExpirationYear", true);	
	}			
?>
