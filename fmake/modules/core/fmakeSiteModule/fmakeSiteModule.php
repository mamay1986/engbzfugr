<?php

class fmakeSiteModule extends fmakeCore {

	public $table = "site_modul";
	public $table_relation = "site_modul_relation";
	public $setName = "";
	public $fileDirectory = "images/sitemodul/";
	public $fileDirectory_old = "images/sitemodul_image/";

	/**
	 * 
	 * 
	 * @var ArrayObject fmakeSiteModule_ExtensionInterface 
	 */
	protected $extensions;
	public $order = "position";
	public $order_as = "DESC";
	public $group_by = false;
	public $tree = array();
	public static $adminModulAccessQuery = false;

	public function __isset($key) {

		return isset($this->params[$key]);
	}

	function __get($nm) {
		return $this->__isset($nm) ? $this->params[$nm] : false;
	}

	function setRedir($redir,$url = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$result = $select->addFild("id")->addFrom($this->table)->addWhere("redir = '" . $redir . "'")->addWhere("active='1'")->queryDB();
		if(!$url) $url = $_SERVER['REQUEST_URI'];
		$id = $this->setRedirController($result,$url);
		//echo $id;
		$this->id = ($id)? $id : $result[0][$this->idField];
	}

	function setRedirController($result,$url){
		$fmakeSiteModulRelation = new fmakeSiteModule_relation();
		$array_url = explode("/",$url);
		if($result)foreach($result as $key=>$item){
			$items_relation = $fmakeSiteModulRelation->getParents($item[$this->idField],'level');
			$test = true;
			$url_key = 1;
			if($items_relation)foreach ($items_relation as $_key=>$_item){
				if($_item['id_parent']){
					$test_obj = new fmakeSiteModule();
					$test_obj->setId($_item['id_parent']);
					$info = $test_obj->getInfo();
					if($info['redir']!=$array_url[$url_key]){
						$test = false;
						break;
					}
					$url_key++;
				}
			}
			if($test){
				return $item[$this->idField];
			}
		}
		$this->error404();
	}
	
	function getChilds($id = null, $active = false, $inmenu = false) {
		//echo('childs '.$type.'<br/>');
		if ($id === null)
			$id = $this->id;

		$select = $this->dataBase->SelectFromDB(__LINE__);

		if ($active)
			$select->addWhere("active='1'");
		if ($inmenu)
			$select->addWhere("inmenu='1'");

		return $select->addFrom($this->table)->addWhere("parent='" . $id . "'")->addOrder($this->order)->queryDB();
	}

	function setDate($date,$format = "d.m.Y"){
		return date($format,$date);
	}
	
	function getWeek($num_week){
		$week = array(
			"1"=>"Понедельник",
			"2"=>"Вторник",
			"3"=>"Среда",
			"4"=>"Четверг",
			"5"=>"Пятница",
			"6"=>"Суббота",
			"0"=>"Воскресенье",
		);
		return $week[$num_week];
	}
	function getWeek2($num_week){
		$week = array(
			"1"=>"ПН",
			"2"=>"ВТ",
			"3"=>"СР",
			"4"=>"ЧТ",
			"5"=>"ПТ",
			"6"=>"СБ",
			"0"=>"ВС",
		);
		return $week[$num_week];
	}
	function getMounth($num_mounth){
		$num_mounth = intval($num_mounth);
		$mounth = array(
			"1"=>"января",
			"2"=>"февраля",
			"3"=>"марта",
			"4"=>"апряля",
			"5"=>"мая",
			"6"=>"июня",
			"7"=>"июля",
			"8"=>"августа",
			"9"=>"сентября",
			"10"=>"октября",
			"11"=>"ноября",
			"12"=>"декабря",
		);
		return $mounth[$num_mounth];
	}
	
	function getAllAsTree($parent = 0, $level = 0, $active = false, $inmenu = false, $level_vlojennost = false) {
		//$array = array(2,3,4,6);
		if ($level != $level_vlojennost || !$level_vlojennost) {
			$level++;
			$items = $this->getChilds($parent, $active, $inmenu);
			//printAr($items);
			if ($items) {
				foreach ($items as $item) {
					//if($item['id'] == 2 || $item['id'] == 3 || $item['id'] == 4 || $item['id'] == 6) continue;
					if ($item['delete_security'])
						continue;
					$item['level'] = $level;
					$this->tree[] = $item;
					$this->getAllAsTree($item['id'], $level, $active, $inmenu, $level_vlojennost);
				}
			}
		}
		return $this->tree;
	}

	function getAllForMenu($parent = 0, $active = false, &$q, &$flag, $inmenu, $acces = false, $level = 0, $level_vlojennost = false, $type = false) {
		if ($level != $level_vlojennost || !$level_vlojennost) {
			$items = $this->getChilds($parent, $active, $inmenu, $type);

			if (!$items)
				return;
			foreach ($items as $key => $item) {
				if ($items[$key]['id'] == $this->id) {
					$items[$key]['status'] = true;
					$flag = !$flag;
					$q = true;
				}

				if ($flag)
					$items[$key]['status'] = &$q;
				$items[$key]['child'] = $this->getAllForMenu($item['id'], $active, $q, $flag, $inmenu, $acces, $level++, $level_vlojennost, $type);
				if ($flag)
					unset($items[$key]['status']);

			}
		}
		return $items;
	}

	function getAllCatNewsOld($parent,$active = false){
		$select = $this->dataBase->SelectFromDB( __LINE__);
		if($this->order)
			$select -> addOrder($this->order, (($this->order_as)?$this->order_as:'ASC'));
		if($active)
			$select -> addWhere("active='1'");
		if($parent)
			$select -> addWhere("`id_category` = '{$parent}'");
		return $select -> addFrom($this->table) -> queryDB();
	}
	
	function getModul($modul) {

		$where = array();
		if ($modul) {
			$where[sizeof($where)] = "`redir` = '" . $modul . "'";
			$where[sizeof($where)] = "`active` = '1'";
		} else {
			$where[sizeof($where)] = "`index` = '1'";
		}

		$arr = $this->getWhere($where);

		if ($arr[0]) {
			foreach ($arr[0] as $key => $mod) {
				$this->addParam($key, $mod);
			}
		}
		return $arr;
	}
	
	function error404() {

		global $globalTemplateParam, $twig;
		HttpError(404);
		$template = $twig->loadTemplate('404.tpl');
		$template->display($globalTemplateParam->get());
		exit();
	}

	function getPage($modul, $twig, $url = false) {

		if ($url) {
			$param = explode('/', $url);
			$modul = $param[0];
		}else
			$url = $modul;

		$this->getModul($modul);
		// находим страницы из других 
		if (!$this->id && $this->extensions) {
			foreach ($this->extensions as $name => &$obj) {
				if ($obj->getModul($modul)) {
					$this->params = $obj->params;
					$this->setName = $name;
					break;
				}
			}
		} else {
			$this->setName = $this->getName();
		}
		
		if($this->id) $this->modulController($this->id);
		
		if (!$this->id) {
			global $globalTemplateParam;
			HttpError(404);
			$template = $twig->loadTemplate('404.tpl');
			$template->display($globalTemplateParam->get());
			exit();
		}
	}

	function modulController($id,$url = false){
		$fmakeSiteModulRelation = new fmakeSiteModule_relation();
		if(!$url) $url = $_SERVER['REQUEST_URI'];
		$array_url = explode("/",$url);
		$items_relation = $fmakeSiteModulRelation->getParents($id,'level');
		$test = true;
		$url_key = 1;
		if($items_relation)foreach ($items_relation as $_key=>$_item){
			if($_item['id_parent']){
				$test_obj = new fmakeSiteModule();
				$test_obj->setId($_item['id_parent']);
				$info = $test_obj->getInfo();
				if($info['redir']!=$array_url[$url_key]){
					$test = false;
					break;
				}
				$url_key++;
			}
		}
		if($test){
			return $item[$this->idField];
		}
		$this->error404();
	}
	
	function addExtension(fmakeSiteModule_ExtensionInterface $extension) {

		$this->extensions[$extension->getName()] = $extension;
	}

	function getUp() {

		$order = $this->getInfo();
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$arr = $select->addFrom($this->table)->addWhere("`parent` = '{$order['parent']}' ")->addWhere("`position` < '{$order['position']}' ")->addOrder('position', 'DESC')->addLimit(0, 1)->queryDB();
		$arr = $arr[0];

		if ($arr) {
			$update = $this->dataBase->UpdateDB(__LINE__);
			$update->addTable($this->table)->addFild("`position`", $order['position'])->addWhere("`" . $this->idField . "` = '" . $arr['id'] . "'")->queryDB();
			$update->addTable($this->table)->addFild("`position`", $arr['position'])->addWhere("`" . $this->idField . "` = '" . $this->id . "'")->queryDB();
		}
	}

	function getDown() {

		$order = $this->getInfo();
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$arr = $select->addFrom($this->table)->addWhere("`parent` = '{$order['parent']}' ")->addWhere("`position` > '{$order['position']}' ")->addOrder('position', 'ASC')->addLimit(0, 1)->queryDB();
		$arr = $arr[0];

		if ($arr) {

			$update = $this->dataBase->UpdateDB(__LINE__);
			$update->addTable($this->table)->addFild("`position`", $order['position'])->addWhere("`id` = '" . $arr['id'] . "'")->queryDB();
			$update->addTable($this->table)->addFild("`position`", $arr['position'])->addWhere("`id` = '" . $this->id . "'")->queryDB();
		}
	}

	/*
	 * делаем две записи на одном уровне, устонавливает позицуии
	 */

	function setGeneralParent($from, $to) {
		$this->setId($to);
		$info = $this->getInfo();
		// добавляем объект в дерево
		$this->setId($from);
		$this->addParam("parent", $info['parent']);
		$this->update();
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$arr = $select->addFild("id")->addFrom($this->table)->addWhere("`parent` = '" . $info['parent'] . "' ")->addOrder('position', 'ASC')->queryDB();
		$fromNum = 0;
		$toNum = 0;
		for ($i = 0; $i < sizeof($arr); $i++) {
			if ($fromNum && $toNum)
				break;

			if ($arr[$i]['id'] == $from) {
				$fromNum = $i + 1;
			} else if ($arr[$i]['id'] == $to) {
				$toNum = $i + 1;
			}
		}
		$action = $fromNum - $toNum - 1; // -1 так как они должны быть друг под другом
		$this->setId($from);
		while ($action > 0) {
			$this->getUp();
			$action--;
		}
		while ($action < 0) {
			$this->getDown();
			$action++;
		}
	}

	/*
	 * выставляем родителя и делаем самой последней
	 */

	function setParent($child, $parent) {
		$this->setId($child);
		$this->addParam("parent", $parent);
		$this->update();

		$select = $this->dataBase->SelectFromDB(__LINE__);
		$arr = $select->addFild("id")->addFrom($this->table)->addWhere("`parent` = '" . $info['parent'] . "' ")->addOrder('position', 'ASC')->queryDB();
		$childNum = 0;
		for ($i = 0; $i < sizeof($arr); $i++) {
			if ($arr[$i]['id'] == $child) {
				$childNum = $i;
				break;
			}
		}

		$action = sizeof($arr) - $childNum;

		$this->setId($child);
		while ($action > 0) {
			$this->getDown();
			$action--;
		}
	}
	
	function getName() {

		return 'siteModul';
	}

	/**
	 * 
	 * Удаление записи, перед использованием надо установить id записи
	 */
	function delete() {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$isdelete = $select->addFrom($this->table)->addFild('delete_security')->addWhere("`" . $this->idField . "`='" . $this->id . "'")->queryDB();
		if ($isdelete[0]['delete_security'] == '0') {
			$delete = $this->dataBase->DeleteFromDB(__LINE__);
			$delete->addTable($this->table)->addWhere("`" . $this->idField . "`='" . $this->id . "'")->queryDB();
		}
	}

	function getLinkPage($id_page){
		$fmakeSiteModulRelation = new fmakeSiteModule_relation();
		$fmakeSiteModul = new fmakeSiteModule();
		$items = $fmakeSiteModulRelation->getParents($id_page,'level');
		$link = "/";
		//if($id_page == 251) printAr($items);
		//printAr($items);
		if($items)foreach ($items as $key=>$item){
			//$fmakeSiteModul->setId($item['id_parent']);
			//$item = $fmakeSiteModul->getInfoFilds("redir");
			if($item['redir_p']) $link .="{$item['redir_p']}/";
		}
		//$fmakeSiteModul->setId($id_page);
		//$item = $fmakeSiteModul->getInfoFilds("redir");
		return $link."{$item['redir_c']}/";
	}
	
	function getByPageAdmin($parent, $limit, $page, $where = "a.`file` = 'item_news'", $active = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");

		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($parent);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		if($this->order) 
			$select->addOrder($this->order, $this->order_as);
		if($this->group_by)
			$select->addGroup($this->group_by);
		if($limit)
			$select->addLimit((($page - 1) * $limit), $limit);
		return $select->addFild("a.*,b.*,c.caption name_category")->addFrom($this->table . $table_join)->queryDB();
	}
	function getByPageCountAdmin($parent, $type = false, $where = "a.`file` = 'item_news'", $active = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");

		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($parent);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		$result = $select->addFild("COUNT(*)")->addFrom($this->table . $table_join)->queryDB();
		return $result[0]["COUNT(*)"];
	}
	
	function getFilter($filters,$select){
		if($filters)foreach ($filters as $key=>$item){
			if($item){
				switch ($key){
					case 'date':
						switch ($item){
							case 'today':
								$date = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date}'");
								break;
							case 'yesterday':
								$tmp_date = strtotime("-1 day");
								$date1 = mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date),date("Y",$tmp_date));
								$date2 = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date1}'") -> addWhere("`{$key}` < '{$date2}'");
								break;
							case 'week':
								$tmp_date = strtotime("-7 day");
								$date1 = mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date),date("Y",$tmp_date));
								$date2 = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date1}'") -> addWhere("`{$key}` < '{$date2}'");
								break;
							case 'month':
								$date = mktime(0,0,0,date("m",time()),1,date("Y",time()));
								$select -> addWhere("`{$key}` >= '{$date}'");
								break;
							default:
								if($item['to']){
									$array1 = explode('.', $item['to']);
									$date = mktime(0,0,0,intval($array1[1]),intval($array1[0]),intval($array1[2]));	
									$select -> addWhere("b.`{$key}` >= '{$date}'");
								}
								if($item['from']){
									$array2 = explode('.', $item['from']);
									$date = mktime(0,0,0,intval($array2[1]),intval($array2[0]),intval($array2[2]));
									$select -> addWhere("b.`{$key}` <= '{$date}'");
								}
								
								break;
						}
						break;
					default:
						$select -> addWhere("a.`{$key}` = '{$item}'");
						break;
				}
			}
		}
	}
	
	function getByPageAdminFilter($filters,$parent, $limit, $page, $where = "a.`file` = 'item_news'", $active = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");

		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($parent);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		if($this->order) 
			$select->addOrder($this->order, $this->order_as);
		if($this->group_by)
			$select->addGroup($this->group_by);
		if($limit)
			$select->addLimit((($page - 1) * $limit), $limit);
		if($filters)
			$this->getFilter($filters,$select);
		return $select->addFild("a.*,b.*,c.caption name_category")->addFrom($this->table . $table_join)->queryDB();
	}
	function getByPageCountAdminFilter($filters,$parent, $type = false, $where = "a.`file` = 'item_news'", $active = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");

		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($parent);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		if($filters)
			$this->getFilter($filters,$select);
		$result = $select->addFild("COUNT(*)")->addFrom($this->table . $table_join)->queryDB();
		return $result[0]["COUNT(*)"];
	}
	
	function getByPage($parent, $limit, $page, $where = false, $type = false, $active = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		if ($active)
			$select->addWhere("a.active='1'");
		
		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($type);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		if($this->order) 
				$select->addOrder($this->order, $this->order_as);	
		return $select->addFild("a.*,b.*,c.caption name_category")->addFrom($this->table . $table_join)->addWhere('a.parent in (' . $parent . ')')->addLimit((($page - 1) * $limit), $limit)->queryDB();
	}

	function getByPageCount($parent, $where = false, $type = false, $active = false) {
		$select = $this->dataBase->SelectFromDB(__LINE__);

		if ($active)
			$select->addWhere("a.active='1'");
		
		$fmakeTypeTable = new fmakeTypeTable();
		$table = $fmakeTypeTable->getTable($type);
		if ($table){
			$table_join = " as a LEFT JOIN {$table} as b ON b.id = a.id LEFT JOIN {$this->table} as c ON a.parent = c.{$this->idField}";
		}
		if($where)
			$select->addWhere($where);
		$result = $select->addFild("COUNT(*)")->addFrom($this->table . $table_join)->addOrder($this->order, DESC)->addWhere('a.parent in (' . $parent . ')')->queryDB();
		return $result[0]["COUNT(*)"];
	}

	function getParent($parent) { // Берем родителя раздела

		$select = $this->dataBase->SelectFromDB(__LINE__);
		$parent = $select->addFrom($this->table)->addWhere("active='1'")->addWhere("{$this->idField}='$parent'")->addOrder($this->order)->queryDB();
		return $parent[0];
	}

	function getParents($parent) {

		$parents[] = $this->getParent($parent);
		if ($parents[sizeof($parents) - 1]['parent']) {
			$subparents = $this->getParents($parents[sizeof($parents) - 1]['parent']);
			if ($subparents) {
				$parents = array_merge($parents, $subparents);
			}
		}
		return $parents;
	}

function getBreadCrumbs($id)
     {
          $this -> setId($id);
          $breadCrumbs[] = $this ->getInfo();
          
          if($parents = $this -> getParents($breadCrumbs[0]['parent']) ){
               $breadCrumbs = array_merge( $breadCrumbs, $parents );
          }
         
          $str = "";
          $sizeI = sizeof($breadCrumbs);
          
          for( $i=$sizeI-1; $i >= 0; $i--){
               /*if( !$breadCrumbs[$i]['showlink'] ){
                    continue;
               }*/
          		if($breadCrumbs[$i]['caption']==''){
          			continue;
          		}
               $str .= "/".$breadCrumbs[$i]['redir'];
               $ans[] = array( "caption"=>$breadCrumbs[$i]['caption'], "link" => $str."/","redir" =>$breadCrumbs[$i]['redir'],"id"=>$breadCrumbs[$i]['id'] );
          }
          //printAr($ans);
          //array("caption"=>$modul->caption,"link"=>"/".$modul->redir."/");
          return $ans;
     }

	function getInfoFilds($fields) 
	{
		$select = $this->dataBase->SelectFromDB( __LINE__);
		if($fields)
			$select->addFild($fields);
		$arr = $select -> addFrom($this->table) -> addWhere("`".$this->idField."`='".$this->id."'") -> queryDB();	
		return $arr[0];
	}
	
	/**
	 * 
	 * добавление файла
	 * @param string $tempName
	 * @param string $name
	 */
	function addFile($tempName, $name) {
		$dirs = explode("/", $this->fileDirectory . '/' . $this->id);
		$dirname = ROOT . "/";

		foreach ($dirs as $dir) {
			$dirname = $dirname . $dir . "/";
			if (!is_dir($dirname))
				mkdir($dirname);
		}

		$wantermark = ROOT.'/images/wantermark2.png';
		
		$images = new imageMaker($name);
		$images->imagesData = $tempName;
		$images->resize(false,false, false, $dirname, '', false,"wmi|{$wantermark}|BL");
		$images->resize(false,false, false, $dirname, 'original_', false);
		$images->resize(379, 181, true, $dirname, '379_181_', false);
		$images->resize(100, 80, true, $dirname, '100_80_', false);
		$images->resize(200, 160, true, $dirname, '200_160_', false);
		$images->resize(80, 80, true, $dirname, '80_80_', false);
		$images->resize(73, 53, true, $dirname, '73_53_', false);
		$images->resize(144, 77, true, $dirname, '144_77_', false);
		$images->resize(112, 169, true, $dirname, '112_169_', false);
		$images->resize(232, 155, true, $dirname, '232_155_', false);
		$images->resize(406, false, true, $dirname, '406__', false,"wmi|{$wantermark}|BL");
		$images->resize(175, 116, true, $dirname, '175_116_', false);
		
		//wantermark
		//$images->wantermark_img($dirname.$name,ROOT."/images/wantermark2.png");
		//$images->wantermark_img($dirname."406__".$name,ROOT."/images/wantermark2.png");
		
		$this->addParam('picture', $name);
		$this->update();
	}

}