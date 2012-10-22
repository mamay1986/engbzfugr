<?php

class fmakePlace extends fmakeSiteModule {

	public function getScriptItemAdmin($id_content){
		$this->setId($id_content);
		$item = $this->getInfo();
		$script = "array_place = {";

		$fmakeTypeTable = new fmakeTypeTable();
		$absitem_dop = new fmakeTypeTable();
		$absitem_dop->table = $fmakeTypeTable->getTable(5);
		$absitem_dop->setId($item['id']);
		$item_dop = $absitem_dop->getInfo();
		
		/*if($dop_param){
			$script .= "'name':'{$item[caption]}','redir':'{$url_page}','addres':'{$item_dop[addres]}','addres_coord':'{$item_dop[addres_coord]}','image':'/{$this->fileDirectory}{$item[id]}/100_80_{$item[picture]}'";
		}else{*/
			$script .= "'name':'{$item[caption]}','redir':'{$url_page}','addres':'{$item_dop[addres]}','addres_coord':'{$item_dop[addres_coord]}','image':'/{$this->fileDirectory}{$item[id]}/100_80_{$item[picture]}'";
		//}

		$script .= "}";
		return $script;
		
	}

	public function getScriptCat($id_cat){
		$select = $this->dataBase->SelectFromDB( __LINE__);
		$fmakePlaceCat = new fmakePlace();
		$fmakePlaceCat->setId($id_cat);
		$urlCat = $fmakePlaceCat->getInfo();
		$select = $select -> addFrom($this->table) -> addWhere("`parent` ='{$id_cat}'") -> addWhere("`active` = '1'") -> queryDB();
		$script = "{";
		if($select)foreach($select as $key=>$item){
			$url_page = $fmakePlaceCat->getLinkPage($item[$fmakePlaceCat->idField]);
			
			$fmakeTypeTable = new fmakeTypeTable();
			$absitem_dop = new fmakeTypeTable();
			$absitem_dop->table = $fmakeTypeTable->getTable(5);
			$absitem_dop->setId($item['id']);
			$item_dop = $absitem_dop->getInfo();
			
			$script .= "{$key} : {'name':'{$item[caption]}','redir':'{$url_page}','addres':'{$item_dop[addres]}','addres_coord':'{$item_dop[addres_coord]}','image':'/{$this->fileDirectory}{$item[id]}/100_80_{$item[picture]}'},";
		}
		
		/*multi parent*/	
		$select = $this->dataBase->SelectFromDB( __LINE__);
		$fmakeSiteModuleMultiple = new fmakeSiteModule_multiple();
		$items_site_modul = $fmakeSiteModuleMultiple->ItemsParent($id_cat);
		if($items_site_modul){
			$multi_parent .="id in (";
			foreach($items_site_modul as $key=>$item_site_modul){
				if($key==0) $multi_parent .="{$item_site_modul['id_site_modul']}";
				else $multi_parent .=",{$item_site_modul['id_site_modul']}";
			}
			$multi_parent .=")";
			
			$script .= "'multi_parent': {";
			$select_multi_parent = $select -> addFrom($this->table) -> addWhere("{$multi_parent}") -> addWhere("`active` = '1'") -> queryDB();
			if($select_multi_parent)foreach($select_multi_parent as $key=>$item){
				$url_page = $fmakePlaceCat->getLinkPage($item[$fmakePlaceCat->idField]);
				
				$fmakeTypeTable = new fmakeTypeTable();
				$absitem_dop = new fmakeTypeTable();
				$absitem_dop->table = $fmakeTypeTable->getTable(5);
				$absitem_dop->setId($item['id']);
				$item_dop = $absitem_dop->getInfo();
				
				$script .= "{$key} : {'name':'{$item[caption]}','redir':'{$url_page}','addres':'{$item_dop[addres]}','addres_coord':'{$item_dop[addres_coord]}','image':'/{$this->fileDirectory}{$item[id]}/100_80_{$item[picture]}'},";
			}
			$script .= "},";
		}
		
		/*multi parent*/
		
		$script .= "},";
		return $script;
		
	}
	
	public function getScriptAll(){
		$all_cat = $this->getChilds(5,true);
		$script = "array_place = {";
		if($all_cat)foreach($all_cat as $key_cat=>$item_cat){
			$script .= "{$item_cat[id]} : {$this->getScriptCat($item_cat[id])}";
		}
		$script .= "};";
		return $script;
		
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
				
		/*multi parent*/		
		$fmakeSiteModuleMultiple = new fmakeSiteModule_multiple();
		$items_site_modul = $fmakeSiteModuleMultiple->ItemsParent($parent);
		if($items_site_modul){
			$multi_parent .=" OR a.id in (";
			foreach($items_site_modul as $key=>$item_site_modul){
				if($key==0) $multi_parent .="{$item_site_modul['id_site_modul']}";
				else $multi_parent .=",{$item_site_modul['id_site_modul']}";
			}
			$multi_parent .=")";
			//if($_GET['debug']) echo $multi_parent;
		}
		else{
			$multi_parent = '';
		}		
		/*multi parent*/	
		
		return $select->addFild("a.*,b.*,c.caption name_category")->addFrom($this->table . $table_join)->addWhere("( a.parent in ({$parent}) {$multi_parent} )")->addLimit((($page - 1) * $limit), $limit)->queryDB();
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
			
		/*multi parent*/		
		$fmakeSiteModuleMultiple = new fmakeSiteModule_multiple();
		$items_site_modul = $fmakeSiteModuleMultiple->ItemsParent($parent);
		if($items_site_modul){
			$multi_parent .=" OR a.id in (";
			foreach($items_site_modul as $key=>$item_site_modul){
				if($key==0) $multi_parent .="{$item_site_modul['id_site_modul']}";
				else $multi_parent .=",{$item_site_modul['id_site_modul']}";
			}
			$multi_parent .=")";
			//if($_GET['debug']) echo $multi_parent;
		}
		else{
			$multi_parent = '';
		}		
		/*multi parent*/
		
		$result = $select->addFild("COUNT(*)")->addFrom($this->table . $table_join)->addOrder($this->order, DESC)->addWhere("( a.parent in ({$parent}) {$multi_parent} )")->queryDB();
		return $result[0]["COUNT(*)"];
	}
	
}

?>
