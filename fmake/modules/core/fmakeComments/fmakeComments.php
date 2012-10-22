<?php
class fmakeComments extends fmakeCore{
		
	public $table = "comments";
	public $modul = false;

	protected $extensions; 	
	public $order = "date";

	function getComments($id) {
		$select = $this->dataBase->SelectFromDB(__LINE__);
		$modul = $this->modul;
		if(!$id or !$modul)
			return false;
		return $select->addFrom($this->table)->addWhere("`id_content` = '{$id}'")->addWhere("`modul` = '{$modul}'")->addWhere("active = '1'")->queryDB();
	}
	
	function getByPage($id = false,$limit = 20, $page = 1, $active = false,$join_table_main = false) {
		
		$select = $this->dataBase->SelectFromDB( __LINE__);
		
		$modul = $this->modul;
		if($active)
			$select -> addWhere("a.`active`='1'");
		if($id)
			$select -> addWhere("a.`id_content` = '{$id}'");
		if($modul)
			$select -> addWhere("a.`modul` = '{$modul}'");
		
		if($join_table_main){
			$fmakeSiteModule = new fmakeSiteModule();
			$table = $fmakeSiteModule->table;
			$table_join = " LEFT JOIN {$table} as b ON a.id_content = b.id ";
			$select ->addFild("a.*,b.caption page_caption,b.id page_id");
		}	
		
		return $select -> addFrom($this->table." as a".$table_join) -> addOrder($this->order,DESC) -> addLimit((($page-1)*$limit), $limit) -> queryDB();
	}
	
	function getByPageCount($id = false,$active = false) {
		
		$select = $this->dataBase->SelectFromDB( __LINE__);
		
		$modul = $this->modul;
		if($active)
			$select -> addWhere("active='1'");
		if($id)
			$select -> addWhere("`id_content` = '{$id}'");
		if($modul)
			$select -> addWhere("`modul` = '{$modul}'");
		$result = $select ->addFild("COUNT(*)")-> addFrom($this->table)-> queryDB();
		return $result[0]["COUNT(*)"];
	}
	
	function status_active($active = false){
		if($active) $this->addParam('active','1');
		else $this->addParam('active','0');
		$this->update();
	}
}