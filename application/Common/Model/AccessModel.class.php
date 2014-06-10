<?php

/* * 
 * 后台权限
 */
namespace Common\Model;
use Common\Model\CommonModel;
class AccessModel extends CommonModel {

    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('role_id', 'require', '角色不能为空！', 1, 'regex', 3),
        array('g', 'require', '项目不能为空！', 1, 'regex', 3),
        array('m', 'require', '模块不能为空！', 1, 'regex', 3),
        array('a', 'require', '方法不能为空！', 1, 'regex', 3),
    );

    /**
     * 角色授权
     * @param type $roleid
     * @param type $addauthorize 是一个数组 array(0=>array(...))
     * @return boolean
     */
    public function rbac_authorize($roleid, $addauthorize) {
        if(!$roleid || !$addauthorize || !is_array($addauthorize)){
            return false;
        }
        //删除旧的权限
        $this->where(array("role_id" => $roleid))->delete();
        return $this->addAll($addauthorize);
    }
    
    protected function _before_write(&$data) {
    	parent::_before_write($data);
    }

}

?>