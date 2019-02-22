<?php
class ControllerExtensionPaymentPaytm extends Controller {

	private $error = array();
	private $save_paytm_response = true; /* save paytm response in db */
	private $max_retry_count = 3; /* number of retries untill cURL gets success */

	/**
	* get Default callback url
	*/
	private function getCallbackUrl(){
		$callback_url = "index.php?route=extension/payment/paytm/callback";
		return $_SERVER['HTTPS']? HTTPS_CATALOG . $callback_url : HTTP_CATALOG . $callback_url;
	}

	public function index() {
		require_once(DIR_SYSTEM . 'encdec_paytm.php');
		
		$this->language->load('extension/payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paytm', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->install();

			if(!$this->validateCurl($this->request->post['payment_paytm_transaction_status_url'])){
				$this->session->data['warning'] = $this->language->get('error_curl_warning');
				$this->response->redirect($this->url->link('extension/payment/paytm', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		if (isset($this->error['merchant_id'])) {
			$data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}

		if (isset($this->error['merchant_key'])) {
			$data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$data['error_merchant_key'] = '';
		}

		if (isset($this->error['website'])) {
			$data['error_website'] = $this->error['website'];
		} else {
			$data['error_website'] = '';
		}
		
		if (isset($this->error['industry_type'])) {
			$data['error_industry_type'] = $this->error['industry_type'];
		} else {
			$data['error_industry_type'] = '';
		}
		
		if (isset($this->error['transaction_url'])) {
			$data['error_transaction_url'] = $this->error['transaction_url'];
		} else {
			$data['error_transaction_url'] = '';
		}
		
		if (isset($this->error['transaction_status_url'])) {
			$data['error_transaction_status_url'] = $this->error['transaction_status_url'];
		} else {
			$data['error_transaction_status_url'] = '';
		}
		
		if (isset($this->error['callback_url_status'])) {
			$data['error_callback_url_status'] = $this->error['callback_url_status'];
		} else {
			$data['error_callback_url_status'] = '';
		}
		
		if (isset($this->error['callback_url'])) {
			$data['error_callback_url'] = $this->error['callback_url'];
		} else {
			$data['error_callback_url'] = '';
		}

		if (isset($this->error['promo_codes'])) {
			$data['error_promo_codes'] = $this->error['promo_codes'];
		} else {
			$data['error_promo_codes'] = array();
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_home'),
			'href'	  => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
		'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_extension'),
			'href'	  => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'),
		'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('text_payments'),
			'href'	  => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'),
		'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'	  => $this->language->get('heading_title'),
			'href'	  => $this->url->link('extension/payment/paytm', 'user_token=' . $this->session->data['user_token'], 'SSL'),
		'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/payment/paytm', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL');


		if (isset($this->request->post['payment_paytm_merchant_id'])) {
			$data['payment_paytm_merchant_id'] = $this->request->post['payment_paytm_merchant_id'];
		} else {
			$data['payment_paytm_merchant_id'] = $this->config->get('payment_paytm_merchant_id');
		}

		if (isset($this->request->post['payment_paytm_merchant_key'])) {
			$data['payment_paytm_merchant_key'] = $this->request->post['payment_paytm_merchant_key'];
		} else {
			$data['payment_paytm_merchant_key'] = $this->config->get('payment_paytm_merchant_key');
		}
		
		if (isset($this->request->post['payment_paytm_website'])) {
			$data['payment_paytm_website'] = $this->request->post['payment_paytm_website'];
		} else {
			$data['payment_paytm_website'] = $this->config->get('payment_paytm_website');
		}

		if (isset($this->request->post['payment_paytm_industry_type'])) {
			$data['payment_paytm_industry_type'] = $this->request->post['payment_paytm_industry_type'];
		} else {
			$data['payment_paytm_industry_type'] = $this->config->get('payment_paytm_industry_type');
		}
	
		if (isset($this->request->post['payment_paytm_transaction_url'])) {
			$data['payment_paytm_transaction_url'] = $this->request->post['payment_paytm_transaction_url'];
		} else {
			$data['payment_paytm_transaction_url'] = $this->config->get('payment_paytm_transaction_url');
		}
	
		if (isset($this->request->post['payment_paytm_transaction_status_url'])) {
			$data['payment_paytm_transaction_status_url'] = $this->request->post['payment_paytm_transaction_status_url'];
		} else {
			$data['payment_paytm_transaction_status_url'] = $this->config->get('payment_paytm_transaction_status_url');
		}
		
		if (isset($this->request->post['payment_paytm_callback_url_status'])) {
			$data['payment_paytm_callback_url_status'] = $this->request->post['payment_paytm_callback_url_status'];
		} else if($this->config->get('payment_paytm_callback_url_status')){
			$data['payment_paytm_callback_url_status'] = $this->config->get('payment_paytm_callback_url_status');
		} else {
			$data['payment_paytm_callback_url_status'] = "0";
		}

		$data["default_callback_url"] = $this->getCallbackUrl();

		if (isset($this->request->post['payment_paytm_callback_url_status']) && $this->request->post['payment_paytm_callback_url_status'] == 1) {
			$data['payment_paytm_callback_url'] = $this->request->post['payment_paytm_callback_url'];
		} else if($this->config->get('payment_paytm_callback_url')) {
			$data['payment_paytm_callback_url'] = $this->config->get('payment_paytm_callback_url');
		} else {
			$data['payment_paytm_callback_url'] = $data["default_callback_url"];
		}


		if (isset($this->request->post['payment_paytm_order_success_status_id'])) {
			$data['payment_paytm_order_success_status_id'] = $this->request->post['payment_paytm_order_success_status_id'];
		} else {
			$data['payment_paytm_order_success_status_id'] = $this->config->get('payment_paytm_order_success_status_id');
		}

		if (isset($this->request->post['payment_paytm_order_failed_status_id'])) {
			$data['payment_paytm_order_failed_status_id'] = $this->request->post['payment_paytm_order_failed_status_id'];
		} else {
			$data['payment_paytm_order_failed_status_id'] = $this->config->get('payment_paytm_order_failed_status_id');
		}

		if (isset($this->request->post['payment_paytm_status'])) {
			$data['payment_paytm_status'] = $this->request->post['payment_paytm_status'];
		} else {
			$data['payment_paytm_status'] = $this->config->get('payment_paytm_status');
		}

		if (isset($this->request->post['payment_paytm_sort_order'])) {
			$data['payment_paytm_sort_order'] = $this->request->post['payment_paytm_sort_order'];
		} else {
			$data['payment_paytm_sort_order'] = (int)$this->config->get('payment_paytm_sort_order');
		}
		

		if (isset($this->request->post['payment_paytm_promo_code_status'])) {
			$data['payment_paytm_promo_code_status'] = $this->request->post['payment_paytm_promo_code_status'];
		} else if($this->config->get('payment_paytm_promo_code_status') != null) {
			$data['payment_paytm_promo_code_status'] = $this->config->get('payment_paytm_promo_code_status');
		} else {
			// keep this disable at fresh installation
			$data['payment_paytm_promo_code_status'] = "0";
		}		

		if (isset($this->request->post['payment_paytm_promo_code_validation'])) {
			$data['payment_paytm_promo_code_validation'] = $this->request->post['payment_paytm_promo_code_validation'];
		} else if($this->config->get('payment_paytm_promo_code_validation') != null) {
			$data['payment_paytm_promo_code_validation'] = $this->config->get('payment_paytm_promo_code_validation');
		} else {
			// keep this enable at fresh installation
			$data['payment_paytm_promo_code_validation'] = "1";
		}

		if (isset($this->request->post['payment_paytm_promo_codes'])) {
			$data['payment_paytm_promo_codes'] = $this->request->post['payment_paytm_promo_codes'];
		} else if($this->config->get('payment_paytm_promo_codes')) {
			$data['payment_paytm_promo_codes'] = $this->config->get('payment_paytm_promo_codes');
		} else {
			$data['payment_paytm_promo_codes'] = array();
		}

		$data['last_updated'] = "";
		$path = DIR_SYSTEM . "/paytm_version.txt";
		if(file_exists($path)){
			$handle = fopen($path, "r");
			if($handle !== false){
				$date = fread($handle, 10); // i.e. DD-MM-YYYY or 25-04-2018
				$data['last_updated'] = date("d F Y", strtotime($date));
			}
		}
		// Check cUrl is enabled or not
		if(function_exists('curl_version')){
			$data['curl_version'] = (!empty($curl_ver_array = curl_version()) && $curl_ver_array['version']) ? $curl_ver_array['version']:'';
		}else{
			$data['curl_version'] = '';
		}		

		$data['opencart_version'] = VERSION;
		$data['php_version'] = PHP_VERSION;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paytm', $data));
	}
	/**
	* install `paytm_order_data` table and save paytm response with it.
	*/
	private function install(){
		$this->db->query("
	      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paytm_order_data` (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,
			`paytm_order_id` VARCHAR(255) NOT NULL,
			`transaction_id` VARCHAR(255) NOT NULL,
			`status` ENUM('0', '1')  DEFAULT '0' NOT NULL,
	        `paytm_response` TEXT,
	        `date_added` DATETIME NOT NULL,
	        `date_modified` DATETIME NOT NULL,
	        PRIMARY KEY (`id`)
	      ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	/**
	* create tab with paytm response at Order Detail page
	*/
	public function order() {
		$this->language->load('extension/payment/paytm');
		if(!empty($order_id = $this->request->get['order_id'])){

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytm_order_data` WHERE order_id = '" . (int)$order_id . "' ORDER BY id DESC LIMIT 1");
			$data = array();
			$data['user_token'] = $this->session->data['user_token'];
			if($query->row){
				$data['transaction_id']			= $query->row['transaction_id'];
				$data['paytm_order_id']			= $query->row['paytm_order_id'];
				$data['order_data_id']			= $query->row['id'];
				$data['paytm_response'] 		= json_decode($query->row['paytm_response'],true);
				return $this->load->view('extension/payment/paytm_order', $data);
			}
		}
	}

	/**
	* ajax - fetch and save transaction status in db
	*/
	public function savetxnstatus() {
		require_once(DIR_SYSTEM . 'encdec_paytm.php');

		$this->language->load('extension/payment/paytm');
		$json = array("success" => false, "response" => '', 'message' => $this->language->get('text_response_error'));

		if(!empty($this->request->post['paytm_order_id'])){
				$reqParams = array(
					"MID" 		=> $this->config->get('payment_paytm_merchant_id'),
					"ORDERID" 	=> $this->request->post['paytm_order_id']
				);

				$reqParams['CHECKSUMHASH'] = PaytmPayment::getChecksumFromArray($reqParams, $this->config->get("payment_paytm_merchant_key"));
					
				$retry = 1;
				do{
					$resParams = PaytmPayment::executecUrl($this->config->get('payment_paytm_transaction_status_url'), $reqParams);
					$retry++;
				} while(!$resParams && $retry < $this->max_retry_count);

				if($this->save_paytm_response){
					$update_response	=	$this->saveTxnResponse($resParams, $this->request->post['order_data_id']); 
					if($update_response){

						$message = $this->language->get('text_response_success');
						if($resParams['STATUS']!='PENDING'){
							$message .= sprintf($this->language->get('text_response_status_success'), $resParams['STATUS']);
						}						
						$json = array("success" => true, "response" => $resParams, 'message' => $message);
					}
				}
		}		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function saveTxnResponse($data  = array(), $id = false){

		$status 				= (!empty($data['STATUS']) && $data['STATUS'] =='TXN_SUCCESS') ? 1 : 0;
		$paytm_order_id 	= (!empty($data['ORDERID'])? $data['ORDERID']:'');
		$transaction_id 	= (!empty($data['TXNID'])? $data['TXNID']:'');
		if($paytm_order_id){
			$sql =  "UPDATE " . DB_PREFIX . "paytm_order_data SET transaction_id = '" . $this->db->escape($transaction_id) . "', status = '" . (int)$status . "', paytm_response = '" . $this->db->escape(json_encode($data)) . "', date_modified = NOW() WHERE paytm_order_id = '" . $paytm_order_id . "' AND id = '" . (int)$id . "'";
			$this->db->query($sql);
			return $id;
		}
		
	}

	/**
	* check and test cURL is working or able to communicate properly with paytm
	*/
	private function validateCurl ($payment_paytm_transaction_status_url = ''){
		if(!empty($payment_paytm_transaction_status_url) && function_exists("curl_init")){
			
				$ch = curl_init(trim($payment_paytm_transaction_status_url));
				$res = curl_exec($ch);
				if (curl_errno($ch)) {
					$responseBool = false;

				} else {
					$responseBool = true;
				}
				curl_close($ch);
				return $responseBool;
			
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// trim all values except for Promo Codes
		foreach($this->request->post as $key=>&$val){
			if($key == "payment_paytm_promo_codes"){
				foreach($val as $k=>&$code) {
					$v["code"] = trim($code["code"]);
					if($code["code"] == ""){
						$this->error['promo_codes'][$k]['promo_code'] = $this->language->get('error_promo_code');
					}

					if($code["start_date"] == ""){
						$this->error['promo_codes'][$k]['start_date'] = $this->language->get('error_start_date');
					}

					if($code["end_date"] == ""){
						$this->error['promo_codes'][$k]['end_date'] = $this->language->get('error_end_date');
					} else if(strtotime($code["end_date"]) < strtotime($code["start_date"])){
						$this->error['promo_codes'][$k]['end_date'] = $this->language->get('error_invalid_end_date');
					}
	
				}
			} else {
				$val = trim($val);
			}
		}

		if (!$this->request->post['payment_paytm_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}
		if (!$this->request->post['payment_paytm_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}
		if (!$this->request->post['payment_paytm_website']) {
			$this->error['website'] = $this->language->get('error_website');
		}
		if (!$this->request->post['payment_paytm_industry_type']) {
			$this->error['industry_type'] = $this->language->get('error_industry_type');
		}
		if (!$this->request->post['payment_paytm_transaction_url']) {
			$this->error['transaction_url'] = $this->language->get('error_transaction_url');
		}
		if (!$this->request->post['payment_paytm_transaction_status_url']) {
			$this->error['transaction_status_url'] = $this->language->get('error_transaction_status_url');
		}
		if (!$this->request->post['payment_paytm_callback_url']) {
			$this->error['callback_url'] = $this->language->get('error_callback_url');
		} else {
			$url_parts = parse_url($this->request->post['payment_paytm_callback_url']);
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