<?php
namespace Asset\Controller;
use Think\Controller;
class UeditorController extends Controller {
	public function uploadimg(){
		
		
		
		//上传处理类
		$config=array(
				'rootPath' => './'. C("UPLOADPATH"),
				'savePath' => "ueditor/",
				'maxSize' => 11048576,
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    false,
		);
		$upload = new \Think\Upload($config);// 
		
		$file = $title = $oriName = $state ='0';

		$info=$upload->upload();
		
		//开始上传
		if ($info) {
			
			
			//上传成功
			$title = $oriName = $info['upfile']['name'];
			
			$state = 'SUCCESS';
			$file = C("TMPL_PARSE_STRING.__UPLOAD__")."ueditor/".$info['upfile']['savename'];
			if(strpos($file, "https")===0 || strpos($file, "http")===0){
				
			}else{//local
				$host=(is_ssl() ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'];
				$file=$host.$file;
			}
		} else {
			$state = $upload->getError();
		}
		$response= "{'url':'" .$file . "','title':'" . $title . "','original':'" . $oriName . "','state':'" . $state . "'}";
		exit($response);
	}
	
	public function imageManager(){
		error_reporting(E_ERROR|E_WARNING);
		$path = 'upload'; //最好使用缩略图地址，否则当网速慢时可能会造成严重的延时
		$action = htmlspecialchars($_POST["action"]);
		if($action=="get"){
			$files = $this->getfiles($path);
			if(!$files)return;
			$str = "";
			foreach ($files as $file) {
				$str .= $file."ue_separate_ue";
			}
			echo $str;
		}
	}
	
	//imageManager()用的到
	private function getfiles(){
		if (!is_dir($path)) return;
		
		$handle = opendir($path);
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$path2 = $path . '/' . $file;
				if (is_dir($path2)) {
					getfiles($path2, $files);
				} else {
					if (preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)) {
						$files[] = $path2;
					}
				}
			}
		}return $files;
	}
	
	
	public function get_remote_image(){
		$uri = htmlspecialchars( $_POST[ 'upfile' ] );
		$uri = str_replace( "&amp;" , "&" , $uri );
		//远程抓取图片配置
		$config = array(
				"savePath" => './'. C("UPLOADPATH")."ueditor/",            //保存路径
				"allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" ) , //文件允许格式
				"maxSize" => 3000                    //文件大小限制，单位KB
		);
		//忽略抓取时间限制
		set_time_limit( 0 );
		//ue_separate_ue  ue用于传递数据分割符号
		$imgUrls = explode( "ue_separate_ue" , $uri );
		$tmpNames = array();
		foreach ( $imgUrls as $imgUrl ) {
			//http开头验证
			if(strpos($imgUrl,"http")!==0){
				array_push( $tmpNames , "error" );
				continue;
			}
			//获取请求头
			if(!IS_SAE){//SAE下无效
				$heads = get_headers( $imgUrl );
				//死链检测
				if ( !( stristr( $heads[ 0 ] , "200" ) && stristr( $heads[ 0 ] , "OK" ) ) ) {
					array_push( $tmpNames , "error" );
					continue;
				}
			}
			
		
			//格式验证(扩展名验证和Content-Type验证)
			$fileType = strtolower( strrchr( $imgUrl , '.' ) );
			if ( !in_array( $fileType , $config[ 'allowFiles' ] ) || stristr( $heads[ 'Content-Type' ] , "image" ) ) {
				array_push( $tmpNames , "error" );
				continue;
			}
		
			//打开输出缓冲区并获取远程图片
			ob_start();
			$context = stream_context_create(
					array (
							'http' => array (
									'follow_location' => false // don't follow redirects
							)
					)
			);
			//请确保php.ini中的fopen wrappers已经激活
			readfile( $imgUrl,false,$context);
			$img = ob_get_contents();
			ob_end_clean();
		
			//大小验证
			$uriSize = strlen( $img ); //得到图片大小
			$allowSize = 1024 * $config[ 'maxSize' ];
			if ( $uriSize > $allowSize ) {
				array_push( $tmpNames , "error" );
				continue;
			}
			//创建保存位置
			$savePath = $config[ 'savePath' ];
			if ( !file_exists( $savePath ) ) {
				mkdir( "$savePath" , 0777 );
			}
			$file=date("Ymdhis").uniqid() . strrchr( $imgUrl , '.' );
			//写入文件
			$tmpName = $savePath .$file ;
			$file = C("TMPL_PARSE_STRING.__UPLOAD__")."ueditor/".$file;
			if(strpos($file, "https")===0 || strpos($file, "http")===0){
			
			}else{//local
				$host=(is_ssl() ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'];
				$file=$host.$file;
			}
			if(sp_file_write($tmpName,$img)){
				array_push( $tmpNames ,  $file );
			}else{
				array_push( $tmpNames , "error" );
			}
		}
		/**
		 * 返回数据格式
		 * {
		 *   'url'   : '新地址一ue_separate_ue新地址二ue_separate_ue新地址三',
		 *   'srcUrl': '原始地址一ue_separate_ue原始地址二ue_separate_ue原始地址三'，
		 *   'tip'   : '状态提示'
		 * }
		 */
		$response= "{'url':'" . implode( "ue_separate_ue" , $tmpNames ) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "'}";
		exit($response);
	}
}