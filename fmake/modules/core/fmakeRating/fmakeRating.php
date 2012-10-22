<?php

	class fmakeRating extends fmakeCore{
		
		public $table = "reiting";
		public $lyambda = 0.001;
		
		function showRating($id_content){
			$is_active = $this->isRatingCookie($id_content);
			if($is_active) $str_active = 1;
			else  $str_active = 0;
			$rating_item = $this->getRating($id_content);
			$rating = round($rating_item[rating]);
			
			$html = "<div class=\"stars\" disabled-star=\"{$str_active}\" problem-id=\"{$id_content}\" problem-rating=\"{$rating}\" id=\"stars{$id_content}\"></div>
						<script>
						$(function(){	
							$('#stars{$id_content}').ratings(5,{$rating},{$str_active}).bind('ratingchanged', function(event, data) {
								addStarRating({$id_content},data.rating);
							});
						});
						</script>";
			return $html;
		}
		
		function getRating($id_content){
			$select = $this->dataBase->SelectFromDB(__LINE__);
			$result = $select->addFrom($this->table)->addWhere("`id` = '{$id_content}'")->queryDB();
			return $result[0];
		}
		
		function addRatingCookie($id_content){
			if($_COOKIE['rating'][$id_content]!=1){
				setcookie('rating['.$id_content.']',1,time()+3600*24*31,'/');
			}
		}
		
		function isRatingCookie($id_content){
			if($_COOKIE['rating'][$id_content]==1){
				return true;
			}
			else{
				return false;
			}
		}
		
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
			$this->id = $insert	-> getInsertId();
		}
	}
?>