<?php
namespace Common\Behavior;
use Think\Behavior;
/**
 * 语言检测 并自动加载语言包
 */
class AdminDefaultLangBehavior extends Behavior{

    // 行为扩展的执行入口必须是run
    public function run(&$params){
        // 检测语言
        $this->loadLang();
    }

    /**
     * 语言检查
     * 检查浏览器支持语言，并自动加载语言包
     * @access private
     * @return void
     */
    private function loadLang() {
        
        $default_lang = C('DEFAULT_LANG');
        $langSet = C('ADMIN_LANG_SWITCH_ON',null,false)?LANG_SET:$default_lang;
        // 读取框架语言包
        $file   =   THINK_PATH.'Lang/'.$langSet.'.php';
        if(!C('ADMIN_LANG_SWITCH_ON',null,false) && is_file($file))
            L(include $file);

        // 读取应用公共语言包
        $file   =  LANG_PATH.$langSet.'.php';
        if(is_file($file))
            L(include $file);
        
        // 读取模块语言包
        $file   =   MODULE_PATH.'Lang/'.$langSet.'.php';
        if(is_file($file))
            L(include $file);

        // 读取当前控制器语言包
        $file   =   MODULE_PATH.'Lang/'.$langSet.'/'.strtolower(CONTROLLER_NAME).'.php';
        if (is_file($file))
            L(include $file);
    }
}
