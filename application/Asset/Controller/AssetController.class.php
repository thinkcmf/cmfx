<?php

/**
 * 附件上传
 */
namespace Asset\Controller;
use Common\Controller\AdminbaseController;
class AssetController extends AdminbaseController {


    function _initialize() {
        //默认图片类型
        $this->imgext = array('jpg', 'gif', 'png', 'bmp', 'jpeg','zip');
        //当前登陆用户名 0 表示游客
    }

    /**
     * swfupload 上传 
     */
    public function swfupload() {
        if (IS_POST) {
			
            //上传处理类
            $config=array(
            		'rootPath' => './'.C("UPLOADPATH"),
            		'savePath' => './',
            		'maxSize' => 11048576,
            		'saveName'   =>    array('uniqid',''),
            		'exts'       =>    array('jpg', 'gif', 'png', 'jpeg',"txt",'zip'),
            		'autoSub'    =>    false,
            );
			$upload = new \Think\Upload($config);// 
			$info=$upload->upload();
            //开始上传
            if ($info) {
                //上传成功
                //写入附件数据库信息
                $first=array_shift($info);
				echo "1," . C("TMPL_PARSE_STRING.__UPLOAD__").$first['savename'].",".'1,'.$first['name'];
				exit;
            } else {
                //上传失败，返回错误
                exit("0," . $upload->getError());
            }
        } else {
            $this->display();
        }
    }

}

?>
