<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {

    public function __construct() {
        $username = session('username');
        if ( !$username ) {
            $this->redirect('index/login');
            exit();
        }
        parent::__construct();
    }
    public function index() {
        $pid = M('chatpid');
        $datas = $pid->where('apply = 1')->select();
        $this->assign('datas', $datas);
        $this->display();
    }

}