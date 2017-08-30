<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index() {
        $username = session('username');
        if ( !$username ) {
            $this->redirect('login');
            exit();
        }
        $chatpay = M('chatpay');
        $count      = $chatpay->count();
        $Page       = new \Think\Page($count,2);
        $show       = $Page->show();
        $datas = $chatpay->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('datas', $datas);
        $this->assign('page', $show);
        $this->display();
    }

    public function login() {
        if ( IS_GET) {
            layout(false);
            $this->display();
            exit();
        }
        $User = M('admin_user');
        $condition['username'] = I('post.name');
        $password = I('post.password');
        $User = $User->where($condition)->select();
        if ($User && md5($password.C('PWD_SALT')) == $User[0]['password'] ) {
            session('username', $User[0]['username']);
            $this->redirect('index/index');
        } else {
            $this->error('请检查账号或密码',0);
        }
    }



}