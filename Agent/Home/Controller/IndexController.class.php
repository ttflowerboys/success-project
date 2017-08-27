<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommandController {
    public function index(){
    	$rs = M('agent')->where(array('id'=>session('AgentId'),'phone'=>session('AgentPhone')))->find();
    	$this->assign('rs',$rs);
        $this->display();
    }


    public function agent(){
        $this->display();
    }

    public function add(){
        $this->display();
    }

    public function count(){
    	$this->display();
    }

    public function coin(){
    	$this->display();
    }
}