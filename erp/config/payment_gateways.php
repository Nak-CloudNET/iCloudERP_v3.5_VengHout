<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ***************** Paypal Settings ***************** */
/** 
 * Sandbox / Test Mode
 * -------------------------
 * TRUE means you'll be hitting PayPal's sandbox /Stripe test mode. FALSE means you'll be hitting the live servers.
 */
$config['TestMode'] = FALSE;

/* 
 * PayPal API Version
 * ------------------
 * The library is currently using PayPal API version 98.0.  
 * You may adjust this value here and then pass it into the PayPal object when you create it within your scripts to override if necessary.
 */
$config['APIVersion'] = '98.0';

/*
 * PayPal Gateway API Credentials
 * ------------------------------
 * These are your PayPal API credentials for working with the PayPal gateway directly.
 * These are used any time you're using the parent PayPal class within the library.
 * 
 * We're using shorthand if/else statements here to set both Sandbox and Production values.
 * Your sandbox values go on the left and your live values go on the right.
 * 
 * You may obtain these credentials by logging into the following with your PayPal account: https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run
 */
$config['APIUsername'] = $config['TestMode'] ? '' : 'saleem-facilitator_api1.tecdiary.com';
$config['APIPassword'] = $config['TestMode'] ? '' : '1405304892';
$config['APISignature'] = $config['TestMode'] ? '' : 'AFcWxV21C7fd0v3bYYYRCpSSRl31AcvmDmbNIqld1Dh0WjWcXFjJeFOF';


/* ***************** Stripe Keys ***************** */
/* 
 * Stripe API Keys
 * ------------------ 
 * You may obtain these by visiting account settings link and then API keys at https://dashboard.stripe.com/login
 */
$config['stripe_secret_key']			= $config['TestMode'] ? '' : 'sk_test_jHf4cEzAYtgcXvgWPCsQAn50'; 
$config['stripe_publishable_key']		= $config['TestMode'] ? '' : 'pk_test_beat8SWPORb0OVdF2H77A7uG'; 

/* End of file payment_gateways.php */
/* Location: ./erp/config/payment_gateways.php */