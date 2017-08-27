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
        $agent = M('agent');
        $status = trim(I('get.status'));
        $username = trim(I('get.username'));
        $phone = trim(I('get.phone'));

        if ($status && is_numeric($status)) {
            $map['status'] = $status;
        }else{
            $map['status'] = array('in',array(0,1));
        }

        if($username){$map['username']=$username;}
        if($phone){$map['phone']=$phone;}
        $map['parentid'] = session('AgentId');
        $listcount = $agent->where($map)->field('id')->count();
        $Page = new \Think\Page($listcount, 20);
        $list = $agent->where($map)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();        
        $empty = "<div class='NoInfo'><div class='tit'><i class='icon-lost'></i>空空如也～</div>抱歉，暂时还未搜索到<b class='t-green'>代理商</b>相关信息！</div>";
        $this->assign('empty',$empty);
        $this->assign('username',$username);
        $this->assign('phone',$phone);
        $this->assign('page', $Page->show());
        $this->assign('list', $list);
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

    public function addDo(){
    	if( !IS_POST ) {E('页面不存在！');}
    	$username = trim(I('username'));
		$sex = trim(I('sex'));
		$phone = trim(I('phone'));
		$identity = trim(I('identity'));
		$password = trim(I('password'));
		$location = trim(I('location'));

    	if (empty($username)) {$this->error('姓名不能为空！');}elseif (!check_username($username)) {
    		$this->error('请输入中文姓名！');
    	}
    	if (empty($sex)) {$this->error('请选择性别！');}
    	if (empty($phone)) {$this->error('手机号不能为空！');}elseif (!isMobile($phone)) {
    		$this->error('请输入正确手机号！');
    	}
    	if (empty($identity)) {$this->error('身份证号不能为空！');}elseif (!check_identity($identity)) {
    		$this->error('请输入正确身份证号！');
    	}
    	if (empty($password)) {$this->error('登录密码不能为空！');}
    	if (empty($location)) {$this->error('所在城市不能为空！');}

    	$agent = M('agent');
    	if ($agent->where(array('phone' => $phone))->find()) {
            $this->error('手机号已存在！');
        }
        if ($agent->where(array('identity' => $identity))->find()) {
            $this->error('身份证号已存在！');
        }

        $agentId = session('AgentId');

        $data['username'] = $username;
        $data['sex'] = $sex;
        $data['phone'] = $phone;
        $data['identity'] = $identity;
        $data['password'] = md5($password);
        $data['location'] = $location;
        $data['addtime'] = time();
        $data['parentid'] = $agentId;

        $agent->startTrans();
        $rs = $agent->add($data);
        if ($rs === false) {
        	$agent->rollback();
        	$this->error('代理商注册失败！');
        }

         if($agent->where(array('id'=>$agentId))->save(array('agent'=>array('exp','agent+1'))) === false){
            $agent->rollback();
            $this->error('更新代理商失败');
        }

        $agent->commit();
        $this->success('代理商注册成功', u('index/agent'));
    }
}