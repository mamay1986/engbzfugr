<?php

        $fmakeTags = new fmakeSiteModule_tags();
        $page = !empty($_GET['page']) ? abs((int)$_GET['page']) : 1;
        $limit = $configs->reports_count ? $configs->reports_count : 10;

		$tag = intval($request -> getEscape('modul'));
		
        if($tag){
			$fmakeTags = new fmakeSiteModule_tags();
			$fmakeSiteModul = new fmakeSiteModule();
            $fmakeSiteModul->setId(220);
            $_item = $fmakeSiteModul->getInfo();
                
			$fmakeTags->setId($tag);
            $info_tag = $fmakeTags->getInfo();	
				
            $tags = $fmakeTags->getByPage($tag, $limit, $page,false,true);
                                
            $count = $fmakeTags->getByPageCount($tag,false,true);
			$pages = ceil($count/$limit);
        	
			$fmakeSiteModulRelation = new fmakeSiteModule_relation();
			
			if($tags)foreach($tags as $key=>$item){
				
				$pages_relation = $fmakeSiteModulRelation->getParents($item['id'],'level');
				//printAr($pages_relation);
				$fmakeTypeTable = new fmakeTypeTable();
				$absitem_dop = new fmakeTypeTable();
				$absitem_dop->table = $fmakeTypeTable->getTable($pages_relation[1]['id_parent']);
				//echo $pages_relation[1]['id_parent']."<br/>";
				//printAr($pages_relation);
				//echo 'qq';
				if($absitem_dop->table){
					$absitem_dop->setId($item['id']);
					$tags_dop = $absitem_dop->getInfo();
					$tags[$key]['anons'] = $tags_dop['anons'];
					$tags[$key]['date'] = $tags_dop['date'];
				}
			
				$_tags = $fmakeTags->getTags($item['id']);
				$tags[$key]['tags'] = $_tags;
			}
			
			if ($page < 1) {
				$page = 1;
			}
			elseif ($page > $pages) {
				$page = $pages;
			}
			
			$breadcrubs = $modul->getBreadCrumbs($modul->id);
			
			$globalTemplateParam->set('breadcrubs', $breadcrubs);
			$globalTemplateParam->set('pages', $pages);
			$globalTemplateParam->set('page', $page);
			$globalTemplateParam->set('tags', $tags);
			$globalTemplateParam->set('item', $_item);
			$globalTemplateParam->set('pager_tag', $tag);
			$globalTemplateParam->set('info_tag', $info_tag);
			
			$modul->template = "tags/category.tpl";
        }
		else{
			$modul->error404();
		}

?>
