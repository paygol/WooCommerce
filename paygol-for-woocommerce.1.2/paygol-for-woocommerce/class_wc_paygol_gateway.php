<?php
class WC_PayGol_Gateway extends WC_Payment_Gateway {
public function __construct() {
    $this->id			      	= 'paygol';
	$this->icon 		    	= apply_filters('woocommerce_paygol_icon',plugins_url() . "/" . plugin_basename(dirname(__FILE__)) . '/images/paygol.png');
	$this->has_fields 		= false; // 
	$this->method_title   = __( 'PayGol', "paygol_wc" );
    //$this->method_description
	$this->init_form_fields();
	$this->init_settings();
    $this->title 		    	= apply_filters( 'woopaygol_title', __( 'PayGol','paygol_wc') );
	$this->description    = apply_filters( 'woopaygol_description', __( 'PayGol offers you worldwide coverage with a complete payment solution.','paygol_wc' ) );
    $this->serviceID      = $this->get_option('serviceID') ;
    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    add_action( 'woocommerce_receipt_paygol', array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( &$this, 'paygol_ipn_response') );
    add_action ('woocommerce_thankyou',array($this,'order_received'),1); 
	}
  //////////////////////////////////////////////////////////////////////  
  // 
 	function paygol_ipn_response(){
		global $woocommerce; // VAR GLOB
    $get_filtered = filter_input_array(INPUT_GET);
    $order_id = $get_filtered['custom'];  // receive order ID from paygol           
    $order = new WC_Order( $order_id );
    $service_id = $this->serviceID;  //var id service
    $status = $order->get_status(); //var status  order
    if(!in_array($_SERVER['REMOTE_ADDR'],array('109.70.3.48', '109.70.3.146', '109.70.3.210'))) {
        exit;
    }            
     /// 
     if ($status!="cancelled") // != cancelled
     {
        if( ($get_filtered['frmprice'] == $order->get_total() and $get_filtered['frmcurrency'] == get_woocommerce_currency() ) and $get_filtered['service_id'] == $service_id)
          {                           

          $order->update_status('processing'); ///              
          $order->reduce_order_stock();
          $woocommerce->cart->empty_cart();           
          }
        else
          { 
          $order->update_status('pending'); /// 
          
          } 
     }else{     // == cancelled
            if( ($get_filtered['frmprice'] == $order->get_total() and $get_filtered['frmcurrency'] == get_woocommerce_currency() ) and $get_filtered['service_id'] == $service_id)
            {                           
              $order->update_status('processing'); ///              
              $order->reduce_order_stock();
              $woocommerce->cart->empty_cart();           
            }
          else
            { 
            $order->update_status('pending'); /// 
            //
            //
            } 
          } 
	} 
  //////////////////////////////////////////////////////////////////////                                                                     
  function init_form_fields() {
     // 
     $this->form_fields = array(
    	'enabled' => array(
    		'title' => __( 'Enable/Disable', 'paygol_wc' ),
    		'type' => 'checkbox',
    		'label' => __( 'Enable PayGol payments', 'paygol_wc' ),
    		'default' => 'yes'
    	),
    	'serviceID' => array(
    		'title' => __( 'Service ID', 'paygol_wc' ),
    		'type' => 'text',
    		'description' => __( 'This is the ID of your PayGol service.', 'paygol_wc' ),
    		'default' => __( '', 'paygol_wc' ),
    		'desc_tip'      => true,
    	)
    );
  }
  //////////////////////////////////////////////////////////////////////   
  public function admin_options() {
    // 
		?>
		<h3><?php _e( 'PayGol', 'paygol_wc' ); ?></h3> 	
		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
      <tr valign="top">
        <th scope="row" class="titledesc"><?php _e('Payments notification URL (IPN)','paygol_wc');?></th>
        <td class="forminp"><b><?php _e(add_query_arg( 'wc-api', 'WC_Paygol_Gateway', home_url( '/' )),'paygol_wc'); ?></b><br>
        <span class="description"><?php _e('Paste this address on the "Background URL (IPN)" field at your service\'s configuration, on PayGol\'s website.','paygol_wc'); ?></span>
        </td>
      </tr>			
		</table> 	
		<?php
	 }
 
  //////////////////////////////////////////////////////////////////////   
   function receipt_page($order) {
    echo '<p>'.__( 'Click the PayGol button to proceed with your purchase', "paygol_wc" ).'</p>';
    echo $this->generate_paygol_form($order); 
	}
  //////////////////////////////////////////////////////////////////////////////
 function generate_paygol_form($orderID) {
		// PayGol form 
		global $woocommerce;
    $order = new WC_Order($orderID);
  	$gateway_address   = 'https://www.paygol.com/pay'; 
  	$paygol_args       = $this->prepare_args($order);
    //$paygol_args_array = array();
    foreach ($paygol_args as $key => $value) { 
    $paygol_args_array[] = '<input type="hidden" name="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" />';
		}
     	return '<form action="'.esc_url($gateway_address).'" method="post" name="pg_frm" id="pg_frm" target="_top">
			' . implode( '', $paygol_args_array) . '      
			<button type="submit" style="background-color:transparent" name="pg_button" id="pg_button"><img src="'.(substr(get_locale(),0,2) =='es'? plugins_url().'/'.plugin_basename(dirname(__FILE__)).'/images/paygol_es_white.png':plugins_url().'/'.plugin_basename(dirname(__FILE__)).'/images/paygol_en_white.png').'" border="0" alt="Make payments PayGol: the easiest way!" title="Make payments PayGol: the easiest way!" /></button>
			</form>';
    }
    
  ////////////////////////////////////////////////////////////////////////////// 
  function prepare_args( $order ) {
    // Prepare PayGol form parameters
		global $woocommerce;
		$orderID = $order->id;  // Assign order number               
    $shopOrderInfo = get_bloginfo('name').' | Order #'.$orderID; // Order information to be shown at the payment screen   // 
    add_query_arg( 'wc-api', 'WC_Paygol_Gateway', home_url( '/' ) ); 		                 
		$args = array (
				'pg_serviceid'	=> $this->serviceID,         // ID PAYGOL
				'pg_currency'		=> get_woocommerce_currency(),  // 
				'pg_name'				=> $shopOrderInfo,    //   
        'pg_custom'     => $orderID,       // 
				'pg_price'			=>  $order->get_total(), // 
    		'pg_return_url'	=> apply_filters( 'paygol_param_urlOK', $this->get_return_url( $order )), // Success URL
				'pg_cancel_url'	=> apply_filters( 'paygol_param_urlKO', $order->get_checkout_payment_url())	// Cancel URL            
		);		
		return $args;		
	} 
  ////////////////////////////////////////////////////////////////////////////// 
  function process_payment( $order_id ) {
      global $woocommerce; 
    	$order = new WC_Order( $order_id );
      return array(
			'result' 	=> 'success',
      'redirect'	=> $order->get_checkout_payment_url( true ));     
    } 
  //////////////////////////////////////////////////////////////////////////////
      function order_received($order_id){          
        $order = new WC_Order( $order_id );
        switch($order->get_status()){                 
          case 'completed':{
               wc_print_notice( __( 'Your order has been completed.', 'paygol_wc' ), 'success' );
               break;
          }             
          case 'cancelled':{
               wc_print_notice( __( 'Your order cannot be completed.', 'paygol_wc' ), 'error' );
               break;         
          } 
         default:{
              $order->update_status('processing');
              wc_print_notice( __( 'Your request is being processed, will be completed once it is confirmed by the local payment provider.', 'paygol_wc' ), 'notice' );          
          
          }              
        }
              
    }
  ////////////////////////////////////////////////////////////////////////////// 
 }  
?>