<?php
namespace Opencart\Catalog\Model\Extension\PaytmPaymentGateway\Payment;
class Paytm extends \Opencart\System\Engine\Model {
	public function getMethods(array $address): array {
		$this->load->language('extension/paytm_payment_gateway/payment/paytm');
			$option_data['paytm'] = [
				'code' => 'paytm.paytm',
				'name' => $this->language->get('text_card_use')
			];		
			$method_data = [
				'code'       => 'paytm',
				'name'       => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_paytm_sort_order')
			];
		return $method_data;
	}

	/*public function getPaytm(int $customer_id, int $paytm_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytm` WHERE `customer_id` = '" . (int)$customer_id . "' AND `paytm_id` = '" . (int)$paytm_id . "'");

		return $query->row;
	}

	public function getPaytms(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytm` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function addPaytm(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paytm` SET `customer_id` = '" . (int)$customer_id . "', `card_name` = '" . $this->db->escape($data['card_name']) . "', `card_number` = '" . $this->db->escape($data['card_number']) . "', `card_expire_month` = '" . $this->db->escape($data['card_expire_month']) . "', `card_expire_year` = '" . $this->db->escape($data['card_expire_year']) . "', `card_cvv` = '" . $this->db->escape($data['card_cvv']) . "', `date_added` = NOW()");
	}

	public function deletePaytm(int $customer_id, int $paytm_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "paytm` WHERE `customer_id` = '" . (int)$customer_id . "' AND `paytm_id` = '" . (int)$paytm_id . "'");
	}*/

	/*public function charge(int $customer_id, int $order_id, float $amount, int $paytm_id = 0): string {
		//$this->db->query("INSERT INTO `" . DB_PREFIX . "paytm` SET `customer_id` = '" . (int)$customer_id . "', `card_name` = '" . $this->db->escape($data['card_name']) . "', `card_number` = '" . $this->db->escape($data['card_number']) . "', `card_expire_month` = '" . $this->db->escape($data['card_expire_month']) . "', `card_expire_year` = '" . $this->db->escape($data['card_expire_year']) . "', `card_cvv` = '" . $this->db->escape($data['card_cvv']) . "', `date_added` = NOW()");

		return $this->config->get('payment_paytm_response');
	}*/

	public function saveTxnResponse($order_id, $data  = array()){
		if(empty($data['STATUS'])) return false;
		$status 			= (!empty($data['STATUS']) && $data['STATUS'] =='TXN_SUCCESS') ? 1 : 0;
		$paytm_order_id 	= (!empty($data['ORDERID'])? $data['ORDERID']:'');
		$transaction_id 	= (!empty($data['TXNID'])? $data['TXNID']:'');

		$sql =  "INSERT INTO " . DB_PREFIX . "paytm_report SET order_id = '" . $order_id . "', paytm_order_id = '" . $paytm_order_id . "', transaction_id = '" . $this->db->escape($transaction_id) . "', status = '" . (int)$status . "', response = '" . $this->db->escape(json_encode($data)) . "', date_added = NOW()";
		$this->db->query($sql);
		return $this->db->getLastId();
	}	
}
