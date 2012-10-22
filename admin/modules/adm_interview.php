<?php

if (!$admin->isLogined())
	die("Доступ запрещен!");

$flag_url = true;

# Поля
$filds = array(
	'caption'=>'Ответ',
	'stat'=>'Статистика',
);

$globalTemplateParam->set('filds', $filds);

$ignor_delete_security = true;
$globalTemplateParam->set('ignor_delete_security', $ignor_delete_security);

	$absitem = new fmakeInterview();
	$absitem->table = $absitem->table_vopros;
	$fmakeCat = new fmakeInterview();
	$all_cat = $fmakeCat->getAll(true);
	
	$absitem->setId($request->id);
	$absitem->tree = false;
	
	$actions = array('active','edit','delete');
	$globalTemplateParam->set('actions', $actions);

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

		if($request->id_interview) $items = $absitem -> getVoproses($request->id_interview);
		else $items = $absitem -> getAll();
		
		/*if($items)foreach($items as $key=>$item){
			$items[$key]['stat'] = 
		}*/
		
		$globalTemplateParam->set('items', $items);
		$globalTemplateParam->set('all_cat', $all_cat);
		global $template; 
		$template = $block;
		include('content.php');
	break;
	case 'edit':
		$items = $absitem -> getInfo();
	case 'new': // Далее форма
		
		
		$form = new utlFormEngine($modul, "/admin/index.php?modul=".$request->modul);
	
		$form->addHidden("action", (($_GET['action'] == 'new')?'insert':'update'));
		$form->addHidden("id", $items['id']);
		
		$_modul = $form->addSelect("Вопрос", "id_interview");
				$_modul->AddOption(new selectOption(0, "Нет вопроса", (($items['id_interview']==0)? true : false )));
			if($all_cat) foreach ($all_cat as $modul)
			{
				if($modul['id'] == $items['id']) continue;
				$_modul->AddOption(new selectOption($modul['id'],$modul['caption'], (($modul['id']==$items['id_interview'] || $modul['id']==$request->id_interview)? true : false )));
			}
		
		$form->AddElement($_modul);
		
		//foreach ($filds as $key=>$fild)
			//$form->addVarchar($fild, $key, (($key == 'date' && $_GET['action'] == 'new')? date("Y-m-d H:i:s") : $items[$key]));
		$form->addVarchar("Ответ", "caption",$items["caption"]);
		//$form->addVarchar("Url", "url", $items['url']);
		
		$form->addSubmit("save", "Сохранить");
		$content .= $form->printForm();
		
		$globalTemplateParam->set('content', $content);
		$block = "admin/edit/simple_edit.tpl";
		global $template; 
		$template = $block;
	break;
}
?>