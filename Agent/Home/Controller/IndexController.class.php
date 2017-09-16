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
        $agent = M('agent');

        $pid = session('AgentId');
        $result = $agent->order('id desc')->select();
        $list = $this->getChilds($result, $pid);

        // $listcount = count($list);
        // $pageNum = 1;
        // $Page = new \Think\Page($listcount, $pageNum);
        // $rs = array_slice($list, $Page->firstRow . ',' . $Page->listRows . ', ""');

        $empty = "<div class='NoInfo'><div class='tit'><i class='icon-lost'></i>空空如也～</div>抱歉，暂时还未搜索到<b class='t-green'>代理商</b>相关信息！</div>";
        $this->assign('empty',$empty);
        // $this->assign('page', $Page->show());
        $this->assign('list', $list);
        $this->display();
    }

    public function coin(){
        $this->display();
    }

    public function coinDo(){
        if( !IS_POST ) {E('页面不存在！');}
        $num = trim(I('num'));
        $phone = trim(I('phone'));
        $type = trim(I('type'));
        if (empty($phone)) {$this->error('手机号不能为空！');}elseif (!isMobile($phone)) { $this->error('请输入正确手机号！'); }
        if (!is_numeric($num) || empty($num)) { $this->error('参数有误，请返回1！'); }
        if (!is_numeric($type) || empty($type)) { $this->error('参数有误，请返回！'); }

        $agent = M('agent');
        $user = M('user');
        # 1. 检测当前代理商信息
        $agentRs = $agent->where(array('id'=>session('AgentId')))->find();
        $agentId = $agentRs['id'];
        if (!$agentRs) {
            $this->error('操作失败，亲稍后再试！');
        }elseif($num > $agentRs['coin1']) {
            $this->error('学习币库存不足！');
        }elseif($agentRs['phone'] == $phone) {
            $this->error('不能给自己充值！');
        }

        # 2. 通过手机号 检测用户，是否存在
        $db = $type == '1' ? $agent : $user;        
        $rs = $db->where(array('phone'=>$phone))->find();
        if (!$rs) {
            $this->error('用户不存在！');
        }

        $agent->startTrans();
        # 3. 直属关系充值
        if ($type == '1') {
            $agentData = $db->field('id,phone,parentid')->select();
            # 1. 先向下
            $a1 = $this->getChildsId($agentData, $agentId);
            # 2. 后向上
            $a2 = $this->getParentsId($agentData, $agentId);
            # 3. 数组合并
            $a = array_merge($a1, $a2);
            # 4. 是否在数组中
            $r = in_array($rs['id'], $a);
            if ($r) {
                # 2.1 可以开始充值了
                $income['coin'] = array('exp','coin+' . $num);
                $income['coin1'] = array('exp','coin1+' . $num);
                if ($db->where(array('id'=>$r))->save($income) === false) {
                    $agent->rollback();
                    $this->error('糟糕，充值失败！');
                }
                $pay['coin1'] = array('exp','coin1-' . $num);
                $pay['coin2'] = array('exp','coin2+' . $num);
                if($db->where(array('id'=>$agentId))->save($pay) === false){
                    $agent->rollback();
                    $this->error('糟糕，充值失败！');
                }
            }else{
                $this->error('对不起，您不能给该代理商充值！');
            }
        }

        $agent->commit();
        $this->success('恭喜，学习币充值成功！');

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
        $data['agentnum'] = 0; # 团队总人数，不算自己
        $data['agent'] = 0;
        $data['ldstr'] = '';

        # 1. 检查上级代理商是否存在
        $pRs = $agent->where(array('id'=>$agentId))->find();
        if (!$pRs) {
            $this->error('上级代理商不存在！');
        }
        # 1.1 设置新代理商ldstr
        $pId = $pRs['id'];
        $data['parentid'] = $pId;
        $data['parentuser'] = $pRs['username'];
        if($pRs['parentid']){
            $data['ldstr'] = $pRs['ldstr']. ','.$pId;
        }else{
            $data['ldstr'] = $pId;
        }

        # 2. 注册代理商
        $agent->startTrans();
        $rs = $agent->add($data);
        if ($rs === false) {
            $agent->rollback();
            $this->error('代理商注册失败！');
        }

        # 3.更新直属上级代理商        
        if($agent->where(array('id'=>$pId))->save(array('agent'=>array('exp','agent+1'))) === false){
            $agent->rollback();
            $this->error('更新代理商失败');
        }

        #  更新所有代理商 总数
        if($agent->where(array('id'=>array('in',$data['ldstr'])))->save(array('agentnum'=>array('exp','agentnum+1')))===false){
            $agent->rollback();
            $this->error('更新代理商人数失败');
        }

        $agent->commit();
        $this->success('代理商注册成功', u('index/agent'));
    }

    //传递一个父级分类ID返回所有子分类
    public function getChilds ($data, $pid) {
        $arr = array();
        foreach ($data as $v) {
            if ($v['parentid'] == $pid) {
                $arr[] = $v;
                $arr = array_merge($arr, self::getChilds($data, $v['id']));
            }
        }
        return $arr;
    }

    //传递一个父级分类ID返回所有子分类ID
    Static Public function getChildsId ($data, $pid) {
        $arr = array();
        foreach ($data as $v) {
            if ($v['parentid'] == $pid) {
                $arr[] = $v['id'];
                $arr = array_merge($arr, self::getChildsId($data, $v['id']));
            }
        }
        return $arr;
    }

    //传递一个子分类ID返回所有的父级分类
    public function getParents ($data, $id) {
        $arr = array();
        foreach ($data as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::getParents($data, $v['parentid']), $arr); 
            }
        }
        return $arr;
    }    

    //传递一个子分类ID返回所有的父级分类ID
    public function getParentsId ($data, $id) {
        $arr = array();
        foreach ($data as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v['id'];
                $arr = array_merge(self::getParentsId($data, $v['parentid']), $arr); 
            }
        }
        return $arr;
    }

}