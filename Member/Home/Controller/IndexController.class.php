<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommandController {
    public function index(){
        // $rs = M('member')->where(array('memberId' => session('memberId'),'memberUsername' => session('MemberUsername')))->find();
        // $this->assign('rs',$rs);
        //$this->display();
        $this->show('aa');
    }
}