<?php
class fmakeGallery_Image extends fmakeSiteModule implements fmakeSiteModule_ExtensionInterface {
		
	public $table = "gallery_images";
	public $imgFolder = "images/galleries/";
	public $order = "position";
	

	function getByPage($id_catalog ,$limit, $page, $active = false) {
		
		$select = $this->dataBase->SelectFromDB( __LINE__);
		if($active)
			$select -> addWhere("active='1'");
		return $select -> addFrom($this->table) ->addWhere("id_catalog = '{$id_catalog}'") -> addOrder($this->order, ASC) -> addLimit((($page-1)*$limit), $limit) -> queryDB();
	}
	
	function getByPageCount($id_page, $active = false) {
		
		$select = $this->dataBase->SelectFromDB( __LINE__);
		if($active)
			$select -> addWhere("active='1'");
			
		$fmakeGalleryNotice = new fmakeGallery();
		$fmakeGalleryNotice->table = $fmakeGalleryNotice->table_notice_galley;
		$fmakeGalleryNotice->idField = 'id_site_modul';
		$fmakeGalleryNotice->setId($id_page);
		$do_gallery = $fmakeGalleryNotice->getInfo();
		$id_gallery = $do_gallery['id_gallery'];
			
		$result = $select ->addFild("COUNT(*)")-> addFrom($this->table) ->addWhere("id_catalog = '{$id_gallery}'") -> queryDB();
		return $result[0]["COUNT(*)"];
	}
	
	function editImageParams($id_catalog,$name_image,$fild_image, $active = false) {
		
		$update = $this->dataBase->UpdateDB( __LINE__);
		if($active)
			$update -> addWhere("active='1'");
		return $update ->addFild('title', $fild_image)-> addTable($this->table) ->addWhere("id_catalog = '{$id_catalog}'") ->addWhere("image = '{$name_image}'") -> queryDB();
	}
	function editImageSort($id_catalog,$name_image,$position) {
		
		$update = $this->dataBase->UpdateDB( __LINE__);
		
		return $update ->addFild('position', $position)-> addTable($this->table) ->addWhere("id_catalog = '{$id_catalog}'") ->addWhere("image = '{$name_image}'") -> queryDB();
	}	
	function getImageParams($id_catalog,$name_image, $active = false) {
		
		$select = $this->dataBase->SelectFromDB( __LINE__);
		if($active)
			$select -> addWhere("active='1'");
		$image_params = $select ->addFrom($this->table) ->addWhere("id_catalog = '{$id_catalog}'") ->addWhere("image = '{$name_image}'") -> queryDB();
		return $image_params[0];
	}	
	
	function getFullPhotoByGallery($id_gallery) {
		$select = $this->dataBase->SelectFromDB( __LINE__);
		return $select -> addFrom($this->table) ->addWhere("id_catalog = '{$id_gallery}'")->addOrder($this->order) -> queryDB();
	}
	
	function getFullPhoto($id_page) {
		$select = $this->dataBase->SelectFromDB( __LINE__);
		
		$fmakeGalleryNotice = new fmakeGallery();
		$fmakeGalleryNotice->table = $fmakeGalleryNotice->table_notice_galley;
		$fmakeGalleryNotice->idField = 'id_site_modul';
		$fmakeGalleryNotice->setId($id_page);
		$do_gallery = $fmakeGalleryNotice->getInfo();
		$id_gallery = $do_gallery['id_gallery'];
		//$id_gallery = $id_page; 
		return $select -> addFrom($this->table) ->addWhere("id_catalog = '{$id_gallery}'")->addOrder($this->order) -> queryDB();
	}
	
	function getCountPhoto($id_page) {
		$select = $this->dataBase->SelectFromDB( __LINE__);
		
		$fmakeGalleryNotice = new fmakeGallery();
		$fmakeGalleryNotice->table = $fmakeGalleryNotice->table_notice_galley;
		$fmakeGalleryNotice->idField = 'id_site_modul';
		$fmakeGalleryNotice->setId($id_page);
		$do_gallery = $fmakeGalleryNotice->getInfo();
		$id_gallery = $do_gallery['id_gallery'];
		
		if($id_gallery) $result = $select->addFild("COUNT(*)")->addFrom($this->table)->addWhere("id_catalog = '{$id_gallery}'")->queryDB();
		return $result[0]["COUNT(*)"];
	}
	
	function getOnePhoto($id_gallery) {
		$select = $this->dataBase->SelectFromDB( __LINE__);
				
		$photo = $select -> addFrom($this->table) ->addWhere("id_catalog = '{$id_gallery}'")->addOrder($this->order) ->addLimit(0,1)-> queryDB();
		return $photo[0];
	}
	
	function isPhoto($photo_name,$id_gallery) {
		$select = $this->dataBase->SelectFromDB( __LINE__);
		$image = $select ->addFild("COUNT(*)")-> addFrom($this->table) ->addWhere("id_catalog = '{$id_gallery}' AND image = '{$photo_name}' ") -> queryDB();
		return $image[0]["COUNT(*)"];
	}

	function getPhoto($photo_name,$id_gallery) {
		$select = $this->dataBase->SelectFromDB( __LINE__);
		$image = $select-> addFrom($this->table) ->addWhere("id_catalog = '{$id_gallery}' AND image = '{$photo_name}' ") -> queryDB();
		return $image[0];
	}
	
	function addPreviewFoto($file,$id_gal){
		
		$dirs = explode("/", $this->imgFolder.$id_gal);
		$dirname = ROOT."/";
		
		$wantermark = ROOT.'/images/wantermark2.png';
		
		foreach($dirs as $dir){
			$dirname = $dirname.$dir."/";
			if(!is_dir($dirname)) mkdir($dirname);	
		}
		if(!is_dir($dirname."/thumbs/")) mkdir($dirname."/thumbs/");
		//echo $dirname;
		$images = new imageMaker($file['name']);
		$images->imagesData = $file['tmp_name'];
		copy($file['tmp_name'],$dirname.$file['name']);
		//$images->resize(800,false,false,$dirname.'/','',false);
		$images->resize(140,111,true,$dirname.'/thumbs/','',false);
		$images->resize(175,116,true,$dirname.'/thumbs/','175_116_',false);
		$images->resize(1024,false,false,$dirname.'/','1024_',false,"wmi|{$wantermark}|BL");
		//$images->resize(168,173,true,$dirname.'/thumbs/','mini_',false);
		
		//wantermark
		//$images->wantermark_img($dirname."1024_".$file['name'],ROOT."/images/wantermark2.png"); 
				
	}
	
	function deleteImage($id_gall,$name_image){
		//echo('qq');
		$delete = $this->dataBase->DeleteFromDB(__LINE__);
		$delete-> addTable($this->table)->addWhere("id_catalog = '{$id_gall}'")->addWhere("image = '{$name_image}'")->queryDB();
		//echo('wwwww');
		if(file_exists(ROOT."/".$this->imgFolder.$id_gall."/thumbs/".$name_image))
			unlink(ROOT."/".$this->imgFolder.$id_gall."/thumbs/".$name_image);
			
		if(file_exists(ROOT."/".$this->imgFolder.$id_gall."/thumbs/175_116_".$name_image))
			unlink(ROOT."/".$this->imgFolder.$id_gall."/thumbs/175_116_".$name_image);
		
		if(file_exists(ROOT."/".$this->imgFolder.$id_gall."/1024_".$name_image))
			unlink(ROOT."/".$this->imgFolder.$id_gall."/1024_".$name_image);

		if(file_exists(ROOT."/".$this->imgFolder.$id_gall."/".$name_image))
			unlink(ROOT."/".$this->imgFolder.$id_gall."/".$name_image);	
	}
	
	function renameGallery($id_gallery_new,$id_gallery_old){
		$update = $this->dataBase->UpdateDB(__LINE__);
		$update->addTable($this->table)->addFild('id_catalog', $id_gallery_new)->addWhere('id_catalog = '.$id_gallery_old)->queryDB();
		
		
		$dirname_old = ROOT."/".$this->imgFolder.$id_gallery_old;
		$dirname_new = ROOT."/".$this->imgFolder.$id_gallery_new;
		
		if(is_dir($dirname_old)){
			rename($dirname_old, $dirname_new);
		}
	}
	
	function delete_directory($dirname) {
		if (is_dir($dirname))
			$dir_handle = opendir($dirname);
		if (!$dir_handle)
			return false;
		while($file = readdir($dir_handle)) {
			if ($file != "." && $file != "..") {
				if (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
				else
					$this->delete_directory($dirname.'/'.$file); 		
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	}
	
	function deleteImagesTmp($id_gallery_tmp){
		$delete = $this->dataBase->DeleteFromDB(__LINE__);
		$delete-> addTable($this->table)->addWhere("id_catalog = '{$id_gallery_tmp}'")->queryDB();
		
		$dirname_old = ROOT."/".$this->imgFolder.$id_gallery_tmp;
		if(is_dir($dirname_old)){
			$this->delete_directory($dirname_old);
		}
	}
} 