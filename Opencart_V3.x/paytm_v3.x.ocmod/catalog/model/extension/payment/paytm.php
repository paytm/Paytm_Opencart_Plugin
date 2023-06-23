<?php 
require_once(DIR_SYSTEM . "/library/paytm/PaytmConstants.php");
class ModelExtensionPaymentPaytm extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/paytm');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_paytm_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_paytm_total') > 0 && $this->config->get('payment_paytm_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_paytm_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$status = (isset($this->session->data['currency']) && $this->session->data['currency'] != 'INR' && PaytmConstants::ONLY_SUPPORTED_INR) ? false : $status;

		$method_data = array();
		$white_logo= PaytmConstants::WHITE_LOGO_URL;
		$colored_logo= PaytmConstants::COLORED_LOGO_URL;
        $invert_logo = $this->config->get('payment_paytm_logo')? $white_logo : $colored_logo;

		if ($status) {
			$method_data = array(
				'code'       => 'paytm',
				'title'      => '<img src="'.$invert_logo.'" width="300px" alt="Paytm" title="Paytm">',
				'terms'      => '',
				'sort_order' => $this->config->get('payment_paytm_sort_order')
			);
		}

		return $method_data;
	}
	/**
	* save response in db
	*/
	public function saveTxnResponse($order_id, $data  = array()){
		if(empty($data['STATUS'])) return false;

		$status 			= (!empty($data['STATUS']) && $data['STATUS'] =='TXN_SUCCESS') ? 1 : 0;
		$paytm_order_id 	= (!empty($data['ORDERID'])? $data['ORDERID']:'');
		$transaction_id 	= (!empty($data['TXNID'])? $data['TXNID']:'');

		$sql =  "INSERT INTO " . DB_PREFIX . "paytm_order_data SET order_id = '" . $order_id . "', paytm_order_id = '" . $paytm_order_id . "', transaction_id = '" . $this->db->escape($transaction_id) . "', status = '" . (int)$status . "', paytm_response = '" . $this->db->escape(json_encode($data)) . "', date_added = NOW(), date_modified = NOW()";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
}
?>