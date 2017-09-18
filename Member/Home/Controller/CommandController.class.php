<?php
namespace Home\Controller;
use Think\Controller;
class CommandController extends Controller {
	public function _initialize(){
		$member = M('member')->where(array('memberId' => session('memberId'),'memberUsername' => session('MemberUsername')))->find();
		/*if (!$member) {
			$this->redirect('Home/Login/index');
		}else{
			$this->assign('member', $member);
		}*/
		$this->redirect('Home/Login/index');
	}
}