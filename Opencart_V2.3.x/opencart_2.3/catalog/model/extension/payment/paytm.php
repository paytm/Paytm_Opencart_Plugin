<?php 
class ModelExtensionPaymentPaytm extends Model {
	public function getMethod($address, $total) {

	 $method_data = array();

	 $currencies = array(
		'INR',
	 );

	 // if customer's selected currency is not INR and paytm multi currency support is disabled
	 if (!in_array(strtoupper($this->session->data['currency']), $currencies) 
	 		&& $this->config->get('paytm_multi_currency_support') == "0"){
	 	$method_data = array();
	 } else {
			$this->language->load('extension/payment/paytm');
			
			$method_data = array( 
				'code'			=> 'paytm',
				'title'			=> $this->language->get('text_title'),
				'sort_order'	=> $this->config->get('paytm_sort_order'),
				'terms'			=> ''
			);
	 	}

		return $method_data;
	}
}
?>