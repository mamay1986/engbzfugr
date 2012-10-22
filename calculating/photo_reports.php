<?php

		$no_right_menu = true;
        $globalTemplateParam->set('no_right_menu', $no_right_menu);

        $breadcrubs = $modul->getBreadCrumbs($modul->id);
        $reports_obj = new fmakeSiteModule();
		$fmakeTag = new fmakeSiteModule_tags();
        $globalTemplateParam->set('reports_obj', $reports_obj);
        //$reports_url = $reports_obj->getUrlReports();
		//$globalTemplateParam->set('reports_url',$reports_url);
        $page = !empty($_GET['page']) ? abs((int)$_GET['page']) : 1;
        $limit = $configs->reports_count ? $configs->reports_count : 10;
        
        //$offset = ($page - 1) * $limit;
        
        if($request -> getEscape('url')){
            
			$url_arr = explode('/', $request -> getEscape('url'));
			
			list($main_cat, $cat, $item) = $url_arr;
	
			if(is_string($item)){;
				$reports_obj->setRedir($request->modul);
				$item = $reports_obj->getInfo();
				
				$fmakeTypeTable = new fmakeTypeTable();
				$absitem_dop = new fmakeSiteModule();
				$absitem_dop->table = $fmakeTypeTable->getTable(2);
				$absitem_dop->setId($item[$reports_obj->idField]);
				$item['dop_params'] = $absitem_dop->getInfo();
				//printAr($item);   
				//$id_gallery = $reports_obj->getGalleryId($item['id']);

				$limit_photo = 16;
				$fmakeGallery = new fmakeGallery_Image();
				$photos = $fmakeGallery->getFullPhoto($item[$reports_obj->idField]);
				$count = $fmakeGallery->getByPageCount($item[$reports_obj->idField]);
				$pages = ceil($count/$limit_photo);
				
				$gap['to'] = ($page-1)*$limit_photo;
				$gap['from'] = ($page-1)*$limit_photo+$limit_photo-1;
				$globalTemplateParam->set('gap',$gap);
				$globalTemplateParam->set('photos', $photos);
				$globalTemplateParam->set('pages', $pages);
				$globalTemplateParam->set('page', $page);

				
				$include_param_id_comment = $item[$reports_obj->idField];
				//$include_param_modul = $reports_obj->mod;
				include 'helpModules/comments.php';
				
				$modul->title = $item['title'];
				$modul->description = $item['description'];
				$modul->keywords = $item['keywords'];
				
				//echo $item['keywords'].'1';
				
				$tags = $fmakeTag->getTags($item[$reports_obj->idField]);
				$item['tags'] = $tags;
				
				$breadcrubs = $modul->getBreadCrumbs($item[$reports_obj->idField]);
				
				$globalTemplateParam->set('breadcrubs', $breadcrubs);
				$globalTemplateParam->set('item', $item);
				
				$modul->template = "photoreports/item.tpl";
			}elseif(is_string($cat)){
				$cat = $reports_obj->getChilds($modul->id,true);
			
				$fmakeGallery = new fmakeGallery_Image();
				$reports_obj->order = "b.date DESC, a.id";
				$reports_obj->setRedir($request->modul);
				$item = $reports_obj->getInfo();
					
				$reports = $reports_obj->getByPage($item[$reports_obj->idField], $limit, $page,false,$modul->id,true);
									
				$count = $reports_obj->getByPageCount($item[$reports_obj->idField],false,$modul->id,true);
				$pages = ceil($count/$limit);
				
				if ($page < 1) {
					$page = 1;
				}
				elseif ($page > $pages) {
					$page = $pages;
				}
				
				/*if($reports)foreach($reports as $key=>$item_new){
					$tags = $fmakeTag->getTags($item_new[$reports_obj->idField]);
					$reports[$key]['tags'] = $tags;
				}*/
				
				$breadcrubs = $modul->getBreadCrumbs($item[$reports_obj->idField]);
				
				$globalTemplateParam->set('gallery_obj', $fmakeGallery);
				$globalTemplateParam->set('breadcrubs', $breadcrubs);
				$globalTemplateParam->set('item', $item);
				$globalTemplateParam->set('pages', $pages);
				$globalTemplateParam->set('page', $page);
				$globalTemplateParam->set('categories', $cat);
				$globalTemplateParam->set('reports', $reports);
				
				$modul->template = "photoreports/all_report.tpl";
			}
			
        }else{
        	$cat = $reports_obj->getChilds($modul->id,true);
			
			$fmakeGallery = new fmakeGallery_Image();
			$reports_obj->order = "b.date DESC, a.id";
			$reports_obj->setRedir($request->modul);
			$item = $reports_obj->getInfo();
			
			$modul->title = $item['title'];
			$modul->description = $item['description'];
			
			$reports = $reports_obj->getByPageAdmin($modul->id, $limit, $page,"a.`file` = 'item_photo_reports'",true);
			
			$count = $reports_obj->getByPageCountAdmin($modul->id,$modul->id,"a.`file` = 'item_photo_reports'",true);
			$pages = ceil($count/$limit);
			//printAr($reports);
			if ($page < 1) {
					$page = 1;
			}
			elseif ($page > $pages) {
					$page = $pages;
			}

			/*if($places)foreach($places as $key=>$item_new){
				$tags = $fmakeTag->getTags($item_new[$places_obj->idField]);
				$places[$key]['tags'] = $tags;
			}*/
			
			$breadcrubs = $modul->getBreadCrumbs($item[$reports_obj->idField]);
			//printAr($item);
			$globalTemplateParam->set('gallery_obj', $fmakeGallery);
			$globalTemplateParam->set('reports', $reports);
			$globalTemplateParam->set('item', $item);
			$globalTemplateParam->set('pages', $pages);
			$globalTemplateParam->set('page', $page);
			$globalTemplateParam->set('categories', $cat);
			$globalTemplateParam->set('breadcrubs', $breadcrubs);
			$modul->template = "photoreports/all_report.tpl";
        }
?>
