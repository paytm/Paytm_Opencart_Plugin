<?php
class ControllerPaymentPaytm extends Controller {
	
	private $error 					= array();
	private $save_paytm_response 	= true; /* save paytm response in db */
	private $max_retry_count 		= 3; /* number of retries untill cURL gets success */

	/**
	* create `paytm_order_data` table and install this module.
	*/
	public function install() {
		$this->load->model('payment/paytm');
		$this->model_payment_paytm->install();
	}
	/**
	* drop `paytm_order_data` table and uninstall this module.
	*/
	public function uninstall() {
		$this->load->model('payment/paytm');
		$this->model_payment_paytm->uninstall();
	}
	/**
	* get Default callback url
	*/
	private function getCallbackUrl(){
		$callback_url = "index.php?route=payment/paytm/callback";
		return (!empty($_SERVER['HTTPS']))? HTTPS_CATALOG . $callback_url : HTTP_CATALOG . $callback_url;
	}
		
	public function index() {
		require_once(DIR_SYSTEM . 'paytm/encdec_paytm.php');

		$this->language->load('payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paytm', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if(!$this->validateCurl($this->request->post['paytm_transaction_status_url'])){
				$this->session->data['warning'] = $this->language->get('error_curl_warning');
				$this->redirect($this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL'));
			}

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] 				= $this->language->get('heading_title');
		$this->data['text_enabled'] 				= $this->language->get('text_enabled');
		$this->data['text_disabled'] 				= $this->language->get('text_disabled');
		$this->data['text_all_zones'] 			= $this->language->get('text_all_zones');
	
		
		$this->data['text_opencart_version'] 	= $this->language->get('text_opencart_version');
		$this->data['text_curl_version'] 		= $this->language->get('text_curl_version');
		$this->data['text_php_version'] 			= $this->language->get('text_php_version');
		$this->data['text_last_updated'] 		= $this->language->get('text_last_updated');
		$this->data['text_curl_disabled'] 		= $this->language->get('text_curl_disabled');
		
		$this->data['entry_merchant_id'] 		= $this->language->get('entry_merchant_id');
		$this->data['entry_merchant_key'] 		= $this->language->get('entry_merchant_key');
		$this->data['entry_website'] 				= $this->language->get('entry_website');
		$this->data['entry_industry_type'] 		= $this->language->get('entry_industry_type');
		$this->data['entry_transaction_url'] 	= $this->language->get('entry_transaction_url');
		$this->data['entry_transaction_status_url'] = $this->language->get('entry_transaction_status_url');
		$this->data['entry_callback_url_status'] = $this->language->get('entry_callback_url_status');
		$this->data['entry_callback_url'] 		= $this->language->get('entry_callback_url');
		$this->data['entry_order_success_status'] = $this->language->get('entry_order_success_status');
		$this->data['entry_order_failed_status'] = $this->language->get('entry_order_failed_status');
		
		$this->data['entry_total'] 				= $this->language->get('entry_total');	
		$this->data['entry_geo_zone'] 			= $this->language->get('entry_geo_zone');
		$this->data['entry_status'] 				= $this->language->get('entry_status');
		$this->data['entry_sort_order'] 			= $this->language->get('entry_sort_order');		

		$this->data['button_save'] 				= $this->language->get('button_save');
		$this->data['button_cancel'] 				= $this->language->get('button_cancel');
		
		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['merchant_id'])) {
			$this->data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$this->data['error_merchant_id'] = '';
		}
		if (isset($this->error['merchant_key'])) {
			$this->data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$this->data['error_merchant_key'] = '';
		}
		if (isset($this->error['website'])) {
			$this->data['error_website'] = $this->error['website'];
		} else {
			$this->data['error_website'] = '';
		}
		
		if (isset($this->error['industry_type'])) {
			$this->data['error_industry_type'] = $this->error['industry_type'];
		} else {
			$this->data['error_industry_type'] = '';
		}
		
		if (isset($this->error['transaction_url'])) {
			$this->data['error_transaction_url'] = $this->error['transaction_url'];
		} else {
			$this->data['error_transaction_url'] = '';
		}
		
		if (isset($this->error['transaction_status_url'])) {
			$this->data['error_transaction_status_url'] = $this->error['transaction_status_url'];
		} else {
			$this->data['error_transaction_status_url'] = '';
		}
		
		if (isset($this->error['callback_url_status'])) {
			$this->data['error_callback_url_status'] = $this->error['callback_url_status'];
		} else {
			$this->data['error_callback_url_status'] = '';
		}
		
		if (isset($this->error['callback_url'])) {
			$this->data['error_callback_url'] = $this->error['callback_url'];
		} else {
			$this->data['error_callback_url'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/paytm', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['paytm_merchant_id'])) {
			$this->data['paytm_merchant_id'] = $this->request->post['paytm_merchant_id'];
		} else {
			$this->data['paytm_merchant_id'] = $this->config->get('paytm_merchant_id');
		}

		if (isset($this->request->post['paytm_merchant_key'])) {
			$this->data['paytm_merchant_key'] = $this->request->post['paytm_merchant_key'];
		} else {
			$this->data['paytm_merchant_key'] = $this->config->get('paytm_merchant_key');
		}
		
		if (isset($this->request->post['paytm_website'])) {
			$this->data['paytm_website'] = $this->request->post['paytm_website'];
		} else {
			$this->data['paytm_website'] = $this->config->get('paytm_website');
		}
		
		if (isset($this->request->post['paytm_industry_type'])) {
			$this->data['paytm_industry_type'] = $this->request->post['paytm_industry_type'];
		} else {
			$this->data['paytm_industry_type'] = $this->config->get('paytm_industry_type');
		}
		
		if (isset($this->request->post['paytm_transaction_url'])) {
			$this->data['paytm_transaction_url'] = $this->request->post['paytm_transaction_url'];
		} else {
			$this->data['paytm_transaction_url'] = $this->config->get('paytm_transaction_url');
		}

		if (isset($this->request->post['paytm_transaction_status_url'])) {
			$this->data['paytm_transaction_status_url'] = $this->request->post['paytm_transaction_status_url'];
		} else {
			$this->data['paytm_transaction_status_url'] = $this->config->get('paytm_transaction_status_url');
		}

		if (isset($this->request->post['paytm_callback_url_status'])) {
			$this->data['paytm_callback_url_status'] = $this->request->post['paytm_callback_url_status'];
		} else if($this->config->get('paytm_callback_url_status')){
			$this->data['paytm_callback_url_status'] = $this->config->get('paytm_callback_url_status');
		} else {
			$this->data['paytm_callback_url_status'] = "0";
		}

		$this->data["default_callback_url"] = $this->getCallbackUrl();

		if (isset($this->request->post['paytm_callback_url_status']) && $this->request->post['paytm_callback_url_status'] == 1) {
			$this->data['paytm_callback_url'] = $this->request->post['paytm_callback_url'];
		} else if($this->config->get('paytm_callback_url')) {
			$this->data['paytm_callback_url'] = $this->config->get('paytm_callback_url');
		} else {
			$this->data['paytm_callback_url'] = $this->data["default_callback_url"];
		}
		
		if (isset($this->request->post['paytm_order_success_status_id'])) {
			$this->data['paytm_order_success_status_id'] = $this->request->post['paytm_order_success_status_id'];
		} else {
			$this->data['paytm_order_success_status_id'] = $this->config->get('paytm_order_success_status_id');
		}

		if (isset($this->request->post['paytm_order_failed_status_id'])) {
			$this->data['paytm_order_failed_status_id'] = $this->request->post['paytm_order_failed_status_id'];
		} else {
			$this->data['paytm_order_failed_status_id'] = $this->config->get('paytm_order_failed_status_id');
		}
		
		if (isset($this->request->post['paytm_order_status_id'])) {
			$this->data['paytm_order_status_id'] = $this->request->post['paytm_order_status_id'];
		} else {
			$this->data['paytm_order_status_id'] = $this->config->get('paytm_order_status_id');
		}
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paytm_total'])) {
			$this->data['paytm_total'] = $this->request->post['paytm_total'];
		} else {
			$this->data['paytm_total'] = $this->config->get('paytm_total'); 
		}
		
		if (isset($this->request->post['paytm_geo_zone_id'])) {
			$this->data['paytm_geo_zone_id'] = $this->request->post['paytm_geo_zone_id'];
		} else {
			$this->data['paytm_geo_zone_id'] = $this->config->get('paytm_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paytm_status'])) {
			$this->data['paytm_status'] = $this->request->post['paytm_status'];
		} else {
			$this->data['paytm_status'] = $this->config->get('paytm_status');
		}

		if (isset($this->request->post['paytm_sort_order'])) {
			$this->data['paytm_sort_order'] = $this->request->post['paytm_sort_order'];
		} else {
			$this->data['paytm_sort_order'] = $this->config->get('paytm_sort_order');
		}

		$this->data['last_updated'] = "";
		$path = DIR_SYSTEM . "/paytm/paytm_version.txt";
		if(file_exists($path)){
			$handle = fopen($path, "r");
			if($handle !== false){
				$date = fread($handle, 10); // i.e. DD-MM-YYYY or 25-04-2018
				$this->data['last_updated'] = date("d F Y", strtotime($date));
			}
		}
		
		// Check cUrl is enabled or not
		if(function_exists('curl_version')){
			$this->data['curl_version'] = (!empty($curl_ver_array = curl_version()) && $curl_ver_array['version']) ? $curl_ver_array['version']:'';
		}else{
			$this->data['curl_version'] = '';
		}		

		$this->data['opencart_version'] = VERSION;
		$this->data['php_version'] = PHP_VERSION;

		$this->template = 'payment/paytm.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	
	}
	
	/**
	* get paytm response by order id from `paytm_order_data`
	*/
	public function getorder(){
		if(!empty($this->request->get['order_id'])){
			$this->load->model('payment/paytm');
			$results = $this->model_payment_paytm->getPaytmOrderData($this->request->get['order_id']);
			$this->response->setOutput(json_encode($results));
		}
	}
	
	/**
	* check and test cURL is working or able to communicate properly with paytm
	*/
	private function validateCurl($paytm_transaction_status_url = ''){		
		if(!empty($paytm_transaction_status_url) && function_exists("curl_init")){
			$ch 	= curl_init(trim($paytm_transaction_status_url));
			$res 	= curl_exec($ch);
			curl_close($ch);
			return $res !== false;
		}
		return false;
	}
	
	//validate function to ensure required fields are filled before proceeding
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paytm_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}
		if (!$this->request->post['paytm_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}
		if (!$this->request->post['paytm_website']) {
			$this->error['website'] = $this->language->get('error_website');
		}
		if (!$this->request->post['paytm_industry_type']) {
			$this->error['industry_type'] = $this->language->get('error_industry_type');
		}
		if (!$this->request->post['paytm_transaction_url']) {
			$this->error['transaction_url'] = $this->language->get('error_transaction_url');
		}
		if (!$this->request->post['paytm_transaction_status_url']) {
			$this->error['transaction_status_url'] = $this->language->get('error_transaction_status_url');
		}
		if (!$this->request->post['paytm_callback_url']) {
			$this->error['callback_url'] = $this->language->get('error_callback_url');
		} else {
			$url_parts = parse_url($this->request->post['paytm_callback_url']);
			if(!isset($url_parts["scheme"]) || (strtolower($url_parts["scheme"]) != "http" && strtolower($url_parts["scheme"]) != "https") || !isset($url_parts["host"]) || $url_parts["host"] == ""){
				$this->error['callback_url'] = $this->language->get('error_valid_callback_url');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>