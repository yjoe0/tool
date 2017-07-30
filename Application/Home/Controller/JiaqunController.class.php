<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class JiaqunController extends Controller {

    public function index() {
        // if (!IS_POST) {
        //     $this->error('请使用POST请求',0);
        // }
        layout(false);
        vendor('Teengon.teegon', '' ,'.php');
        $param['order_no'] = substr(md5(time().print_r($_SERVER,1)), 0, 24); //订单号
        $param['channel'] = 'wxpay';
        $param['return_url'] = I('post.return_url','http://tool.xiaoshenghuo.win/');
        $param['amount'] = I('post.amount',1);
        $param['subject'] = I('post.subject',"虚拟物品");
        $param['metadata'] = I('post.metadata', '');
        $param['notify_url'] = I('post.notify_url','http://tool.xiaoshenghuo.win/payback.html');//支付成功后天工支付网关通知
        $param['client_ip'] = '127.0.0.1';
        $param['client_id'] = C('TEE_CLIENT_ID');

        $srv = new \TeegonService( C('TEE_API_URL') );
        $sign = $srv->sign($param);
        $param['sign'] = $sign;

        $this->assign( $param );
        $this->display();
    }

    public function pay() {

        // if (!IS_POST) {
        //     $this->error('请使用POST请求',0);
        // }
        layout(false);
        if ( cookie('number_p') != '' ) {
            $number_p = cookie('number_p') + rand(0,5);
            cookie('number_p', $number_p);
        } else {
            $number_p = rand(200, 600);
            cookie('number_p', $number_p);
        }
        layout(false);
        vendor('Teengon.teegon', '' ,'.php');
        $param['order_no'] = substr(md5(time().print_r($_SERVER,1)), 0, 24); //订单号
        $param['channel'] = 'wxpay';
        $param['return_url'] = I('post.return_url','http://tool.xiaoshenghuo.win/');
        $param['amount'] = I('post.amount',1);
        $param['subject'] = I('post.subject',"虚拟物品");
        $param['metadata'] = I('post.metadata', '');
        $param['notify_url'] = I('post.notify_url','http://tool.xiaoshenghuo.win/payback.html');//支付成功后天工支付网关通知
        $param['client_ip'] = '127.0.0.1';
        $param['client_id'] = C('TEE_CLIENT_ID');

        $srv = new \TeegonService( C('TEE_API_URL') );
        $sign = $srv->sign($param);
        $param['sign'] = $sign;

        $this->assign( $param );
        $this->display();
    }
}