<?php

if (!$admin->isLogined())
	die("Доступ запрещен!");

$flag_url = true;

# Поля
$filds = array(
	'caption'=>'Вопрос',
);

$globalTemplateParam->set('filds', $filds);

$ignor_delete_security = true;
$globalTemplateParam->set('ignor_delete_security', $ignor_delete_security);

	$absitem = new fmakeInterview();
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

		$items = $absitem -> getAll();
		
		
		$globalTemplateParam->set('items', $items);
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
		
		foreach ($filds as $key=>$fild)
			$form->addVarchar($fild, $key, (($key == 'date' && $_GET['action'] == 'new')? date("Y-m-d H:i:s") : $items[$key]));
		
		$form->addSubmit("save", "Сохранить");
		$content .= $form->printForm();
		
		$globalTemplateParam->set('content', $content);
		$block = "admin/edit/simple_edit.tpl";
		global $template; 
		$template = $block;
	break;
}
?>