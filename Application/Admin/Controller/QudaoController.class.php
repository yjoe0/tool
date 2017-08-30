<?php
namespace Admin\Controller;
use Think\Controller;
class QudaoController extends Controller {

    public function __construct() {
        $username = session('username');
        if ( !$username ) {
            $this->redirect('index/login');
        }
        parent::__construct();
    }
    public function index() {
        $pid = M('chatpid');
        $datas = $pid->select();
        $this->assign('datas', $datas);
        $this->display();
    }

    public function user() {
        $pid = I('get.pid');
        $condition['pid'] = $pid;
        $user = M('chatpid');
        $user = $user->where($condition)->find();
        $chatpay = M('chatpay');

        $datas = $chatpay->where($condition)->limit(100)->select();
        $condition['applyed'] = '0';
        $sum = $chatpay->where($condition)->sum('money');
        $this->assign('datas', $datas);
        $this->assign('user', $user);
        $this->assign('sum', $sum);
        $this->display();
    }

    public function add() {
        if (IS_GET) {
            $this->display();
            exit();
        }
        $condition['pid'] = I('post.pid');
        $condition['user'] = I('post.user');
        $condition['pwd'] = I('post.pwd', '12345678');
        $condition['alipay'] = I('post.alipay',' ');
        $User = M('chatpid');
        if ( $User->add($condition) ) {
            $this->redirect('qudao/index');
        } else {
            $this->error('信息错误');
        }

    }



}