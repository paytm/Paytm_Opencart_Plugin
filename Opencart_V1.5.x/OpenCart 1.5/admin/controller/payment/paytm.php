<?php
class ControllerPaymentpaytm extends Controller {
	private $error = array();
	//function executed at load of page
	public function index() {
		require_once(DIR_SYSTEM . 'encdec_paytm.php');
		require_once(DIR_SYSTEM . 'paytm_constants.php');
		$this->language->load('payment/paytm');

		$this->document->setTitle($this->language->get('heading_title'));
		$arr = array();	
		
		foreach($this->request->post as $key => $value)
		{
			if($key == 'paytm_key')
			{
				 $arr[$key] = encrypt_e($value, $const1);
				continue;
			}
			$arr[$key] = $value;
		}
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paytmexample', $arr);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_successful'] = $this->language->get('text_successful');
		$this->data['text_fail'] = $this->language->get('text_fail');
		$this->data['text_env_production'] = $this->language->get('text_env_production');
		$this->data['text_env_test'] = $this->language->get('text_env_test');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_merchantkey'] = $this->language->get('entry_merchantkey');
		$this->data['entry_website'] = $this->language->get('entry_website');
		$this->data['entry_industry'] = $this->language->get('entry_industry');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['callbackurl_status'] = $this->language->get('callbackurl_status');
		$this->data['entry_checkstatus'] = $this->language->get('entry_checkstatus');
		// $this->data['entry_environment'] = $this->language->get('entry_environment');
		$this->data['entry_transaction_url'] = $this->language->get('entry_transaction_url');
		$this->data['entry_transaction_url_status'] = $this->language->get('entry_transaction_url_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}
		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}
		if (isset($this->error['website'])) {
			$this->data['error_website'] = $this->error['website'];
		} else {
			$this->data['error_website'] = '';
		}
		
		if (isset($this->error['industry'])) {
			$this->data['error_industry'] = $this->error['industry'];
		} else {
			$this->data['error_industry'] = '';
		}
		
		if (isset($this->request->post['paytm_order_status_id'])) {
			$this->data['paytm_order_status_id'] = $this->request->post['paytm_order_status_id'];
		} else {
			$this->data['paytm_order_status_id'] = $this->config->get('paytm_order_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

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

		if (isset($this->request->post['paytm_merchant'])) {
			$this->data['paytm_merchant'] = $this->request->post['paytm_merchant'];
		} else {
			$this->data['paytm_merchant'] = $this->config->get('paytm_merchant');
		}
		
		if (isset($this->request->post['paytm_website'])) {
			$this->data['paytm_website'] = $this->request->post['paytm_website'];
		} else {
			$this->data['paytm_website'] = $this->config->get('paytm_website');
		}
		
		if (isset($this->request->post['paytm_industry'])) {
			$this->data['paytm_industry'] = $this->request->post['paytm_industry'];
		} else {
			$this->data['paytm_industry'] = $this->config->get('paytm_industry');
		}
		
		if (isset($this->request->post['paytm_key'])) {
		
			$this->data['paytm_key'] = $this->request->post['paytm_key'];
		} else {
			$this->data['paytm_key'] = "";
			if ($this->config->get('paytm_key') != "") {
				$this->data['paytm_key'] = htmlspecialchars_decode(decrypt_e($this->config->get('paytm_key'),$const1),ENT_NOQUOTES);
			}
		}


		if (isset($this->request->post['paytm_status'])) {
			$this->data['paytm_status'] = $this->request->post['paytm_status'];
		} else {
			$this->data['paytm_status'] = $this->config->get('paytm_status');
		}
		if (isset($this->request->post['paytm_callbackurl'])) {
			$this->data['paytm_callbackurl'] = $this->request->post['paytm_callbackurl'];
		} else {
			$this->data['paytm_callbackurl'] = $this->config->get('paytm_callbackurl');
		}
		
		if (isset($this->request->post['paytm_checkstatus'])) {
			$this->data['paytm_checkstatus'] = $this->request->post['paytm_checkstatus'];
		} else {
			$this->data['paytm_checkstatus'] = $this->config->get('paytm_checkstatus');
		}

		/*if (isset($this->request->post['paytm_environment'])) {
			$this->data['paytm_environment'] = $this->request->post['paytm_environment'];
		} else {
			$this->data['paytm_environment'] = $this->config->get('paytm_environment');
		}*/

		if (isset($this->request->post['payment_paytm_transaction_url'])) {
			$this->data['payment_paytm_transaction_url'] = $this->request->post['payment_paytm_transaction_url'];
		} else {
			$this->data['payment_paytm_transaction_url'] = $this->config->get('payment_paytm_transaction_url');
		}

		if (isset($this->request->post['payment_paytm_transaction_status_url'])) {
			$this->data['payment_paytm_transaction_status_url'] = $this->request->post['payment_paytm_transaction_status_url'];
		} else {
			$this->data['payment_paytm_transaction_status_url'] = $this->config->get('payment_paytm_transaction_status_url');
		}

		$this->template = 'payment/paytm.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		
	}
	//validate function to ensure required fields are filled before proceeding
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paytm')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paytm_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		if (!$this->request->post['paytm_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
		if (!$this->request->post['paytm_website']) {
			$this->error['website'] = $this->language->get('error_website');
		}
		if (!$this->request->post['paytm_industry']) {
			$this->error['industry'] = $this->language->get('error_industry');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>