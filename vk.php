<?

header('Content-type: text/html; charset=utf-8'); 

setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');

ini_set('display_errors',1);
error_reporting(7);
session_start();
//http://oauth.vk.com/authorize?client_id=2781028&redirect_uri=http://api.vk.com/blank.html&scope=1008191&display=page&response_type=token
//access_token=e015f127e0021bc3e0021bc31de02874a7ee002e00d79fd76b1920ab7e2017e&expires_in=0&user_id=1567460

require('fmake/FController.php');

if($configs->token_vk && $configs->user_id_vk && $_GET['key']=='1029384756' && $_GET['id_news']){
	$tocken = $configs->token_vk; 
	$user_id_vk = $configs->user_id_vk;
	$group_id_vk = '7965653';
	$id_content = $_GET['id_news'];
	
	$fmakeSiteModule = new fmakeSiteModule();
	$url = $fmakeSiteModule->getLinkPage($id_content);
		
	$fmakeSiteModule->setId($id_content);
	$item = $fmakeSiteModule->getInfo();
	
	$id_page_modul = 2;

	$fmakeTypeTable = new fmakeTypeTable();
	$absitem_dop = new fmakeTypeTable();
	$absitem_dop->table = $fmakeTypeTable->getTable($id_page_modul);
	$absitem_dop->setId($id_content);
	$items_dop = $absitem_dop->getInfo();
	
	$vk = new VKapi();
	$array_replace1 = array('&amp;','&quot;','&lt;','&gt;','&nbsp;','&laquo;','&raquo;');
	$array_replace2 = array('&','"','<','>',' ','"','"');
	$anotaciya = strip_tags($items_dop['anons']);
	$anotaciya = str_replace($array_replace1, $array_replace2, $anotaciya);
	//$anotaciya = "Тестовое сообщение";
	$post = array("message"=>$anotaciya,"link"=>"http://{$_SERVER['HTTP_HOST']}{$url}");

	if($_REQUEST['action']=='captcha'){
		$post['captcha']['captcha_sid']= $_REQUEST['captcha_sid'];
		$post['captcha']['captcha_key']= $_REQUEST['captcha_key'];
	}

	$status = $vk->SendWallVKGroup($post,$tocken,$group_id_vk);

	//printAr($status);
	
	switch($status['error']['error_code']){
		case '14':
			if($_REQUEST['popup']) $popup_str = '<input type="hidden" name="popup" value="1">';
			$str = '
			<html>
			<body>
			<form action="/vk.php?key=1029384756&id_news='.$_REQUEST['id_news'].'" method="post">
				<input type="hidden" name="action" value="captcha">
				'.$popup_str.'
				<input type="hidden" name="captcha_sid" value="'.$status['error']['captcha_sid'].'">
				<img src="'.$status['error']['captcha_img'].'"><br/>
				<input type="text" name="captcha_key" value=""><br/>
				<input type="submit" value="Отправить">
			</form>
			</body>
			</html>';
			echo($str);
			break;
		case '5':
			$str = '
			<html>
			<body>
				<p>Перейдите в <a href="/admin/index.php?modul=site_configs">параметры</a> и обновите Токен Вконтекте.</p>
			</body>
			</html>';
			echo($str);
			break;
		default:
			if($_REQUEST['popup']){
				$str = '
				<html>
				<body>
					<script>
						window.close();
					</script>
				</body>
				</html>';
				echo($str);
			}
			else{
				return 0;
			}
			break;
	}
	
}



?>