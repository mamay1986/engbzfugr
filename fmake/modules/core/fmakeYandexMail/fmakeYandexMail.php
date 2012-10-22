<?php
	/**
	*
	* Пользователь системы
	*/

	class fmakeYandexMail{
		
		public $token = "edf199179464b7ad40dec2792071921ac5812497b9d545466446d937";
		public $domain = "engels.bz";
		
		function apiFunc($name, $params){
			
			if($params)foreach($params as $key=>$item){
				$str_param .= "&{$key}={$item}";
			}
			$url_ya_mail = "https://pddimp.yandex.ru/{$name}.xml?{$str_param}";
			$curl = new cURL();
			$curl -> init();
			$curl -> get($url_ya_mail);
			$result = $curl -> data();
			
			//echo $result;
			$dom = new domDocument;
			$dom->loadXML($result);
			$s = simplexml_import_dom($dom);
			return $s;
		}
	
	}