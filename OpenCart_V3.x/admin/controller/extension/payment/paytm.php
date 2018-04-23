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


		// trim all values
		foreach($this->request->post as &$v){
			$v = trim($v);
		}

		$this->load->model('setting/setting');		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paytm', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');


		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_fail'] = $this->language->get('text_fail');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_merchant_id_help'] = $this->language->get('entry_merchant_id_help');

		$data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
		$data['entry_merchant_key_help'] = $this->language->get('entry_merchant_key_help');
		
		$data['entry_website'] = $this->language->get('entry_website');
		$data['entry_website_help'] = $this->language->get('entry_website_help');
		
		$data['entry_industry_type'] = $this->language->get('entry_industry_type');
		$data['entry_industry_type_help'] = $this->language->get('entry_industry_type_help');

		$data['entry_transaction_url'] = $this->language->get('entry_transaction_url');
		$data['entry_transaction_url_help'] = $this->language->get('entry_transaction_url_help');

		$data['entry_transaction_status_url'] = $this->language->get('entry_transaction_status_url');
		$data['entry_transaction_status_url_help'] = $this->language->get('entry_transaction_status_url_help');

		$data['entry_callback_url_status'] = $this->language->get('entry_callback_url_status');
		$data['entry_callback_url_status_help'] = $this->language->get('entry_callback_url_status_help');

		$data['entry_callback_url'] = $this->language->get('entry_callback_url');
		$data['entry_callback_url_help'] = $this->language->get('entry_callback_url_help');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['entry_multi_currency_support'] = $this->language->get('entry_multi_currency_support');
		
		$data['entry_multi_currency_support_disabled'] = $this->language->get('entry_multi_currency_support_disabled');
		$data['entry_multi_currency_support_disabled_help'] = $this->language->get('entry_multi_currency_support_disabled_help');
		
		$data['entry_multi_currency_support_conversion'] = $this->language->get('entry_multi_currency_support_conversion');
		$data['entry_multi_currency_support_conversion_help'] = $this->language->get('entry_multi_currency_support_conversion_help');
		
		$data['entry_multi_currency_support_no_conversion'] = $this->language->get('entry_multi_currency_support_no_conversion');
		$data['entry_multi_currency_support_no_conversion_help'] = $this->language->get('entry_multi_currency_support_no_conversion_help');

		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
		// echo "<PRE>";print_r($this->error);exit;
		
		if (isset($this->error['callback_url'])) {
			$data['error_callback_url'] = $this->error['callback_url'];
		} else {
			$data['error_callback_url'] = '';
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


		if (isset($this->request->post['payment_paytm_order_status_id'])) {
			$data['payment_paytm_order_status_id'] = $this->request->post['payment_paytm_order_status_id'];
		} else {
			$data['payment_paytm_order_status_id'] = $this->config->get('payment_paytm_order_status_id');
		}

		if (isset($this->request->post['payment_paytm_status'])) {
			$data['payment_paytm_status'] = $this->request->post['payment_paytm_status'];
		} else {
			$data['payment_paytm_status'] = $this->config->get('payment_paytm_status');
		}

		if (isset($this->request->post['payment_paytm_multi_currency_support'])) {
			$data['payment_paytm_multi_currency_support'] = $this->request->post['payment_paytm_multi_currency_support'];
		} else if($this->config->get('payment_paytm_multi_currency_support') !== null){
			$data['payment_paytm_multi_currency_support'] = $this->config->get('payment_paytm_multi_currency_support');
		} else {
			$data['payment_paytm_multi_currency_support'] = 1;
		}

		if (isset($this->request->post['payment_paytm_sort_order'])) {
			$data['payment_paytm_sort_order'] = $this->request->post['payment_paytm_sort_order'];
		} else {
			$data['payment_paytm_sort_order'] = (int)$this->config->get('payment_paytm_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paytm', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
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