<?php
        $breadcrubs = $modul->getBreadCrumbs($modul->id);		

        $page = !empty($_GET['page']) ? abs((int)$_GET['page']) : 1;
        $limit = $configs->news_count ? $configs->news_count : 10;

		$item = $modul->getInfo();
		
		$limit_comment = 4;
		$fmakeComments = new fmakeComments();
		//$fmakeComments->modul = $include_param_modul;
		$comments = $fmakeComments->getByPage(false,$limit,$page,true,true);
		$globalTemplateParam->set('main_comments', $main_comments);
						
		//printAr($news);				
						
		$count = $fmakeComments->getByPageCount(false,true);
		$pages = ceil($count/$limit);
		
		if ($page < 1) {
			$page = 1;
		}
		elseif ($page > $pages) {
			$page = $pages;
		}

		
		$modul->title = $item['title'];
		$modul->description = $item['description'];

		//echo $item[$news_obj->idField];
		//$breadcrubs = $modul->getBreadCrumbs($item[$news_obj->idField]);
		$globalTemplateParam->set('breadcrubs', $breadcrubs);
		
		//printAr($breadcrubs);
		
		$globalTemplateParam->set('comments', $comments);
		$globalTemplateParam->set('page', $page);
		$globalTemplateParam->set('pages', $pages);
		$globalTemplateParam->set('item', $item);
		$modul->template = "comments/all_comments.tpl";

?>
