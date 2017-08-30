<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class ManagerController extends Controller {

    public function index() {
          $username = session('userpid');
        if ( !$username ) {
            layout(false);
            $this->display('index');
        }

    }

    public function login() {
        if ( IS_GET) {
            $this->display();
            exit();
        }
        $User = M('chatpid');
        $condition['user'] = I('post.name');
        $password = I('post.password');
        $user = $User->where($condition)->find();
        if ($user && $password == $user['pwd'] ) {
            session(array('name'=>'userpid','expire'=>3600));
            session('userpid', $user['pid']);
            $t = $User->where($user)->setField('updatetime', date("Y-m-d H:i:s" ,time()) );
            $this->redirect('manager/main');
        } else {
            $this->error('请检查账号或密码',0);
        }
    }

    public function main() {
        $userpid = session('userpid');
        if ( !$userpid ) {
            layout(false);
            $this->redirect('manager/index');
        }
        $condition['pid'] = $userpid;
        $chatpay = M('chatpay');
        $count      = $chatpay->where($condition)->count();
        $Page       = new \Think\Page($count,2);
        $show       = $Page->show();
        $datas = $chatpay->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('datas', $datas);
        $this->assign('page', $show);
        $this->display();
    }

    public function user() {
        $userpid = session('userpid');
        if ( !$userpid ) {
            layout(false);
            $this->redirect('manager/index');
        }
        $User = M('chatpid');
        $chatpay = M('chatpay');
        $condition['pid'] = $userpid;
        $user = $User->where($condition)->find();
        $condition['applyed'] = '0';
        $sum = $chatpay->where($condition)->Sum('money');
        $this->assign('user', $user);
        $this->assign('sum', $sum);
        $this->display();
    }

    public function update() {
         if ( IS_GET) {
            $this->display();
            exit();
       }
       $condition['id'] = I('post.id');
       if (I('post.pwd')) {
            $condition['pwd'] = I('post.pwd');
       }
       if (I('post.alipay')) {
            $condition['alipay'] = I('post.alipay');
       }
       if (I('post.apply')) {
            $condition['apply'] = I('post.apply', '0');
       }
       
       $User = M('chatpid');
       if ( $User->save($condition) ) {
        $res = array('status' => true, 'pwd' =>$condition['pwd'], 'alipay' => $condition['alipay']);
       } else {
        $res = array('status' => false, 'msg' => 'update error');
       }
       echo json_encode($res);
    }

}