<?php

/**
 * 附件上传
 */
namespace Asset\Action;
use Common\Action\AdminbaseAction;
class AssetAction extends AdminbaseAction {

    //附件存在物理地址
    public $path = "";
    public $isadmin = false;

    function _initialize() {
        //默认图片类型
        $this->imgext = array('jpg', 'gif', 'png', 'bmp', 'jpeg');
        //当前登陆用户名 0 表示游客
        //附件目录强制/d/file/ 后台设置的附件目录，只对网络地址有效
        $this->path = C("UPLOADFILEPATH");
    }

    /**
     * swfupload 上传 
     * 通过swf上传成功以后回调处理时会调用swfupload_json方法增加cookies！
     */
    public function swfupload() {
        if (IS_POST) {
			
            //filename,filesize,filepath,uploadtime,status,meta,suffix

            //上传处理类
            $config=array(
            		'rootPath' => './',
            		'savePath' => './'. C("UPLOADPATH"),
            		'maxSize' => 11048576,
            		'saveName'   =>    array('uniqid',''),
            		'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
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
            //1,允许上传的文件类型,是否允许从已上传中选择,图片高度,图片高度,是否添加水印1是
            $args = I("get.args");
            $authkey = I("get.authkey");
            $module = I("get.module");
            if($this->module_list[ucwords($module)]){
                $this->module = strtolower($module);
            }
            
            $info = explode(",", $args);
            $this->catid = I("get.catid");
            $att_not_used = cookie('att_json');
            if (empty($att_not_used))
                $tab_status = ' class="on"';
            if (!empty($att_not_used))
                $div_status = ' hidden';
           
            //上传格式显示
            $this->assign("file_types", implode(",", explode("|", $info[1])));
            $this->assign("file_size_limit", $this->isadmin ? CONFIG_UPLOADMAXSIZE : CONFIG_QTUPLOADMAXSIZE);
            $this->assign("file_upload_limit", (int) $info[0]);
            //$this->assign("att", $att);
            $this->assign("tab_status", $tab_status);
            $this->assign("div_status", $div_status);
            $this->assign("att_not_used", $att_not_used);
            $this->assign("watermark_enable", (int) $info[5]); //是否添加水印 
            $group = defined('MODULE_NAME') ? MODULE_NAME . '/' : '';
            $this->display();
        }
    }

}

?>
