<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class JiaqunController extends Controller {

    public function index() {
        layout(false);
        // $param['money'] = I('post.money',1);
        // $param['body'] = I('post.body','车票');
        $param['attach'] = I('get.id');
        $this->assign( $param );
        $this->display();
    }

    public function pay() {

        if (!IS_POST) {
            $this->error('请使用POST请求',0);
        }
        
        if ( cookie('number_p') != '' ) {
            $number_p = cookie('number_p') + rand(0,5);
            cookie('number_p', $number_p);
        } else {
            $number_p = rand(200, 600);
            cookie('number_p', $number_p);
        }
        layout(false);
        $param['return_url'] = I('post.return_url','http://tool.xiaoshenghuo.win/');
        $param['money'] = I('post.money',1);
        $param['body'] = I('post.body',1);
        $param['attach'] = I('post.attach', '');
        $param['notify_url'] = I('post.notify_url','http://tool.xiaoshenghuo.win/payback.html');
        $param['number_p']  = $number_p;
        $this->assign( $param );
        $this->display();
    }
}