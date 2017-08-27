<?php
namespace Home\Controller;
use Think\Controller;
class CommandController extends Controller {
	public function _initialize(){
		$agent = M('agent')->where(array('id' => session('AgentId'),'phone' => session('AgentPhone')))->find();
		if (!$agent) {
			$this->redirect('Home/Login/index');
		}else{
			$this->assign('agent', $agent);
		}
	}
}