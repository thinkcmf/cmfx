<?php

/**
 * 
 */
namespace User\Action;
use Common\Action\HomeBaseAction;
class PublicAction extends HomeBaseAction {
    
	function avatar(){
		
		$users_model=M("Users");
		$id=I("get.id",0,"intval");
		
		$find_user=$users_model->field('avatar')->where(array("id"=>$id))->find();
		
		$avatar=$find_user['avatar'];
		
		$image = new \Think\Image();
		$should_show_default=false;
		
		if(empty($avatar)){
			$should_show_default=true;
		}else{
			if(strpos($avatar,"http")===0){
				header("Location: $avatar");exit();
			}else{
				$avatar_dir=C("UPLOADPATH")."avatar/";
				$avatar=$avatar_dir.$avatar;
				if(file_exists($avatar)){
					$image->open($avatar);
					$mime= $image->mime();
					header("Content-type: $mime");
					$image->save(null);
				}else{
					$should_show_default=true;
				
				}
			}
			
			
		}
		
		if($should_show_default){
			$image->open("statics/images/headicon.png");
			$mime= $image->mime();
			header("Content-type: $mime");
			$image->save(null);
		}
		exit();
		
	}
    

    
}
?>
