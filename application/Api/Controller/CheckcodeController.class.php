<?php

/**
 * 验证码处理
 */
namespace Api\Controller;
use Think\Controller;
class CheckcodeController extends Controller {

    public function index() {
        import("Checkcode");
        $checkcode = new \Checkcode();
        if (isset($_GET['code_len']) && intval($_GET['code_len']))
            $checkcode->code_len = intval($_GET['code_len']);
        if ($checkcode->code_len > 8 || $checkcode->code_len < 2) {
            $checkcode->code_len = 4;
        }
        //设置验证码字符库
        if(isset($_GET['charset'])){
        	$checkcode->charset = trim($_GET['charset']);
        }
        //强制验证码不得小于4位
        if($checkcode->code_len < 4){
            $checkcode->code_len = 4;
        }
        if (isset($_GET['font_size']) && intval($_GET['font_size']))
            $checkcode->font_size = intval($_GET['font_size']);
        if (isset($_GET['width']) && intval($_GET['width']))
            $checkcode->width = intval($_GET['width']);
        if ($checkcode->width <= 0) {
            $checkcode->width = 130;
        }
        if (isset($_GET['height']) && intval($_GET['height']))
            $checkcode->height = intval($_GET['height']);
        if ($checkcode->height <= 0) {
            $checkcode->height = 50;
        }
        if (isset($_GET['font_color']) && trim(urldecode($_GET['font_color'])) && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($_GET['font_color']))))
            $checkcode->font_color = trim(urldecode($_GET['font_color']));
        if (isset($_GET['background']) && trim(urldecode($_GET['background'])) && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($_GET['background']))))
            $checkcode->background = trim(urldecode($_GET['background']));
        $checkcode->doimage();
        
        //验证码类型
        $type = I("get.type");
        $type = $type?strtolower($type):"verify";
        $verify = session("_verify_");
        if(empty($verify)){
            $verify = array();
        }
        $verify[$type] = $checkcode->get_code();
        session("_verify_",$verify);
    }

}

?>