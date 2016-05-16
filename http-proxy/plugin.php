<?php
/*
Plugin Name: HTTP Proxy for YOURLS
Plugin URI: https://github.com/adigitalife/yourls-http-proxy
Description: This plugin HTTP proxy support for YOURLs.
Version: 1.0
Author: Aylwin
Author URI: http://adigitalife.net/
*/

// Hook our custom function into the 'get_remote_content' filter
yourls_add_filter( 'get_remote_content', 'proxy_get_remote_content' );

// Add a new link in the DB, either with custom keyword, or find one
function proxy_get_remote_content( $content, $url, $maxlen, $timeout ) {

	$proxy = 'http://PROXY_SERVER:PORT/';  // Define your proxy server here

	if ( $content ){
	return yourls_apply_filter( 'proxy_get_remote_content', $content, $url, $maxlen, $timeout );
	}

	require_once( YOURLS_INC.'/functions-http.php' );
	
	$response = '';
	if( function_exists( 'curl_init' ) && function_exists( 'curl_exec' ) ){
		$url = yourls_sanitize_url( $url );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_PROXY, $proxy );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // follow redirects...
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 ); // ... but not more than 3
		curl_setopt( $ch, CURLOPT_USERAGENT, yourls_http_user_agent() );
		curl_setopt( $ch, CURLOPT_RANGE, "0-{$maxlen}" ); // Get no more than $maxlen
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // dont check SSL certificates
		curl_setopt( $ch, CURLOPT_HEADER, 0 );

		$response = curl_exec( $ch );
		curl_close( $ch );
	}
	
	if( !$response || curl_error( $ch ) ) {
		//$response = 'Error: '.curl_error( $ch );
	return yourls_apply_filter( 'proxy_get_remote_content', $content, $url, $maxlen, $timeout );
	}

	$content = substr( $response, 0, $maxlen ); // substr in case CURLOPT_RANGE not supported
	
	return yourls_apply_filter( 'proxy_get_remote_content', $content, $url, $maxlen, $timeout );

}
