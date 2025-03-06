<?php

/**
 * Module: SMTP service
 */

// Prevent direct access to the plugin
defined( 'ABSPATH' ) || exit;

// Configures WordPress PHPMailer
add_action( 'phpmailer_init', function( $phpmailer ) {
	// Get the settings array
	global $hc_gems_options;

	// SMTP port number - likely to be 25, 465 or 587
	$smtp_port = $hc_gems_options['smtpport'] ?? 587;
	// Encryption system to use - ssl, tls, or empty string for no encryption
	$smtp_secure = $hc_gems_options['smtpsecure'] ?? '';
	// SMTP From email address
	$smtp_from = $hc_gems_options['smtpfrom'] ?? '';
	// SMTP From name
	$smtp_fromname = $hc_gems_options['smtpfromname'] ?? '';
	// The hostname of the mail server
	$smtp_host = $hc_gems_options['smtphost'] ?? '';
	// Username to use for SMTP authentication
	$smtp_user = $hc_gems_options['smtpuser'] ?? '';
	// Password to use for SMTP authentication
	$smtp_password = $hc_gems_options['smtppassword'] ?? '';

	if ( $smtp_host && $smtp_user && $smtp_password ) {
		$phpmailer->isSMTP();
		$phpmailer->Host = $smtp_host;
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = $smtp_user;
		$phpmailer->Password = $smtp_password;

		// Additional settings
		$phpmailer->Port = $smtp_port;
		$phpmailer->SMTPSecure = $smtp_secure;
		if ( $smtp_from ) {
			$phpmailer->From = $smtp_from;
		}
		if ( $smtp_fromname ) {
			$phpmailer->FromName = $smtp_fromname;
		}
	}
} );
