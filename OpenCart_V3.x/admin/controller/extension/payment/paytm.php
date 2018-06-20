<?php
class ControllerExtensionPaymentPaytm extends Controller {

	private $error = array();

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

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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

		$last_updated = "";
		$path = DIR_SYSTEM . "/paytm_version.txt";
		if(file_exists($path)){
			$handle = fopen($path, "r");
			if($handle !== false){
				$date = fread($handle, 10); // i.e. DD-MM-YYYY or 25-04-2018
				$last_updated = '<p>Last Updated: '. date("d F Y", strtotime($date)) .'</p>';
			}
		}

		$data['footer_text'] = '<div class="text-center">'.$last_updated.'<p>'.$this->language->get('text_opencart_version').': '.VERSION.'</p></div>';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paytm', $data));
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