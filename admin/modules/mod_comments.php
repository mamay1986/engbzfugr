<?php
if (!$admin->isLogined())
	die("Доступ запрещен!");
	
$flag_url = true;

# Поля
$filds = array(
	 'name'=>'Ник',
	 'date'=>'Дата добавления',
	 //'name_content'=>'Фильм',
	 'text'=>'Коммент',
	 'link'=>'Ссылка на страницу'
);

$globalTemplateParam->set('filds', $filds);

/*$filters = array(
	"news"=>array("name"=>"Новости","obj"=>new fmakeNews()),
);
$globalTemplateParam->set('filters', $filters);*/
$ignor_delete_security = true;
$globalTemplateParam->set('ignor_delete_security', $ignor_delete_security);

$absitem = new fmakeComments();	
$fmakeSiteModule = new fmakeSiteModule();

$limit = 20;
$page = $request->page ? $request->page : 1;	

	
	$actions = array('active','edit','delete');
	$globalTemplateParam->set('actions', $actions);

	$group_actions = array('g_active','g_non_active','g_invert_active');
	$globalTemplateParam->set('group_actions', $group_actions);
	include 'group_action.php';

$absitem->setId($request->id);
$absitem->tree = false;
# Actions
switch($request->action)
{
	case 'up':
	case 'down':
	case 'insert':
	case 'update':
	case 'delete':
	case 'index':
	case 'inmenu':
	case 'active':
	default:
		switch($request->action)
		{
			case 'index':
				$absitem->setIndex();
			break;

			case 'inmenu':
			case 'active':
				$absitem->setEnum($request->action);
			break;

			case 'up': // Вверх 
				$absitem->getUp();
			break;

			case 'down': // Вниз
				$absitem->getDown();
			break;

			case 'insert': // Новый
				foreach ($_POST as $key=>$value)
					$absitem ->addParam($key, $value);

				$absitem -> newItem();					
			break;
		
			case 'update': // Переписать
				foreach ($_POST as $key=>$value)
					$absitem ->addParam($key, $value);
	
				$absitem -> update();
			break;
		
			case 'delete': // Удалить

				$absitem -> delete();
			break;
			
		}
		if($request->id_content){
			//echo('qq');
			//$absitem->modul = $request->mod;
			$items = $absitem ->getByPage($request->id_content,$limit,$page,($request->active));
			$countpage = $absitem ->getByPageCount($request->id_content,($request->active));
			
		}
		else{
			
			$items = $absitem ->getByPage(false,$limit,$page,($request->active));
						
			$countpage = $absitem ->getByPageCount(false,($request->active));
			//echo('qq');
		}
		$pages = ceil($countpage/$limit);
		if($request->active === "0"){
			$moderation = true;
			$globalTemplateParam->set('moderation', $moderation);
		}
		
		if($items)foreach ($items as $key=>$item){
			$items[$key]['date'] = date('H:i d.m.Y',$item['date']);
			$link = $fmakeSiteModule->getLinkPage($item['id_content']);
			$items[$key]['link'] = "{$link}";
		}
		
		$globalTemplateParam->set('items', $items);
		$globalTemplateParam->set('pages', $pages);
		$globalTemplateParam->set('page', $page);
		global $template; 
		$template = $block;
		include('content.php');
	break;
	case 'delimg':
		$absitem -> deleteImage($name = 'icon.png');
	case 'edit':
		$items = $absitem -> getInfo();
		$flag_url = false;
	case 'new': // Далее форма
		

		
		$content .= '<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
					<link rel="stylesheet" type="text/css" href="/styles/admin/datepicker.css" />
					<script type="text/javascript" src="/js/datepicker.js"></script>';
	

		
		$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul, "POST", "multipart/form-data");
	
		$form->addHidden("action", (($_GET['action'] == 'new')?'insert':'update'));
		$form->addHidden("id", $items['id']);
		$form->addHidden("page", $request->page);
		if($request->active=='0') $form->addHidden("active", '0');
		if($request->id_content) $form->addHidden("id_content", $items['id_content']);
		//if($request->mod) $form->addHidden("mod", $request->mod);
		

		foreach ($filds as $key=>$fild)
		{
			if($key == 'file' || $key == 'text' || $key == 'name_film' || $key == 'date' ) continue;
			$form->addVarchar("<b>".$fild."</b>", $key, (($key == 'date' && $_GET['action'] == 'new')? date("Y-m-d") : $items[$key]));
		}
		$form->addFCKEditor("Текст", 'text',$items['text']);
		
		$form->addSubmit("save", "Сохранить");
		$content .= $form->printForm();
		
		
		
		$globalTemplateParam->set('content', $content);
		$block = "admin/edit/simple_edit.tpl";
		global $template; 
		$template = $block;
	break;
}
?>