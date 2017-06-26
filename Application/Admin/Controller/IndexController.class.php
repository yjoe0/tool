<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
        $username = session('username');
        if ( !$username ) {
            $this->redirect('index/login');
        }
        $User = M('admin_user');
        $condition['username'] = $username;
        $User = $User->where($condition)->find();
        $this->display();
    }

    public function login() {
        if ( session('username') ) {
            $this->redirect('index/index');
        }
        if ( IS_GET) {
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

    public function regisger() {
        $this->display();
    }



}