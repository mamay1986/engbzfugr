<?php

class fmakeSiteModule_relation extends fmakeCore {

	public $table = "site_modul_relation";
	public $setName = "";

	public $order = "";

	function setGeneralPageRelation($from,$to){
		$fmakeSiteModul = new fmakeSiteModule();
		$fmakeSiteModul->setId($to);
		$info = $fmakeSiteModul->getInfo();
		$id_parent = $info['parent'];
		$id_page = $from;
		/*удаляем все записи с ребенком $id_page*/
		$delete = $this->dataBase->DeleteFromDB(__LINE__);
		$delete->addTable($this->table)->addWhere("`id_child` = '{$id_page}'")->queryDB();
		
		/*привязываем $id_page к новому родителю $id_parent*/
		$this->addPageRelation($id_parent, $id_page,1);
		
		$items = $this->getParents($id_parent);
		if($items)foreach ($items as $key=>$item){
			$this->addPageRelation($item['id_parent'], $id_page,$item['level']+1);
		}
	}
	
	function setPageRelation($id_parent,$id_page){
		/*удаляем все записи с ребенком $id_page*/
		$delete = $this->dataBase->DeleteFromDB(__LINE__);
		$delete->addTable($this->table)->addWhere("`id_child` = '{$id_page}'")->queryDB();
		
		/*привязываем $id_page к новому родителю $id_parent*/
		$this->addPageRelation($id_parent, $id_page,1);
		
		$items = $this->getParents($id_parent);
		if($items)foreach ($items as $key=>$item){
			$this->addPageRelation($item['id_parent'], $id_page,$item['level']+1);
		}
		
		$fmakeSiteModule = new fmakeSiteModule();
		$fmakeSiteModule->setId($id_page);
		$fmakeSiteModule->addParam('full_url',$fmakeSiteModule->getLinkPage($id_page));
		$fmakeSiteModule->update();
	}
	
	function addPageRelation($id_parent,$id_page,$level = 1){
		$this->addParam('id_parent',$id_parent);
		$this->addParam('id_child', $id_page);
		$this->addParam('level',$level);
		$this->newItem();
		//echo $id_parent." ".$id_page." ".$level."<br/>";
		$fmakeSiteModul = new fmakeSiteModule();
		$items = $fmakeSiteModul->getChilds($id_page);
		if($items)foreach($items as $key=>$item){
			/*удаляем все записи с ребенком $id_page*/
			$delete = $this->dataBase->DeleteFromDB(__LINE__);
			$delete->addTable($this->table)->addWhere("`id_parent` = '{$id_parent}'")->addWhere("`id_child` = '{$item[$fmakeSiteModul->idField]}'")->queryDB();
			$this->addPageRelation($id_parent,$item[$fmakeSiteModul->idField],$level+1);
		}
	}
	
	function getParents($id_page,$order = false){
		$select = $this->dataBase->SelectFromDB(__LINE__);
		
		$fmakeSiteModul = new fmakeSiteModule();
		
		if($order)
			$select->addOrder($order,DESC);
		return $select->addFild("a.*,b.redir redir_p,c.redir redir_c")->addFrom($this->table." as a LEFT JOIN {$fmakeSiteModul->table} as b ON b.id = a.id_parent LEFT JOIN {$fmakeSiteModul->table} as c ON c.id = a.id_child")->addWhere("`id_child` = '{$id_page}'")->queryDB();
	}
	
	/**
	 * 
	 * Создание нового объекта, с использованием массива params, c учетов поля position
	 */
	function newItem(){
		$insert = $this->dataBase->InsertInToDB(__LINE__);	
			
		$insert	-> addTable($this->table);
		$this->getFilds();
		
		if($this->filds){
			foreach($this->filds as $fild){
				if(!isset($this->params[$fild])) continue; 
				$insert -> addFild("`".$fild."`", $this->params[$fild]);
			}
			
		}
		$insert->queryDB();
	}

}