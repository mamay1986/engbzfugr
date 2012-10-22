<?php

if (!$admin->isLogined())
    die("Доступ запрещен!");

$flag_url = true;

# Поля
$filds = array(
    'title' => 'Название',
);

$globalTemplateParam->set('filds', $filds);

$ignor_delete_security = true;
$globalTemplateParam->set('ignor_delete_security', $ignor_delete_security);

$fmakeSiteModulRelation = new fmakeSiteModule_relation();
$tags = new fmakeSiteModule_tags();

$absitem = new fmakeSiteModule();
$globalTemplateParam->set('absitem', $absitem);
$absitem->setId($request->id);
$absitem->tree = false;

$id_page_modul = 4;

$fmakeTypeTable = new fmakeTypeTable();
$absitem_dop = new fmakeTypeTable();
$absitem_dop->table = $fmakeTypeTable->getTable($id_page_modul);
$absitem_dop->setId($request->id);

$meets_categories = $absitem->getChilds($id_page_modul);
//printAr($news_categories);

$actions = array('active',
    'edit',
    'delete');
$globalTemplateParam->set('actions', $actions);

$limit = 30;
$page = ($request->page)? $request->page : 1;

/*фильтры*/
$filters_left = "admin/blocks/filter_meets.tpl";
$globalTemplateParam->set('filters_left', $filters_left);

$globalTemplateParam->set('categories', $meets_categories);

$filters = $_REQUEST['filter'];
$globalTemplateParam->set('filters', $filters);
/*фильтры*/

# Actions
switch ($request->action) {
    case 'up':
    case 'down':
    case 'insert':
    case 'update':
    case 'delete':
    case 'index':
    case 'inmenu':
    case 'active':
    default:
        switch ($request->action) {
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
				
				/*-------------------выставление параметров----------------------------*/
				if(!$_POST['title'] && $_POST['caption']) $_POST['title'] = $_POST['caption'];
				if($_POST['title'] && !$_POST['caption']) $_POST['caption'] = $_POST['title'];
				
				if(!$_POST['redir']) $_POST['redir'] = $absitem->transliter($_POST['caption']);
				
				if($_POST['date']) $_POST['date'] = strtotime($_POST['date']);
				else{
					$_POST['date'] = time();
				}
        		if($_POST['date_from']) $_POST['date_from'] = strtotime($_POST['date_from']);
				else{
					$_POST['date_from'] = 0;
				}
				
				if($_POST['active'])
					$_POST['active'] = 1;
				else
					$_POST['active'] = 0;
					
				if($_POST['main'])
					$_POST['main'] = 1;
				else
					$_POST['main'] = 0;
				
				$_POST['file'] = 'item_meets';
				/*-------------------выставление параметров----------------------------*/
				
                foreach ($_POST as $key => $value){
                    //$absitem->addParam($key, mysql_real_escape_string($value));
					$absitem->addParam($key, $value);
				}
				$absitem->addParam("date_create", time());	
                $absitem->newItem();
                //$info_item = $absitem->getInfo();
                
                $fmakeSiteModulRelation->setPageRelation($_POST['parent'], $absitem->id);
                
                $_POST['id'] = $absitem->id;
                foreach ($_POST as $key => $value){
                    //$absitem->addParam($key, mysql_real_escape_string($value));
					$absitem_dop->addParam($key, $value);
				}
							
                $absitem_dop->newItem();
                
				/*теги*/
				$tags->addTags($_POST['tags'],$absitem -> id) ;
				/*теги*/
                
                if($_FILES['picture']['tmp_name'])
                    $absitem->addFile($_FILES['picture']['tmp_name'], $_FILES['picture']['name']);
            	
                break;

            case 'update': // Переписать
				/*-------------------выставление параметров----------------------------*/
				if(!$_POST['title'] && $_POST['caption']) $_POST['title'] = $_POST['caption'];
				if($_POST['title'] && !$_POST['caption']) $_POST['caption'] = $_POST['title'];
				
				if(!$_POST['redir']) $_POST['redir'] = $absitem->transliter($_POST['caption']);
				
				if($_POST['date']) $_POST['date'] = strtotime($_POST['date']);
				else{
					$_POST['date'] = time();
				}
        		if($_POST['date_from']) $_POST['date_from'] = strtotime($_POST['date_from']);
				else{
					$_POST['date_from'] = 0;
				}
				
				if($_POST['active'])
					$_POST['active'] = 1;
				else
					$_POST['active'] = 0;
					
				if($_POST['main'])
					$_POST['main'] = 1;
				else
					$_POST['main'] = 0;
				
				/*-------------------выставление параметров----------------------------*/	
					
                foreach ($_POST as $key => $value){
                    //$absitem->addParam($key, mysql_real_escape_string($value));
					$absitem->addParam($key, $value);
				}
				
				$absitem->update();
				
				$fmakeSiteModulRelation->setPageRelation($_POST['parent'], $absitem->id);
				
				$info_items_dop = $absitem_dop->getInfo();
        		foreach ($_POST as $key => $value){
                    //$absitem->addParam($key, mysql_real_escape_string($value));
					$absitem_dop->addParam($key, $value);
				}
				
				if($info_items_dop) $absitem_dop->update();
				else $absitem_dop->newItem();
				
				/*теги*/
				$tags->addTags($_POST['tags'],$absitem -> id) ;
				/*теги*/
				
                if($_FILES['picture']['tmp_name'])
                    $absitem->addFile($_FILES['picture']['tmp_name'], $_FILES['picture']['name']);
                
                break;

            case 'delete': // Удалить
                $absitem->delete();
                $absitem_dop->delete();
                break;
        }

        $absitem->order = "b.date DESC, a.id";
		$absitem->order_as = "DESC";
		if($filters){
			$items = $absitem->getByPageAdminFilter($filters,$id_page_modul, $limit, $page,"a.`file` = 'item_meets'");
			$count = $absitem->getByPageCountAdminFilter($filters,$id_page_modul,$id_page_modul,"a.`file` = 'item_meets'");
		}else{
			$items = $absitem->getByPageAdmin($id_page_modul, $limit, $page,"a.`file` = 'item_meets'");
			$count = $absitem->getByPageCountAdmin($id_page_modul,$id_page_modul,"a.`file` = 'item_meets'");
		}
		$pages = ceil($count/$limit);
		
        $globalTemplateParam->set('items', $items);
		$globalTemplateParam->set('pages', $pages);
		$globalTemplateParam->set('page', $page);
        global $template;
        $template = $block;
        include('content.php');
        break;
    case 'edit':
        $items = $absitem->getInfo();
        
        $items_dop = $absitem_dop->getInfo();
		$flag_url = false;
    case 'new': // Далее форма

		
	
		/*теги*/
		$tagsStr = $tags -> tagsToString( $tags -> getTags ($items[$absitem->idField]) );
		$tagsJsStr = $tags -> tagsToJsString( $tags -> getAll () );
		/*теги*/
	
		$content .= '<script type="text/javascript" src="/js/admin/jquery.autocomplete.js"></script>
					<link rel="stylesheet" type="text/css" href="/js/calendar_to_time/latest.css" />
					<script type="text/javascript" src="/js/calendar_to_time/latest.js"></script>';
	
        $form = new utlFormEngine($modul, "/admin/index.php?modul=" . $request->modul, "POST", "multipart/form-data");

        $form->addHidden("action", (($_GET['action'] == 'new') ? 'insert' : 'update'));
        $form->addHidden("id", $items['id']);

        $form->addVarchar("<b>Название</b>", "caption", $items["caption"]);
		$form->addVarchar("<i>Заголовок</i>", "title", $items["title"]);
		$form->addVarchar("<i>Описание</i>", "description", $items["description"]);
		$form->addVarchar("<em>Ключевые</em>", "keywords", $items["keywords"],50,false,"");
		$form->addVarchar("<i>URL</i>", "redir", $items["redir"]);
        
        $_select = $form->addSelect("Категория", "parent");
        //$_select->AddOption(new selectOption("", "", false));
        if($meets_categories)foreach($meets_categories as $category){
            $_select->AddOption(new selectOption($category['id'], $category['title'], (($category['id'] == $items['parent'] || ($request->action=='new' && $file=='mod_text') )? true : false )));
        }
        
        $form->AddElement($_select);
		
		/*-------привязка к месту-------*/
		$fmakePlace = new fmakeSiteModule();
		$fmakePlace->order = "b.date DESC, a.id";
		$fmakePlace->order_as = "DESC";
		$all_place = $fmakePlace->getByPageAdmin(5, false, false,"a.`file` = 'item_place'");
		
		$_select = $form->addSelect("Привязка к месту", "id_place");
        $_select->AddOption(new selectOption("0", "не привязано к месту", false));
        if($all_place)foreach($all_place as $place){
            $_select->AddOption(new selectOption($place['id'], $place['title'], (($place['id'] == $items_dop['id_place'])? true : false )));
        }
        
        $form->AddElement($_select);
		/*-------привязка к месту-------*/
		
        //$form->addVarchar("Дата (ДД.ММ.ГГГГ)", "date", $absitem->setDate($items['date']));
        
		$form->addHtml('Дата (ДД.ММ.ГГГГ ЧЧ:мм:сс)',"<td>Дата (ДД.ММ.ГГГГ ЧЧ:мм:сс)</td><td><input type=\"text\" class=\"datepickerTimeField\" name=\"date\" value=\"".(($items_dop['date'])? $absitem->setDate($items_dop['date'],"d.m.Y H:i:s") : '')."\"  ></td>");
		$form->addHtml('Дата (ДД.ММ.ГГГГ ЧЧ:мм:сс)',"<td>Дата окончания(ДД.ММ.ГГГГ ЧЧ:мм:сс)</td><td><input type=\"text\" class=\"datepickerTimeField\" name=\"date_from\" value=\"".(($items_dop['date_from'])? $absitem->setDate($items_dop['date_from'],"d.m.Y H:i:s") : '')."\"  ></td>");
        if($items['picture'])
            $form->addHtml("", "<tr><td colspan='2'><img width='150' src='/{$absitem->fileDirectory}{$items['id']}/{$items['picture']}' /></td></tr>");
        $form->addFile("Фото:", "picture",$text = false);
        
        $form->addTextArea("Анонс", "anons", $items_dop["anons"], 50, 50);
        
		$form->addCheckBox("Включить/Выключить", "active", 1, ($items["active"]==='0') ? false : true);
		
        $form->addCheckBox("Главное событие", "main", 1, ($items_dop["main"]==='0') ? false : true);
        
		/*теги*/
		$form->addTextAreaMini("Метки ( через запятую )", "tags", $tagsStr,1,1);
		/*теги*/
				
        $form->addTinymce("Текст", "text", $items["text"]);

        $form->addSubmit("save", "Сохранить");
        $content .= $form->printForm();

		/*теги*/
		$content .= '<script type="text/javascript">
			var tags = ['.$tagsJsStr.']
		
			$("#tags").autocomplete(tags , {
				multiple: true,
				mustMatch: false,
				autoFill: true
			});
		</script>';
		/*теги*/
		
		$content .= "
		<script type=\"text/javascript\" >
			$(document).ready(function(){

				/*$('#filter-date1').DatePicker({
					format:'d.m.Y',
					date: '',
					current: '',
					starts: 1,
					onShow:function() {
						return false;
					},
					onChange:function(dateText) {
					   document.getElementById('filter-date1').value = dateText;
					   $('#filter-date1').DatePickerHide();
					}
				});*/

				$('.datepickerTimeField').datepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat: 'dd.mm.yy',
					firstDay: 1, changeFirstDay: false,
					navigationAsDateFormat: false,
					duration: 0,// отключаем эффект появления
					onSelect: function() {
						datepickerYaproSetTime();
					}
			
				}).click(function(){// при открытии календаря
					$('.datepickerYaproSelected').removeClass('datepickerYaproSelected');// удаляем со всех элементов класс идентификации
					$(this).addClass('datepickerYaproSelected');// добавляем класс для возможности идентификации выбранного INPUT
					datepickerYaproSetClockSelect();// выставляем значения элементам SELECT
				});
				
			});
		</script>		
		";
		
		if($flag_url){
		$content .='
			<script>
				$("#caption").keyup(function(){
					convert2EN("caption","redir");
				});
			</script>
		';
		}
		
        $globalTemplateParam->set('content', $content);
        $block = "admin/edit/simple_edit.tpl";
        global $template;
        $template = $block;
        break;
}
?>
