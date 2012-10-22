<?php
 
 class VKapi {
     public $app_secret = 'AZIJ8H9lZkPvSduBD6yr'; 
     private $app_id = '3112820';

    
     public function __construct($api_id='',$tocken='') {
         $this->api_id = $api_id;
		 $this->tocken = $tocken;
     }
    
	public function thisAppId() {
		return $this->app_id;
	}
	
	public function login($code) {
        $url = 'https://oauth.vkontakte.ru/access_token?client_id='.$this->app_id.'&client_secret='.$this->app_secret.'&code='.$code;
		$curl = new cURL();
		$curl -> init();
		$curl -> get($url);
		$result = $curl -> data();
		$res = json_decode($result,true);
		if($res['user_id']){
			$userObj = new fmakeSiteUser();
			$user = $userObj->getByIdVk($res['user_id']);
			if(!$user){
				$userObj->addParam("id_vk", $res['user_id']);
				$userObj->addParam("active", 1);
				$userObj->newItem();
				$user = $userObj->getInfo();
			}
			$userObj->id = $user[$userObj->idField];
			$userObj->login = $user['name'];
			$userObj->role = $user['role'];
			$userObj->status = true;
			$userObj -> save();
			
			$fmakePlaylist = new fmakePlaylist();
			$playlist = $fmakePlaylist->getPlaylistCookie();
			$fmakePlaylist->savePlaylistLoginCookieUser($playlist, $user[$userObj->idField]);
			setcookie('playlist', '', strtotime("+1 month",time()), '/');
			
			return true;
		}
		return false;
    }
    
    
	public function desktop_api($method, $data='',$proxy = false) {

		if ($data) {
			foreach ($data as $k => $v) {
				$str .= ''.$k.'='.$v.'&';
			}
		}
		//ksort($postdata, SORT_STRING);
		$str .='access_token='.$this->tocken; 
		$url = 'https://api.vkontakte.ru/method/'.$method.'?'.$str;
		$curl = new cURL();
		$curl -> init();
		if($proxy){
			/*выбираем из таблицы прокси*/
			$fmakeproxy = new fmakeProxy();
			$proxy = $fmakeproxy->getProxy();
			/*выбираем из таблицы прокси*/
			if($proxy) $curl->set_opt(CURLOPT_PROXY,$proxy['proxy']);
		}
		$curl -> get($url);
		$result = $curl -> data();
		$res = json_decode($result,true);
		return $res;
	}  
	
	public function isUserTokenVK($tocken,$user_id_vk){
		$this->tocken = $tocken;
		$message = urlencode($post['message']);
		$array_param = array('uids'=>$user_id_vk);
		
		$send_vk_wall_messages = $this->desktop_api('users.get', $array_param);
		
		$result = $send_vk_wall_messages;
		
		return $result;
	}
	
	public function SendMessageWall($id_user,$id_soc_set,$textpage,$link){
		$SocialUser = new fmakeSiteUser();
		$params_user_vk = $SocialUser->getUserSocialParam($id_user,$id_soc_set);
		//$vk = new fmakeVkapi($api_id,$params_user_vk['tocken']);
		$this->tocken = $params_user_vk['tocken'];
		$message = urlencode($textpage['text_like']);
		$array_param = array('owner_id'=>$params_user_vk['uid'],'message'=>$message);
		$image = ROOT."/images/image_textlike/{$textpage['id_text_like']}/thumbs/{$textpage['image']}";
		//$image = ROOT."/images/image_textlike/{$textpage['id_text_like']}/{$textpage['image']}";
		if(file_exists($image)){
			$photo_vk_wall_messages = $this->desktop_api('photos.getWallUploadServer', array('uid'=>$params_user_vk['uid']));

			$resp = $photo_vk_wall_messages->response;

			$ch = curl_init($resp->upload_url);  
			curl_setopt($ch, CURLOPT_POST, 1);
			$filename = '@'.$image;
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo'=>"".$filename));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result);
			//printAr($result);
			$photo_vk_upload = $this->desktop_api('photos.saveWallPhoto', array('server'=>$result->server,'photo'=>$result->photo,'hash'=>$result->hash));
			//printAr($photo_vk_upload);
			$photo_params = $photo_vk_upload->response[0];
			$array_param['attachments'] = $photo_params->id.',';
		}
		if($link) $array_param['attachments'] .= $link;
		
		$send_vk_wall_messages = $this->desktop_api('wall.post', $array_param);
		return $send_vk_wall_messages;
	}
	public function SendWallVK($post,$tocken,$user_id_vk){
		$this->tocken = $tocken;
		$message = urlencode($post['message']);
		$array_param = array('owner_id'=>$user_id_vk,'message'=>$message);
		$array_param['attachments'] = $post['link'];
		if($post['captcha']){
			foreach($post['captcha'] as $key=>$item){
				$array_param[$key] = $item;
			}
		}
		
		$send_vk_wall_messages = $this->desktop_api('wall.post', $array_param);
		return $send_vk_wall_messages;
	}
	public function SendWallVKGroup($post,$tocken,$group_id_vk){
		$this->tocken = $tocken;
		$message = urlencode($post['message']);
		$array_param = array('owner_id'=>'-'.$group_id_vk,'message'=>$message,'from_group'=>'1','signed'=>'1');
		$array_param['attachments'] = $post['link'];
		if($post['captcha']){
			foreach($post['captcha'] as $key=>$item){
				$array_param[$key] = $item;
			}
		}
		
		$send_vk_wall_messages = $this->desktop_api('wall.post', $array_param,true);
		return $send_vk_wall_messages;
	}
 }
 ?>