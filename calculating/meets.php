<?php
        $breadcrubs = $modul->getBreadCrumbs($modul->id);
        $meets_obj = new fmakeSiteModule();
		$fmakeTag = new fmakeSiteModule_tags();
        $globalTemplateParam->set('meets_obj', $meets_obj);
        
        $page = ($request->page)? $request->page : 1; 

        $limit = $configs->news_count ? $configs->news_count : 10;

        $cat = $meets_obj->getChilds($modul->id,true);
        
        $globalTemplateParam->set('categories', $cat);
        
        switch($request->getFilter('action')){
            case 'search':
                    $search_string = $request->getFilter('search_string') ? strip_tags($request->getFilter('search_string')) : null;
                    $category = $request->getFilter('event_category') ? strip_tags($request->getFilter('event_category')) : null;
                    $date = $request->getFilter('event_date') ? strip_tags($request->getFilter('event_date')) : null;

                    $meets_obj->setRedir($request->modul);
					$item = $meets_obj->getInfo();
                    $globalTemplateParam->set('item', $item);
                    
                    $meets_obj_search = new fmakeMeets();
                    $meets_obj_search->order = "b.date_from ASC, a.id";
                    $meets = $meets_obj_search->setSearch($search_string,$date,$category,$modul->id,$limit,$page);
					$count = $meets_obj_search->setSearchCount($search_string, $date, $category, $modul->id);
				
					if($date){
						$date_html = $meets_obj_search->dateFilter($date);
						$globalTemplateParam->set('search_date_to', $date_html['to']);
						$globalTemplateParam->set('search_date_from', $date_html['from']);
					}
					
					//echo $page;
                    $pages = ceil($count/$limit);
                    if ($page < 1) {
                            $page = 1;
                    }
                    elseif ($page > $pages) {
                            $page = $pages;
                    }
                    
                    $globalTemplateParam->set('search_string', $search_string);
                    $globalTemplateParam->set('event_category', $category);
                    if(preg_match("/(\d{2})\.(\d{2})\.(\d{4})/", $date)){
                        $globalTemplateParam->set('date', $date);
                    }
                    $globalTemplateParam->set('event_date', $date);
                    
                    if(!$meets){
                        $not_found = true;
                        $globalTemplateParam->set('not_found', $not_found);
                    }
                    else
                        $globalTemplateParam->set('meets', $meets);
                        
                    $query_str = "";
                   // printAr($_REQUEST);
                    if ($_GET['filter'])foreach ($_GET['filter'] as $key=>$item){
                    	$query_str .= "&filter[{$key}]={$item}";
                    }
					
					$modul->title = $item['title'];
					$modul->description = $item['description'];
					$modul->keywords = $item['keywords'];
					
                    //echo $modul->id;
                    $globalTemplateParam->set('breadcrubs', $breadcrubs);
                    $globalTemplateParam->set('pages', $pages);
                    $globalTemplateParam->set('page', $page);
                    $globalTemplateParam->set('query_str', $query_str);
                    //printAr($meets);
                    $modul->template = "meets/category.tpl";
                break;
            default: 
                if($request -> getEscape('url')){
		            $url_arr = explode('/', $request -> getEscape('url'));
		            
		            list($main_cat, $cat, $item) = $url_arr;
		
		            if(is_string($item)){
		                //$item = $meets_obj->getItemByRedir($request->modul);
		                $meets_obj->setRedir($request->modul);
						$item = $meets_obj->getInfo();
						
						$fmakeTypeTable = new fmakeTypeTable();
						$meets_obj_dop = new fmakeSiteModule();
						$meets_obj_dop->table = $fmakeTypeTable->getTable($modul->id);
						$meets_obj_dop->setId($item[$meets_obj->idField]);
						$item['dop_params'] = $meets_obj_dop->getInfo();
		            	
						/*привязка к месту проведения*/
						$places_obj = new fmakeSiteModule();
						$places_obj->setid($item['dop_params']['id_place']);
						$info_place = $places_obj->getInfo();
						$item['dop_params']['info_place'] = $info_place;
						/*привязка к месту проведения*/
						
						$include_param_id_comment = $item[$meets_obj->idField];
						include 'helpModules/comments.php';
						
						$tags = $fmakeTag->getTags($item[$meets_obj->idField]);
						$item['tags'] = $tags;

						$modul->title = $item['title'];
						$modul->description = $item['description'];
						$modul->keywords = $item['keywords'];
						
		            	$breadcrubs = $modul->getBreadCrumbs($item[$meets_obj->idField]);
						
		                $globalTemplateParam->set('item', $item);
		                $globalTemplateParam->set('breadcrubs', $breadcrubs);
		                $modul->template = "meets/item.tpl"; //exit;
		            }elseif(is_string($cat)){
		                //echo $request->modul;
		                //$item = $meets_obj->getItemByRedir($request->modul, true);
						$meets_obj->order_as = "ASC";		
		                $meets_obj->order = "b.date_from ASC, b.date";
		                $meets_obj->setRedir($request->modul);
						$item = $meets_obj->getInfo();
		            	
		                $modul->title = $item['title'];
						$modul->description = $item['description'];
						$modul->keywords = $item['keywords'];
		                
						$today_time = strtotime("today");
		                $meets = $meets_obj->getByPage($item[$meets_obj->idField], $limit, $page," (b.date >= '{$today_time}' OR b.date_from >= '{$today_time}') ",$modul->id,true);
                                
		                $count = $meets_obj->getByPageCount($item[$meets_obj->idField]," (b.date >= '{$today_time}' OR b.date_from >= '{$today_time}') ",$modul->id,true);
						$pages = ceil($count/$limit);
		                
		                
		                
		
		                if ($page < 1) {
		                        $page = 1;
		                }
		                elseif ($page > $pages) {
		                        $page = $pages;
		                }
						
						if($meets)foreach($meets as $key=>$item_new){
							$tags = $fmakeTag->getTags($item_new[$meets_obj->idField]);
							$meets[$key]['tags'] = $tags;
						}
						
		                $breadcrubs = $modul->getBreadCrumbs($item[$meets_obj->idField]);
		                
						$globalTemplateParam->set('meets', $meets);
		                $globalTemplateParam->set('item', $item);
		                $globalTemplateParam->set('breadcrubs', $breadcrubs);
		                $globalTemplateParam->set('pages', $pages);
                    	$globalTemplateParam->set('page', $page);
		                $modul->template = "meets/category.tpl"; //exit;
		            }
		        }else{      
		        			        
					$meets_obj->order_as = "ASC";				
			        $meets_obj->order = "b.date_from ASC, b.date";
			        
			        $meets_obj->setRedir($request->modul);
					$item = $meets_obj->getInfo();
			        
			        $today_time = strtotime("today");
	                $meets = $meets_obj->getByPageAdmin($modul->id, $limit, $page,"a.`file` = 'item_meets' AND ( b.date >= '{$today_time}' OR b.date_from >= '{$today_time}')",true);
	                                
	                $count = $meets_obj->getByPageCountAdmin($modul->id,$modul->id,"a.`file` = 'item_meets' AND ( b.date >= '{$today_time}' OR b.date_from >= '{$today_time}')",true);
	                $pages = ceil($count/$limit);
			
		        	if ($page < 1) {
						$page = 1;
					}
					elseif ($page > $pages) {
						$page = $pages;
					}
					
					if($meets)foreach($meets as $key=>$item_new){
						$tags = $fmakeTag->getTags($item_new[$meets_obj->idField]);
						$meets[$key]['tags'] = $tags;
					}
					
					$breadcrubs = $modul->getBreadCrumbs($modul->id);
					
			        $globalTemplateParam->set('meets', $meets);
					$globalTemplateParam->set('breadcrubs', $breadcrubs);
					$globalTemplateParam->set('pages', $pages);
                    $globalTemplateParam->set('page', $page);
					$globalTemplateParam->set('item', $item);
                    
					$modul->template = "meets/category.tpl";
		        }
			break;
        }

?>
