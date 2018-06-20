<?php 
class ModelExtensionPaymentPaytm extends Model {
	public function getMethod($address, $total) {

		$method_data = array();

		$this->language->load('extension/payment/paytm');
			
		$method_data = array( 
			'code'			=> 'paytm',
			'title'			=> $this->language->get('text_title'),
			'sort_order'	=> $this->config->get('payment_paytm_sort_order'),
			'terms'			=> ''
		);

		return $method_data;
	}
}
?>