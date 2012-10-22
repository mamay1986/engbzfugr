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
$fmakeSiteModuleMultipleCat = new fmakeSiteModule_multiple();
$tags = new fmakeSiteModule_tags();

$absitem = new fmakePlace();
$globalTemplateParam->set('absitem', $absitem);
$absitem->setId($request->id);
$absitem->tree = false;

$id_page_modul = 5;

$fmakeTypeTable = new fmakeTypeTable();
$absitem_dop = new fmakeTypeTable();
$absitem_dop->table = $fmakeTypeTable->getTable($id_page_modul);
$absitem_dop->setId($request->id);

$place_categories = $absitem->getChilds($id_page_modul);
//printAr($_POST['parents']);

$actions = array('active',
    'edit',
    'delete');
$globalTemplateParam->set('actions', $actions);

$limit = 30;
$page = ($request->page)? $request->page : 1;

/*фильтры*/
$filters_left = "admin/blocks/filter_place.tpl";
$globalTemplateParam->set('filters_left', $filters_left);

$globalTemplateParam->set('categories', $place_categories);

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
					$_POST['date'] = date('d.m.Y',time());
				}
				
				if($_POST['active'])
					$_POST['active'] = 1;
				else
					$_POST['active'] = 0;
					
				if($_POST['main'])
					$_POST['main'] = 1;
				else
					$_POST['main'] = 0;
				
				/*доп параметры*/
				$arr_dop_param_check = array("wifi","bron_cherez_engels","business_lunch","banket");
				if($arr_dop_param_check)foreach($arr_dop_param_check as $item){
					if($_POST[$item])
						$_POST[$item] = 1;
					else
						$_POST[$item] = 0;
				}
				/*доп параметры*/
				
				
				$_POST['file'] = 'item_place';
				
				/*-------------------выставление параметров----------------------------*/
				
                foreach ($_POST as $key => $value){
                    //$absitem->addParam($key, mysql_real_escape_string($value));
					$absitem->addParam($key, $value);
				}
				$absitem->addParam("date_create", time());	
                $absitem->newItem();
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
				
                /*множественные категории*/
				$fmakeSiteModuleMultipleCat->addParents($_POST['parents'],$absitem -> id);
				/*множественные категории*/
                
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
					$_POST['date'] = date('d.m.Y',time());
				}
				
				if($_POST['active'])
					$_POST['active'] = 1;
				else
					$_POST['active'] = 0;
					
				if($_POST['main'])
					$_POST['main'] = 1;
				else
					$_POST['main'] = 0;
				
				/*доп параметры*/
				$arr_dop_param_check = array("wifi","bron_cherez_engels","business_lunch","banket");
				if($arr_dop_param_check)foreach($arr_dop_param_check as $item){
					if($_POST[$item])
						$_POST[$item] = 1;
					else
						$_POST[$item] = 0;
				}
				/*доп параметры*/
				
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
				
				/*множественные категории*/
				$fmakeSiteModuleMultipleCat->addParents($_POST['parents'],$absitem -> id);
				/*множественные категории*/
				
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
			$items = $absitem->getByPageAdminFilter($filters,$id_page_modul, $limit, $page,"a.`file` = 'item_place'");
			$count = $absitem->getByPageCountAdminFilter($filters,$id_page_modul,$id_page_modul,"a.`file` = 'item_place'");
		}else{
			$items = $absitem->getByPageAdmin($id_page_modul, $limit, $page,"a.`file` = 'item_place'");
			$count = $absitem->getByPageCountAdmin($id_page_modul,$id_page_modul,"a.`file` = 'item_place'");
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

		//галлерея
		$fmakeGalleryNotice = new fmakeGallery();
		$fmakeGalleryNotice->table = $fmakeGalleryNotice->table_notice_galley;
		$fmakeGalleryNotice->idField = 'id_site_modul';
		$fmakeGalleryNotice->setId($request->id);
		$do_gallery = $fmakeGalleryNotice->getInfo();
		$fmakeGallery = new fmakeGallery();
		$fmakeGallery->setId($do_gallery['id_gallery']);
		$item_gallery = $fmakeGallery->getInfo();
		//
	
		/*теги*/
		$tagsStr = $tags -> tagsToString( $tags -> getTags ($items[$absitem->idField]) );
		$tagsJsStr = $tags -> tagsToJsString( $tags -> getAll () );
		/*теги*/
	
		$content .= '<script type="text/javascript" src="/js/admin/jquery.autocomplete.js"></script>
					<script type="text/javascript" src="/js/gallery/admin-gallery.js"></script>
					<link rel="stylesheet" type="text/css" href="/styles/admin/datepicker.css" />
					<script type="text/javascript" src="/js/admin/datepicker.js"></script>';
	
        $form = new utlFormEngine($modul, "/admin/index.php?modul=" . $request->modul, "POST", "multipart/form-data");

        $form->addHidden("action", (($_GET['action'] == 'new') ? 'insert' : 'update'));
        $form->addHidden("id", $items['id']);

        $form->addVarchar("<b>Название</b>", "caption", $items["caption"]);
		$form->addVarchar("<i>Заголовок</i>", "title", $items["title"]);
		$form->addVarchar("<i>Описание</i>", "description", $items["description"]);
		$form->addVarchar("<em>Ключевые</em>", "keywords", $items["keywords"],50,false,"");
		$form->addVarchar("<i>URL</i>", "redir", $items["redir"]);
        
        $_select = $form->addSelect("Категория ( основная )", "parent");
        //$_select->AddOption(new selectOption("", "", false));
        if($place_categories)foreach($place_categories as $category){
            $_select->AddOption(new selectOption($category['id'], $category['title'], (($category['id'] == $items['parent'] || ($request->action=='new' && $file=='mod_text') )? true : false )));
        }
        
        $form->AddElement($_select);
		
		/*--------множественный выбор категорий----------*/
		$_select = $form->addSelect("Множественный выбор категорий", "parents[]","multiple='multiple'","multiple_parents");
        //$_select->AddOption(new selectOption("", "", false));
        if($place_categories)foreach($place_categories as $category){
            if($category['id'] != $items['parent']){
				$_select->AddOption(new selectOption($category['id'], $category['title'], (($fmakeSiteModuleMultipleCat->isItemParent($category['id'],$items[$absitem->idField]))? true : false )));
			}
        }
        
        $form->AddElement($_select);
		/*--------множественный выбор категорий----------*/
		
        //$form->addVarchar("Дата (ДД.ММ.ГГГГ)", "date", $absitem->setDate($items['date']));
		$form->addHtml('Дата (ДД.ММ.ГГГГ)',"<td>Дата (ДД.ММ.ГГГГ)</td><td><input type=\"text\" id=\"filter-date1\" name=\"date\" value=\"".$absitem->setDate($items_dop['date'])."\"  ></td>");
        if($items['picture'])
            $form->addHtml("", "<tr><td colspan='2'><img width='150' src='/{$absitem->fileDirectory}{$items['id']}/{$items['picture']}' /></td></tr>");
        $form->addFile("Фото:", "picture",$text = false);
        
        $form->addTextArea("Анонс", "anons", $items_dop["anons"], 50, 50);
        
		$form->addCheckBox("Включить/Выключить", "active", 1, ($items["active"]==='0') ? false : true);
		
        $form->addCheckBox("Отображать на главной", "main", 1, ($items_dop["main"]==='0') ? false : true);
        
		$form->addVarchar("<i>Время работы</i>", "date_work", $items_dop["date_work"]);
		$form->addVarchar("<i>Email</i>", "email", $items_dop["email"]);
		$form->addVarchar("<i>Телефон</i>", "phone", $items_dop["phone"]);
		$form->addVarchar("<i>Web сайт</i>", "web", $items_dop["web"]);
		$form->addCheckBox("Wi-Fi", "wifi", 1, ($items_dop["wifi"]=='1') ? true : false);
		$form->addCheckBox("Бронирование через Engels.bz", "bron_cherez_engels", 1, ($items_dop["bron_cherez_engels"]=='1') ? true : false);
		$form->addVarchar("<i>Кухня</i>", "kitchen", $items_dop["kitchen"]);
		$form->addVarchar("<i>Средний счет</i>", "average_chek", $items_dop["average_chek"]);
		$form->addCheckBox("Бизнес ланч", "business_lunch", 1, ($items_dop["business_lunch"]=='1') ? true : false);
		$form->addCheckBox("Банкет", "banket", 1, ($items_dop["banket"]=='1') ? true : false);
		$form->addVarchar("<i>Вместимость (кол-во чел.)</i>", "capacity", $items_dop["capacity"]);
		$form->addVarchar("<i>Парная</i>", "steam", $items_dop["steam"]);
		$form->addVarchar("<i>Бассейн</i>", "pool", $items_dop["pool"]);
		$form->addVarchar("<i>Комната отдыха</i>", "restroom", $items_dop["restroom"]);
		$form->addVarchar("<i>Музыка</i>", "music", $items_dop["music"]);
		$form->addVarchar("<i>Резиденты</i>", "residents", $items_dop["residents"]);
		$form->addVarchar("<i>Кол-во танцполов</i>", "num_dance_flors", $items_dop["num_dance_flors"]);
		$form->addVarchar("<i>Кол-во дорожек</i>", "num_track", $items_dop["num_track"]);
		$form->addVarchar("<i>Вид бильярда</i>", "type_billiards", $items_dop["type_billiards"]);
		$form->addVarchar("<i>Кол-во столов</i>", "num_tables", $items_dop["num_tables"]);
		$form->addVarchar("<i>Доп. услуги</i>", "more_services", $items_dop["more_services"]);
		
        $form->addVarchar("<i>Адресс</i>", "addres", $items_dop["addres"]);
		$form->addHidden("addres_coord", $items_dop["addres_coord"]);
        
		$form->addHtml("Google Карта","<tr><td colspan='2'>
												<div class=\"map-places\" style=\"border: 2px solid #BBBBBB;height: 320px;position: relative;\">
													<div id=\"map-content\">
														<div id=\"map_canvas\" style=\"position:absolute; z-index:1;\"></div>
														<script type=\"text/javascript\">
															initialize();
															(function() {
																makeScrollable('map_canvas', function(delta) {
																  ;
																});
															})();
														</script>
													</div>
												</div>
										</td></tr>");
		
		/*теги*/
		$form->addTextAreaMini("Метки ( через запятую )", "tags", $tagsStr,1,1);
		/*теги*/
		
		/*-----------------галлерея----------------------*/
		if($items){
			if($item_gallery){
				$form->addHtml('','<td colspan="2"><a class="action-link" onclick="return false;" id="link-gallery" href="../../fmake/modules/core/fmakeGallery/index.php?id_gallery='.$item_gallery['id'].'"><div><img alt="" src="/images/admin/and.png"></div>Изменить галерею</a> <div style="padding-top: 6px;">'.$item_gallery[caption].'</div><td>');
			}
			else{
				$form->addHtml('','<td colspan="2"><a class="action-link" onclick="return false;" id="link-gallery" href="../../fmake/modules/core/fmakeGallery/index.php?id_gallery='.$item_gallery['id'].'&id_content='.$items['id'].'"><div><img alt="" src="/images/admin/and.png"></div>Добавить галерею</a> <td>');
			}
		}
		else{
			$form->addHtml('','<td colspan="2">Для добавления галереи сохраните страницу<td>');
		}
		
		$form->addHtml("", '<td colspan="2">
<div id="iframe-pole" style="position: fixed; top:100px; left: 136px;z-index: 9999999;width: 800px; min-height: 500px;display: none;"></div></td>');
		/*-----------------галлерея----------------------*/
		
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
			
			{$absitem->getScriptItemAdmin($items['id'])}
			addPlace(array_place);
			$(document).ready(function(){

				$('#filter-date1').DatePicker({
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
				});
				
			});
			/*function codeAddress() {
				geocoder = new google.maps.Geocoder();
				var address = document.getElementById(\"addres\").value+', Саратовская область, Энгельс';
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						//alert(results[0].geometry.location);
						document.getElementById(\"addres_coord\").value = results[0].geometry.location;
					} else {
						//alert(\"Geocode was not successful for the following reason: \" + status);
					}
				});
			}*/
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
