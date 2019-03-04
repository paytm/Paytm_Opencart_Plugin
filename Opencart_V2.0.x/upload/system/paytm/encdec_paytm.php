<?php
class PaytmPayment { 
	private static $connect_timeout = 3;
	private static $timeout = 10;
	private static $iv = "@@@@&&&&####$$$$";

	static function encrypt_e($input, $ky) {
		$key = html_entity_decode($ky);

		if(function_exists('openssl_encrypt')){
			$data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, self::$iv );
		} else {
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
			$input = self::pkcs5_pad_e($input, $size);
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
			mcrypt_generic_init($td, $key, self::$iv);
			$data = mcrypt_generic($td, $input);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$data = base64_encode($data);
		}
		return $data;
	}

	static function decrypt_e($crypt, $ky) {
		$key   = html_entity_decode($ky);
		
		if(function_exists('openssl_decrypt')){
			$data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, self::$iv );
		} else {
			$crypt = base64_decode($crypt);
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
			mcrypt_generic_init($td, $key, self::$iv);
			$data = mdecrypt_generic($td, $crypt);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$data = self::pkcs5_unpad_e($data);
			$data = rtrim($data);
		}
		return $data;
	}

	static function pkcs5_pad_e($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	static function pkcs5_unpad_e($text) {
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text))
			return false;
		return substr($text, 0, -1 * $pad);
	}

	static function generateSalt_e($length) {
		$random = "";
		srand((double) microtime() * 1000000);

		$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
		$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
		$data .= "0FGH45OP89";

		for ($i = 0; $i < $length; $i++) {
			$random .= substr($data, (rand() % (strlen($data))), 1);
		}

		return $random;
	}

	static function checkString_e($value) {
		$myvalue = trim($value);
		if ($myvalue == 'null')
			$myvalue = '';
		return $myvalue;
	}

	static function getChecksumFromArray($arrayList, $key) {
		ksort($arrayList);
		$str = self::getArray2Str($arrayList);
		$salt = self::generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash = hash("sha256", $finalString);
		$hashString = $hash . $salt;
		$checksum = self::encrypt_e($hashString, $key);
		return $checksum;
	}

	static function verifychecksum_e($arrayList, $key, $checksumvalue) {
		$arrayList = self::removeCheckSumParam($arrayList);
		ksort($arrayList);
		$str = self::getArray2StrForVerify($arrayList);
		$paytm_hash = self::decrypt_e($checksumvalue, $key);
		$salt = substr($paytm_hash, -4);

		$finalString = $str . "|" . $salt;
		$website_hash = hash("sha256", $finalString);
		$website_hash .= $salt;
		return $website_hash == $paytm_hash? true : false;
	}

	static function getArray2Str($arrayList) {
		$findme   = 'REFUND';
		$findmepipe = '|';
		$paramStr = "";
		$flag = 1;	
		foreach ($arrayList as $key => $value) {
			$pos = strpos($value, $findme);
			$pospipe = strpos($value, $findmepipe);
			if ($pos !== false || $pospipe !== false) 
			{
				continue;
			}
			
			if ($flag) {
				$paramStr .= self::checkString_e($value);
				$flag = 0;
			} else {
				$paramStr .= "|" . self::checkString_e($value);
			}
		}
		return $paramStr;
	}

	static function getArray2StrForVerify($arrayList) {
		$paramStr = "";
		$flag = 1;
		foreach ($arrayList as $key => $value) {
			if ($flag) {
				$paramStr .= self::checkString_e($value);
				$flag = 0;
			} else {
				$paramStr .= "|" . self::checkString_e($value);
			}
		}
		return $paramStr;
	}

	static function removeCheckSumParam($arrayList) {
		if (isset($arrayList["CHECKSUMHASH"])) {
			unset($arrayList["CHECKSUMHASH"]);
		}
		return $arrayList;
	}


	static function executecUrl($apiURL, $requestParamList) {
		$responseParamList = array();
		$JsonData = json_encode($requestParamList);
		$postData = 'JsonData='.urlencode($JsonData);
		$ch = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		/*
		** default value is 2 and we also want to use 2
		** so no need to specify since older PHP version might not support 2 as valid value
		** see https://curl.haxx.se/libcurl/c/CURLOPT_SSL_VERIFYHOST.html
		*/
		// curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);

		// TLS 1.2 or above required
		// curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json', 
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse = curl_exec($ch);   

		if (!curl_errno($ch)) {
			return json_decode($jsonResponse, true);
		} else {
			return false;
		}
	}
}