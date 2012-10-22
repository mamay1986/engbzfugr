<?
header('Content-type: text/html; charset=utf-8'); 

setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');

ini_set('display_errors',1);
error_reporting(7);
session_start();

require('./fmake/FController.php');

$consumer_key = "XdJoIEgEjxoyYxIRJ86yBg";
$consumer_secret = "iKWwk29TxqzjyKcrMq74vigLW4QKsOn4Y1jvknj6g4";
$callback_url = "http://engels.bz/twitter.php";

$globalConfigsTwitter = new globalConfigs();

switch($_GET['action']){
	case 'link':
		$globalConfigsTwitter = new globalConfigs();
		$oauth = new TwitterOAuth($consumer_key, $consumer_secret);
		// получаем временные ключи для получения PIN'а
		$token = $oauth->getRequestToken($callback_url);
		$request_token = $token['oauth_token'];
		$request_token_secret = $token['oauth_token_secret'];
		
		// сохраняем их во временную таблицу базы данных
		$globalConfigsTwitter ->udateByValue('token_twitter_temp', $request_token);
		$globalConfigsTwitter ->udateByValue('token_secret_twitter_temp', $request_token_secret);
		
		//echo("token = ".$request_token."<br/> secret_token = ".$request_token_secret."<br/>");
		// а теперь создаем ссылку для получения PIN'а
		$request_link = $oauth->getAuthorizeURL($token);

		// и на этом этапе заканчиваем выполнение скрипта
		// выведя необходимую ссылку
		//die("Перейдите по ссылке: <a href=\"{$request_link}\" >авторизация в Twitter</a> \n");
		if($request_link){
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: '.$request_link);
		}
		break;
	case 'post_news':
		if($configs->request_token_twitter && $configs->request_token_secret_twitter && $_GET['key']=='1029384756' && $_GET['id_news']){
			$access_token = $configs->request_token_twitter;
			$access_token_secret = $configs->request_token_secret_twitter;
			// А теперь можно проверить
			$twitter = new TwitterOAuth($consumer_key, $consumer_secret,
			$access_token, $access_token_secret);
			
			$id_content = $_GET['id_news'];
	
			$fmakeSiteModule = new fmakeSiteModule();
			$url = $fmakeSiteModule->getLinkPage($id_content);
				
			$fmakeSiteModule->setId($id_content);
			$item = $fmakeSiteModule->getInfo();
			
			/*$id_page_modul = 2;

			$fmakeTypeTable = new fmakeTypeTable();
			$absitem_dop = new fmakeTypeTable();
			$absitem_dop->table = $fmakeTypeTable->getTable($id_page_modul);
			$absitem_dop->setId($id_content);
			$items_dop = $absitem_dop->getInfo();*/
			/*параметры твита*/
			$link = "http://".$_SERVER['HTTP_HOST'].$url;
			$text = $item['caption'];
			
			$params = array("status"=>$text." ".$link);
			/*параметры твита*/
			
			$result = $twitter->post("statuses/update",$params);   
			//printAr($result);
		}
		break;
}
if($_GET['oauth_verifier'] && $_GET['oauth_token']){
	// сначала получим сохраненные временные ключи
	
	 
	$request_token = $configs->token_twitter_temp;
	$request_token_secret = $configs->token_secret_twitter_temp;
	$pin = $_GET['oauth_verifier'];
	// создаем объект авторизации, третим и четвертым параметром
	// передаем временные ключи авторизации
	$oauth = new TwitterOAuth($consumer_key, $consumer_secret,
	$request_token, $request_token_secret);   
	 
	
	// получаем постоянные ключи авторизации
	// используя PIN
	
	$request = $oauth->getAccessToken($pin);
	 
	$access_token = $request['oauth_token'];
	$access_token_secret = $request['oauth_token_secret'];
	 
	// сохраняем токен в таблицу
	$globalConfigsTwitter ->udateByValue('request_token_twitter', $access_token);
	$globalConfigsTwitter ->udateByValue('request_token_secret_twitter', $access_token_secret);
	
	// А теперь можно проверить
	$twitter = new TwitterOAuth($consumer_key, $consumer_secret,
	$access_token, $access_token_secret);
	 
	$credentials = $oauth->get("account/verify_credentials");   
	 
	 $globalConfigsTwitter ->udateByValue('user_twitter', $credentials->screen_name);
	 
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: http://'.$hostname.'/admin/?modul=siteconfigs');
	//echo "Вы аторизовались под ником: @". $credentials->screen_name ."\n"; 
} 

