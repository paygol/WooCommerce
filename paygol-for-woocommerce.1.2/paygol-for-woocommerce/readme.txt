=== PayGol for WooCommerce ===

Contributors: paygol
Tags: paygol, woocommerce, payment, gateway, credit, card, credit card, webpay, tarjeta, paysafecard, oxxo, boleto, bitcoin, sms, shortcode, keyword, sms premium, sms billing, paygol, worldwide payments, e-commerce, ecommerce, mobile payments, pay by phone, pay by sms, pay per call
Requires at least: 3.7.0
Tested up to: 4.7.3
Stable tag: 1.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

PayGol is an online payment service provider offering a wide variety of both worldwide and local payment methods.

== Description ==

PayGol is an online payment service provider offering a wide variety of both worldwide and local payment methods including (but not limited to)
credit card, debit card, bank transfer and cash payments. Local payment methods supported include WebPay, OXXO, Boleto, DineroMail, MercadoPago 
and many others. The simplicity of its integration makes it very easy for anyone to use it, and this
ease of use translates perfectly to this plugin.

Website:         https://www.paygol.com

Payment methods: https://www.paygol.com/pricing

== Installation ==

- You'll need a working WordPress installation using the WooCommerce plugin (tested on versions 2.3.0 up to 2.6.14).

- You'll also need a standard PayGol service 
  You can create a PayGol account at https://www.paygol.com/register, then a service at https://www.paygol.com/webapps.

- Go to "Plugins -> Add new", search for the PayGol plugin in the WordPress Plugin Directory then click "Install now".
  You can also click "Upload plugin" to manually upload the plugins' zip file, after which it will be installed automatically.
  If for any reason this fails, you can also manually extract the plugin's folder into wp-content/plugins/.
  
- In your WordPress plugins panel, activate the PayGol plugin.

- Go to the checkout configuration and proceed to configure the plugin:
  * The text fields contain the text that will be used during the checkout process.
  * The Service ID can be found near your service's name at "My Services", at your PayGol panel.
  * Paste the provided IPN URL into the "Background URL (IPN)" setting at your service's configuration 
    at your PayGol panel (click the pencil icon at "My Services" to edit your service).

== Changelog ==

= 1.2 =
* New release, tested with WordPress 3.7.0 up to 4.7.3, and WooCommerce 2.3.0 up to 2.6.14.
* Updated with new logo.
                                                                
= 1.1 =
* New release, tested with WordPress 3.7.0 up to 4.6.1, and WooCommerce 2.3.0 up to 2.6.7.

= 1.0 =
* Initial release.

== Recommendations and important notes ==

- Test your service by enabling test mode on your service (the Enabled/Testing button at "My Services", at the PayGol panel).
  Be sure to change it back to "Enabled" once you are done testing.

- Some payment methods provided by PayGol (such as credit card payments) will confirm the payment immediately, so the payer will 
  see the payment status as "Completed". However, other payment methods (such as local cash payment services) may take longer 
  to confirm the payment. In these cases the payer will see the status "Processing". After the payment is confirmed
  by the local payments provider, the status will internally be updated to "Completed". Depending on your specific
  needs, you may want to use the "Hold Stock" WooCommerce setting if you need to make sure that stock is available for payments
  that are not notified immediately.