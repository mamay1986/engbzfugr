<?php
if (!$admin->isLogined())
	die("Доступ запрещен!");
	
	$absitem = new globalConfigs();
	
	//$configs = $absitem->getSiteConfigs();
	//printAr($configs);
	
	switch($request->action){
		case 'change':
			//echo "qq";
			//printAr($_POST);
			foreach ($_POST['configs'] as $key=>$value){
				$absitem ->udateByValue($key, $value);
			}
		break;
		case 'change_vk':
			if($_POST['string_token_vk']){
				preg_match_all('/access_token=(.*)&expires_in=(.*)&user_id=(.*)/',$_POST['string_token_vk'],$array_result);
				$absitem ->udateByValue('token_vk', $array_result[1][0]);
				$absitem ->udateByValue('user_id_vk', $array_result[3][0]);
			}
		break;
		case 'delete_publick':
			switch($_POST['delete_name']){
				case 'vk':
					$absitem ->udateByValue('token_vk', '');
					$absitem ->udateByValue('user_id_vk', '');
					break;
				case 'twitter':
					$absitem ->udateByValue('request_token_twitter', '');
					$absitem ->udateByValue('request_token_secret_twitter', '');
					$absitem ->udateByValue('user_twitter', '');
					break;
			}
		break;
		case 'new':
			foreach ($_POST as $key=>$value){
				$absitem ->addParam($key, $value);
			}
			$absitem -> newItem();
		break;
	}
	
	
# Поля
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "Основные параметры";
	$form->addHidden("action", 'change');
	
	$_modul = $form->addSelect("Включить/Выключить сайт", "configs[site_on_off]"); 
	$_modul->AddOption(new selectOption('1', "Выключить", (($configs->site_on_off=='1')? true : false )));
	$_modul->AddOption(new selectOption('0', "Включить", (($configs->site_on_off=='0')? true : false )));
	$form->AddElement($_modul);
	
	$form->addSubmit("Добавить","Обновить");
	$content = $form->printForm();
	
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "Основные параметры";
	$form->addHidden("action", 'change');
		
	$form->addVarchar("<em><b>Телефон</b></em>", "configs[phone1]",$configs->phone1,50,false,"Используется на основных страницах сайта и в футере");
	$form->addVarchar("<em><b>Телефон 2</b></em>", "configs[phone2]",$configs->phone2,50,false,"Используется на основных страницах сайта и в футере");
	$form->addVarchar("<em><b>Емайл</b></em>", "configs[email]",$configs->email,50,false,"Используется на основных страницах сайта и в футере, а так же для рассылки и оповещения с сайта");
        
        $form->addVarchar("<em><b>Количество новостей</b><em>", "configs[news_count]",$configs->news_count,20,false,"Количество новостей, выводимых на странице");
        
        $form->addVarchar("<em><b>Количество фоторепортажей</b><em>", "configs[reports_count]",$configs->reports_count,20,false,"Количество фоторепортажей, выводимых на странице");
	
    $form->addVarchar("<em><b>Вконтакте</b><em>", "configs[link_vk]",$configs->link_vk,100,false,"");
    $form->addVarchar("<em><b>Facebook</b><em>", "configs[link_fb]",$configs->link_fb,100,false,"");
    $form->addVarchar("<em><b>Twitter</b><em>", "configs[link_tw]",$configs->link_tw,100,false,"");    

    $form->addVarchar("<em><b>ID раздела Новости от читателей</b><em>", "configs[id_news_chitateli]",$configs->id_news_chitateli,50,false,"");    
    $form->addVarchar("<em><b>ID раздела Обзоры</b><em>", "configs[id_news_obzor]",$configs->id_news_obzor,50,false,"");
    
	$form->addSubmit("Добавить","Обновить");
	$content .= $form->printForm();
	
	
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "Текстовые блоки";
	$form->addHidden("action", 'change');
	
	$form->addTextArea("<em>Блок справа</em><br />", "configs[right_block]",$configs->right_block,"Баннер справа");
	//$form->addTinymce("<em>Правый блок под виджетами</em><br />", "configs[right_text_block]",$configs->right_text_block,"Правый блок под виджетами");
	
	$form->addTinymce("<em>Главный баннер</em>(347x76)<br />", "configs[main_banner]",$configs->main_banner,"Главный баннер");
	$form->addTinymce("<em>Баннер на главной поцентру (609x133)</em><br />", "configs[center_main_block]",$configs->center_main_block,"Баннер на главной поцентру");
	$form->addTinymce("<em>Баннер на главной справа</em>(347x76)<br />", "configs[right_baner_block]",$configs->right_baner_block,"Баннер на главной справа");
	$form->addTinymce("<em>Баннер слева</em><br />", "configs[left_block]",$configs->left_block,"Баннер слева");
	//$form->addTinymce("<em>Футер</em><br />", "configs[footer]",$configs->footer,"Футер");
		
	$form->addSubmit("Добавить","Обновить"); 
	$content .= $form->printForm();
	
	/*публикация Vkontakt*/
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "настройка публикации в Вконтакте";
	$form->addHidden("action", 'change_vk');
	//$form->addFCKEditor("<em>Футер</em><br />", "configs[footer]",$configs->footer,"Футер");
	
	$vk = new VKapi();
	$app_id = $vk->thisAppId();
	$tocken = $configs->token_vk;
	$user_id_vk = $configs->user_id_vk;
	$result = $vk->isUserTokenVK($tocken,$user_id_vk);
	switch($result['error']['error_code']){
		case '5':
			$str_status = "<span style='color:red;'>Неактивна (обновить Токен Вконтакте перейдя по ссылке ниже)</span>";
			break;
		default:
			if($user_id_vk && $tocken)
				$str_status = "<span style='color:green;'>Активна</span>";
			else
				$str_status = "<span style='color:red;'>Неактивна (обновить Токен Вконтакте перейдя по ссылке ниже)</span>";
			break;
	}
	$form->addhtml("","<tr><td colspan='2'>Публикация: ".$str_status."</td></tr>");
	$form->addVarchar("<em>Токен Вконтакте</em><br />", "string_token_vk",false,255,false,"");
	$form->addHtml("","<tr><td colspan='2'>(перейдите по <a target='_blank' href='http://oauth.vk.com/authorize?client_id={$app_id}&redirect_uri=http://api.vk.com/blank.html&scope={$app_id}&display=page&response_type=token'>ссылке</a>, разрешить права, скопируйте строчку на странице и вставить в поле)</td></tr>");
	$form->addSubmit("Добавить","Обновить");
	$content .= $form->printForm();
	//http://oauth.vk.com/authorize?client_id=2781028&redirect_uri=http://api.vk.com/blank.html&scope=1008191&display=page&response_type=token
	/*публикация Vkontakt*/
	
	/*удалить публикацию Vkontakt*/
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "";
	$form->addHidden("action", 'delete_publick');
	$form->addHidden("delete_name", 'vk');
	$form->addSubmit("Добавить","Удалить публикацию в Vkontakt");
	$content .= $form->printForm();
	/*удалить публикацию Vkontakt*/
	
	/*публикация Twitter*/
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "настройка публикации в Twitter";
	$form->addHidden("action", 'change');
	//$form->addFCKEditor("<em>Футер</em><br />", "configs[footer]",$configs->footer,"Футер");
	if($configs->request_token_twitter && $configs->request_token_secret_twitter){
		$str_status = "<span style='color:green;'>Активна</span>";
	}
	else{
		$str_status = "<span style='color:red;'>Неактивна</span>";
	}
	$form->addhtml("","<tr><td colspan='2'>Публикация: ".$str_status."</td></tr>");
	if($configs->user_twitter) $form->addhtml("","<tr><td colspan='2'>Пользователь: <a target='_blank' href='https://twitter.com/#!/".$configs->user_twitter."'>@".$configs->user_twitter."</a></td></tr>");
	//$form->addVarchar("<em>Токен Вконтакте</em><br />", "string_token_vk",false,255,false,"");
	$form->addHtml("","<tr><td colspan='2'>(перейдите по <a href='/twitter.php?action=link'>ссылке</a> и разрешить права)</td></tr>");
	//$form->addSubmit("Добавить","Обновить");
	$content .= $form->printForm();
	/*публикация Twitter*/
	
	/*удалить публикацию Twitter*/
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "";
	$form->addHidden("action", 'delete_publick');
	$form->addHidden("delete_name", 'twitter');
	$form->addSubmit("Добавить","Удалить публикацию в Twitter");
	$content .= $form->printForm();
	/*удалить публикацию Twitter*/
	
	$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	$form->formLegend = "";
	$form->addHtml("Sitemap","<a href=\"http://".$hostname."/sitemap_xml.php?key=1234509876\" target=\"_blank\" style=\"font-size: 15px;\">Сгенерить sitemap.xml</a>");
	$content .= $form->printForm();
	
	$globalTemplateParam -> set('content', $content);
	global $template;
	$template = "admin/edit/simple_edit.tpl";
?>