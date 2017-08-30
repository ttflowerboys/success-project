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

    public function editDo(){
        if (!IS_POST) {E('请求页面不在');}
        $uid = trim(I('post.id'));
        if (!is_numeric($uid) || empty($uid)) {
            $this->error('操作有误，请返回！');
        }
        $password = trim(I('post.password'));

        $agent = M('agent');
        if (!empty($password)) {
            $data['password'] = md5($password);
            $rs = $agent->where(array('id'=>$uid))->find();
            if (!$rs) {$this->error('代理商不存在，请返回！'); }
            if ($agent->where(array('id'=>$rs['id']))->save($data)===fasle) {
                $this->error('操作失败！');
            }
            $this->success('操作成功！');
        }        
    }

}