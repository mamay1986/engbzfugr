<?php

		$no_right_menu = true;
        $globalTemplateParam->set('no_right_menu', $no_right_menu);

        $breadcrubs = $modul->getBreadCrumbs($modul->id);
        $interv_obj = new fmakeSiteModule();
		$fmakeTag = new fmakeSiteModule_tags();
        $globalTemplateParam->set('interv_obj', $interv_obj);
        //$reports_url = $reports_obj->getUrlReports();
		//$globalTemplateParam->set('reports_url',$reports_url);
        $page = !empty($_GET['page']) ? abs((int)$_GET['page']) : 1;
        $limit = $configs->reports_count ? $configs->reports_count : 10;
        
        //$offset = ($page - 1) * $limit;
         
        if($request -> getEscape('url')){
            
            //$item = $reports_obj->getItemByRedir($request->modul);
            $interv_obj->setRedir($request->modul);
			$item = $interv_obj->getInfo();
        	
			$fmakeTypeTable = new fmakeTypeTable();
			$absitem_dop = new fmakeSiteModule();
			$absitem_dop->table = $fmakeTypeTable->getTable(12);
			$absitem_dop->setId($item[$interv_obj->idField]);
			$item['dop_params'] = $absitem_dop->getInfo();
            //printAr($item);   
            //$id_gallery = $reports_obj->getGalleryId($item['id']);
            
            $fmakeGallery = new fmakeGallery_Image();
           	$photos = $fmakeGallery->getFullPhoto($item[$interv_obj->idField]);
            $globalTemplateParam->set('photos', $photos);

            
			$include_param_id_comment = $item[$interv_obj->idField];
			//$include_param_modul = $reports_obj->mod;
			include 'helpModules/comments.php';
			
			$tags = $fmakeTag->getTags($item[$interv_obj->idField]);
			$item['tags'] = $tags;
			
            $modul->title = $item['title'];
            $modul->description = $item['description'];
			$modul->keywords = $item['keywords'];
            
            $breadcrubs = $modul->getBreadCrumbs($item[$interv_obj->idField]);
			
			$globalTemplateParam->set('breadcrubs', $breadcrubs);
            $globalTemplateParam->set('item', $item);
            
            $modul->template = "interv/item.tpl";

        }else{
        	$interv_obj->order = "b.date DESC, a.id";
            $interv_obj->setRedir($request->modul);
            $item = $interv_obj->getInfo();
                
            $intervs = $interv_obj->getByPage($item[$interv_obj->idField], $limit, $page,false,$modul->id,true);
                                
            $count = $interv_obj->getByPageCount($item[$interv_obj->idField],false,$modul->id,true);
			$pages = ceil($count/$limit);
        	
			if ($page < 1) {
				$page = 1;
			}
			elseif ($page > $pages) {
				$page = $pages;
			}
			
			if($intervs)foreach($intervs as $key=>$item_new){
				$tags = $fmakeTag->getTags($item_new[$interv_obj->idField]);
				$intervs[$key]['tags'] = $tags;
			}
			
			$modul->title = $item['title'];
            $modul->description = $item['description'];
			$modul->keywords = $item['keywords'];
			
			$breadcrubs = $modul->getBreadCrumbs($modul->id);
			
			$globalTemplateParam->set('breadcrubs', $breadcrubs);
			$globalTemplateParam->set('pages', $pages);
			$globalTemplateParam->set('page', $page);
			$globalTemplateParam->set('intervs', $intervs);
			
			$modul->template = "interv/all_interv.tpl";
        }
	
		/*$breadcrubs = $modul->getBreadCrumbs($modul->id);	
		$globalTemplateParam->set('breadcrubs', $breadcrubs);
		
		$modul->template = "text/text.tpl";*/
?>
