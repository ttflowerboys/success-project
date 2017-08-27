<?php
namespace Home\Controller;
use Think\Controller;
class AgentController extends CommandController {
    public function edit(){
    	$uid = trim(I('get.id'));
        if (!is_numeric($uid) || empty($uid)) {
            $this->error('操作有误，请返回！');
        }
        $rs = M('agent')->where(array('id'=>$uid))->find();
        $this->assign('rs',$rs);
        $this->display();
    }

}