<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class PayController extends Controller {

    public function index() {
        if ( !IS_POST ) { 
            $this->error('请使用POST提交',0);
        }

        vendor('Teengon.teegon', '' ,'.php');
        $pay = new \TeegonService( C('TEE_API_URL') );

        $param['order_no'] = substr(md5(time().print_r($_SERVER,1)), 0, 24); //订单号
        $param['channel'] = I('post.channel') ?: 'wxpay';
        $param['return_url'] = I('post.return_url') ?: 'http://tool.xiaoshenghuo.win/payback.html';
        $param['amount'] = I('post.amount') ?: '1';
        $param['subject'] = I('post.subject') ?: '加群';
        $param['metadata'] = json_encode( I('post.metadata') );//没有给空
        $param['notify_url'] = I('post.notify_url')."?notify=1";//支付成功后天工支付网关通知

        echo '<pre>';
        var_dump($param);
        var_dump($pay->pay($param, false));
        echo "ok";
    }

}