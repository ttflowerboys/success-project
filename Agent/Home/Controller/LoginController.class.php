<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	
    public function index(){
       $this->display();
    }

    public function loginDo(){
    	if( !IS_POST ) {E('页面不存在！');}

    	$phone = trim(I('post.phone'));
        $password = trim(I('post.password'));
        $code = trim(I('post.code'));
        if (empty($phone)) {$this->error('手机号不能为空！');}
        if (empty($password)) {$this->error('密码不能为空！');}
        if (empty($code)) {$this->error('验证码不能为空！');}elseif (!check_verify($code)) {$this->error("验证码输入有误！");}

        $rs = M('agent')->where(array("phone"=>$phone))->find();
        if ($rs) {
            if ($rs['password'] === md5($password)) {
                session('AgentId', $rs['id']);
                session('AgentPhone', $rs['phone']);
                $this->success('欢迎回来！',U('index/index'));
            }else{
                $this->error('手机号或密码不正确！');
            }
        }else{
            $this->error('手机号或密码不正确！');
        }
    }

    // 生成验证码
    public function verifyCode(){
      $config =    array(
          'fontSize'    =>    22,
          'fontttf'     =>    '4.ttf',
          'length'      =>    4,
          'imageH'      =>    42,
          'imageW'      =>    156,
          'useNoise'    =>    false,
          'useCurve'    =>    false
      );
      $Verify = new \Think\Verify($config);
      $Verify->entry();
    }

}