<?php
/*
@wordpress-plugin
Plugin Name: PayGol plugin for Woocommerce.
Author URI: https://www.paygol.com
Description: Paygol for Woocommerce. PayGol is an online payment service provider offering a wide variety of both worldwide and local payment methods including (but not limited to) credit card, debit card, bank transfer and cash payments. Local payment methods supported include WebPay, OXXO, Boleto, DineroMail, MercadoPago and many others. The simplicity of its integration makes it very easy for anyone to use it, and this ease of use translates perfectly to this plugin.
Author: PayGol
Version: 1.1
License: GNU General Public License v2
Text Domain: paygol_wc
*/
$plugin_header_translate = array( __('PayGol for WooCommerce', 'paygol_wc'), __('Allows your customers from all around the world to pay by using a wide variety of both international and local payment methods including credit cards, debit cards (including Redcompra through WebPay), electronic bank transfers, cash payments, OXXO, Boleto Bancario, MercadoPago and much more through the PayGol online payment platform.', 'paygol_wc') );
add_action( 'plugins_loaded', 'paygol_plugins_loaded' );
add_action( 'init', 'paygol_init' );
//////////////////////////////////////////////////////////////////////
function paygol_init() {
	load_plugin_textdomain( "paygol_wc", false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
//////////////////////////////////////////////////////////////////////
function paygol_plugins_loaded() {
	if ( !class_exists( 'WC_Payment_Gateway' ) ) exit;

	include_once ('class_wc_paygol_gateway.php');
	add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_gateway_paygol_gateway' );
}
//////////////////////////////////////////////////////////////////////       
function woocommerce_add_gateway_paygol_gateway($methods) {
	$methods[] = 'WC_PayGol_Gateway';
	return $methods;
}
//////////////////////////////////////////////////////////////////////
function paygol_plugin_row_meta( $links, $file ){
	if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
		$new_links = array(					
          '<a href="'.plugin_dir_url(__FILE__).'readme.txt" target="_blank">' . __( 'Documentation', "paygol_wc" ) . '</a>'
				);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter('plugin_row_meta', 'paygol_plugin_row_meta', 10, 2); 
?>