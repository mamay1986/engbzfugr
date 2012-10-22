<?php
        $breadcrubs = $modul->getBreadCrumbs($modul->id);
		
        $places_obj = new fmakePlace();
		$fmakeTag = new fmakeSiteModule_tags();
		$globalTemplateParam->set('places_obj', $places_obj);
               
        $page = !empty($_GET['page']) ? abs((int)$_GET['page']) : 1;
        if($request->getFilter('check'))
            $page = 1;
        $limit = $configs->news_count ? $configs->news_count : 10;
                
        //printAr($_REQUEST); 
        
        switch($request->action){
			case 'add_place':
				//обработка формы и добавление
				//echo('qq');
				$error = false;
				//printAr($_REQUEST);
				$filds = $request->getEscape('filds');
				$filds = explode(',',$filds);
				//printAr($filds);
				if($filds)foreach($filds as $key=>$fild){
					switch($fild){
						case 'wifi':
						case 'bron_cherez_engels':
						case 'business_lunch':
						case 'banket':
							break;
						case 'email':
							if(!$request->getEscape('email') || !ereg("^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)*$", $request->getEscape('email'))) $error['email'] = "Некорректный email";
							break;
						default:
							if(!$request->getEscape($fild)) $error[$fild] = "Ошибка ввода";
							break;
					}
				}
				/*if(!$request->getEscape('parent')) $error['parent'] = "Выберите категорию";
				if(!$request->getEscape('caption')) $error['caption'] = "Введите название места";
				if(!$request->getEscape('email') || !ereg("^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)*$", $request->getEscape('email'))) $error['email'] = "Некорректный email";
				if(!$request->getEscape('phone')) $error['phone'] = "Введите телефон";
				if(!$request->getEscape('text')) $error['text'] = "Введите описание";*/
				
				
				if(!$error){
					$fmakeSiteModulRelation = new fmakeSiteModule_relation();
					$fmakeAdvert = new fmakeAdvert();
					
					$fmakeAdvert = new fmakeAdvert();
					$fmakeAdvert_dop = new fmakeTypeTable();
					$fmakeAdvert_dop->table = $fmakeAdvert_dop->getTable($modul->id);
					
					$fmakeAdvert->addParam("parent",$request->getEscape('parent'));
					$fmakeAdvert->addParam("caption",$request->getEscape('caption'));
					$fmakeAdvert->addParam("title",$request->getEscape('caption'));
					$fmakeAdvert->addParam("redir",$fmakeAdvert->transliter($request->getEscape('caption')));
					$fmakeAdvert->addParam("text",$request->text);
					$fmakeAdvert->addParam("file","item_place");
					$fmakeAdvert->addParam("active",0);
					$fmakeAdvert->newItem();
					
					$fmakeSiteModulRelation->setPageRelation($request->getEscape('parent'), $fmakeAdvert->id);
					
					$item_info = $fmakeAdvert->getInfo();
					$fmakeAdvert->addParam("redir", $item_info['redir'].$fmakeAdvert->id);
					$fmakeAdvert->update();
					
					$fmakeAdvert_dop->addParam("id", $fmakeAdvert->id);
					$fmakeAdvert_dop->addParam("date", time());
					if($filds)foreach($filds as $key=>$fild){
						switch($fild){
							default:
								$fmakeAdvert_dop->addParam($fild,$request->getEscape($fild));
								break;
						}
					}
					$fmakeAdvert_dop->newItem();
					
					if($_FILES['image']['tmp_name'])
						$fmakeAdvert->addFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
					
					$add_ok_advert = true;
					$add_ok_place_id = $fmakeAdvert->id;
					
					header("HTTP/1.1 301 Moved Permanently");
					header('Location: '.$_SERVER['REQUEST_URI'].'&placeid='.$add_ok_place_id);
					
				}else{
					$globalTemplateParam->set('error', $error);
				}
				//break;
			default:
				if($request -> getEscape('url')){
					$url_arr = explode('/', $request -> getEscape('url'));
					
					list($main_cat, $cat, $item) = $url_arr;
			
					if(is_string($item)){;
						//$item = $places_obj->getItemByRedir($request->modul);
						$places_obj->setRedir($request->modul);
						$item = $places_obj->getInfo();
					
						$fmakeTypeTable = new fmakeTypeTable();
						$absitem_dop = new fmakeSiteModule();
						$absitem_dop->table = $fmakeTypeTable->getTable($modul->id);
						$absitem_dop->setId($item[$places_obj->idField]);
						$item['dop_params'] = $absitem_dop->getInfo();
					
						/*rating*/
						$id_content = $item[$places_obj->idField];
						$fmakeRating = new fmakeRating();
						$rating_show = $fmakeRating->showRating($id_content);
						$globalTemplateParam->set('rating_show', $rating_show);
						/*rating*/
					
						/*афиши*/
						$meets_obj = new fmakeMeets();
						
						$date = strtotime("today");
						
						$meets_obj->order = "b.date DESC, a.id";
						$items_meets = $meets_obj->getByPageAdmin(4, false,false,"a.`file` = 'item_meets' and b.date >= '{$date}' and b.`id_place` = '{$item[$places_obj->idField]}'",true);
						$globalTemplateParam->set('items_meets', $items_meets);
						/*афиши*/
					
						/*фоторепортажи*/
						$photo_obj = new fmakeSiteModule();
						$photo_obj->order = "b.date DESC, a.id";
						$items_photo_report = $photo_obj->getByPageAdmin(9, false,false,"a.`file` = 'item_photo_reports' and b.`id_place` = '{$item[$places_obj->idField]}'",true);
						$globalTemplateParam->set('items_photo_report', $items_photo_report);
						/*фоторепортажи*/
					
											
						$globalTemplateParam->set('item', $item);
						$modul->title = $item['title'];
						$modul->description = $item['description'];
						$modul->keywords = $item['keywords'];
						
						$limit_photo = 16;
						$fmakeGallery = new fmakeGallery_Image();
						$photos = $fmakeGallery->getFullPhoto($item[$places_obj->idField]);
						$count = $fmakeGallery->getByPageCount($item[$places_obj->idField]);
						$pages = ceil($count/$limit_photo);
						
						$gap['to'] = ($page-1)*$limit_photo;
						$gap['from'] = ($page-1)*$limit_photo+$limit_photo-1;
						$globalTemplateParam->set('gap',$gap);
						$globalTemplateParam->set('photos', $photos);
						$globalTemplateParam->set('pages', $pages);
						$globalTemplateParam->set('page', $page);
						
						$include_param_id_comment = $item[$places_obj->idField];
						include 'helpModules/comments.php';
						
						$tags = $fmakeTag->getTags($item[$places_obj->idField]);
						$item['tags'] = $tags;
						
						$breadcrubs = $places_obj->getBreadCrumbs($item[$places_obj->idField]);
						
						$place_script = $places_obj->getScriptItemAdmin($item['id']);
						
						$globalTemplateParam->set('breadcrubs', $breadcrubs);
						$globalTemplateParam->set('place_script', $place_script);
						$modul->template = "places/item.tpl"; //exit;
					}elseif(is_string($cat)){
						//$item = $places_obj->getItemByRedir($request->modul, true);
						
						$cat = $places_obj->getChilds($modul->id,true);
						
						$places_obj->order = "b.date DESC, a.id";
						$places_obj->setRedir($request->modul);
						$item = $places_obj->getInfo();
						
						
						
						$modul->title = $item['title'];
						$modul->description = $item['description'];
						$modul->keywords = $item['keywords'];
						
						
						$places = $places_obj->getByPage($item[$places_obj->idField], $limit, $page,false,$modul->id,true);
						
						$count = $places_obj->getByPageCount($item[$places_obj->idField],false,$modul->id,true);
						$pages = ceil($count/$limit);
						//printAr($places);
						if ($page < 1) {
								$page = 1;
						}
						elseif ($page > $pages) {
								$page = $pages;
						}

						if($places)foreach($places as $key=>$item_new){
							$tags = $fmakeTag->getTags($item_new[$places_obj->idField]);
							$places[$key]['tags'] = $tags;
						}
						
						
						$breadcrubs = $modul->getBreadCrumbs($item[$places_obj->idField]);
						//printAr($item);
						$globalTemplateParam->set('places', $places);
						$globalTemplateParam->set('item', $item);
						$globalTemplateParam->set('pages', $pages);
						$globalTemplateParam->set('page', $page);
						$globalTemplateParam->set('categories', $cat);
						$globalTemplateParam->set('breadcrubs', $breadcrubs);
						$modul->template = "places/category.tpl"; //exit;
					}
				}else{
					if($request->maps){
						$places_obj = new fmakePlace();
						$cat = $places_obj->getChilds($modul->id,true);
						
						$place_script = $places_obj->getScriptAll();
						
						//printAr($cat);
						
									
						$breadcrubs = $places_obj->getBreadCrumbs($modul->id);
						
						$globalTemplateParam->set('place_script', $place_script);
						$globalTemplateParam->set('categories', $cat);
						$globalTemplateParam->set('breadcrubs', $breadcrubs);
						$modul->template = "places/all_place.tpl";
					}
					elseif($request->getEscape('form') == 'add_place' ){
						$cat = $places_obj->getChilds($modul->id,true);
						
						$breadcrubs = $modul->getBreadCrumbs($modul->id);
						$breadcrubs[] = array("caption" => "Добавление места","link" => "","redir" => "","id" => "");
						//printAr($breadcrubs);
						$globalTemplateParam->set('breadcrubs', $breadcrubs);
						$globalTemplateParam->set('categories', $cat);
						$modul->template = "places/form.tpl"; //exit;
					}
					else{
						$cat = $places_obj->getChilds($modul->id,true);
						
						$places_obj->order = "b.date DESC, a.id";
						$places_obj->setRedir($request->modul);
						$item = $places_obj->getInfo();
						
						$modul->title = $item['title'];
						$modul->description = $item['description'];
						
						
						$places = $places_obj->getByPageAdmin($modul->id, $limit, $page,"a.`file` = 'item_place' ",true);
						
						$count = $places_obj->getByPageCountAdmin($modul->id,$modul->id,"a.`file` = 'item_place'",true);
						$pages = ceil($count/$limit);
						//printAr($places);
						if ($page < 1) {
								$page = 1;
						}
						elseif ($page > $pages) {
								$page = $pages;
						}

						if($places)foreach($places as $key=>$item_new){
							$tags = $fmakeTag->getTags($item_new[$places_obj->idField]);
							$places[$key]['tags'] = $tags;
						}
						
						$breadcrubs = $modul->getBreadCrumbs($item[$places_obj->idField]);
						//printAr($item);
						$globalTemplateParam->set('places', $places);
						$globalTemplateParam->set('item', $item);
						$globalTemplateParam->set('pages', $pages);
						$globalTemplateParam->set('page', $page);
						$globalTemplateParam->set('categories', $cat);
						$globalTemplateParam->set('breadcrubs', $breadcrubs);
						$modul->template = "places/category.tpl"; //exit;
					}
				}
			break;
		}

?>
