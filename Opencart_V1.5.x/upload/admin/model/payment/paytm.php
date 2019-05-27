<?php
class ModelPaymentPaytm extends Model {

	public function install() {
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
			);");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paytm_order_data`;");
	}

	public function getPaytmOrderData($order_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytm_order_data` WHERE order_id = '" . (int)$order_id . "' ORDER BY id DESC LIMIT 1");
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
}