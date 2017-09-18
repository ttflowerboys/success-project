<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->display();
    }

    public function registDo(){
    	if( !IS_POST ) {E('页面不存在！');}

    	$phone = trim(I('post.phone'));        
        $code = trim(I('post.code'));
        $password = trim(I('post.password'));
        $cpassword = trim(I('post.cpassword'));
        $sponsor = trim(I('post.sponsor'));

        if (empty($phone)) {$this->error('手机号不能为空！');}
        if (empty($code)) {$this->error('验证码不能为空！');}
        if (empty($password)) {$this->error('密码不能为空！');}
        if (empty($cpassword)) {$this->error('确认密码不能为空！');}
        if ($cpassword != $password) {
        	$this->error('两次密码输入不正确！');
        }
        if (empty($sponsor)) {$this->error('推荐人不能为空！');}

		$member = M('member');

        $pRs = $member->where(array("phone"=>$sponsor))->find();
        if(!$pRs){ $this->error('推荐人不存在！'); }        
        $rs = $member->where(array('phone'=>$phone))->find();
        if ($rs) { $this->error('手机号已存在！'); }

        # 注册新会员
        # 1. 会员信息
        $pId = $pRs['id'];
        $data['phone'] = $phone;
        $data['password'] = md5($password);
        $data['parentid'] = $pRs['id'];
        $data['parentphone'] = $pRs['phone'];
        if($pRs['parentid']){
            $data['ldstr'] = $pRs['ldstr']. ','.$pId;
        }else{
            $data['ldstr'] = $pId;
        }
        $data['addtime'] = time();
        # 2. 注册代理商
        $member->startTrans();
        $rs = $member->add($data);
        if ($rs === false) {
        	$this->error('注册失败！');
        }

        $this->success('注册成功！');
    }

}