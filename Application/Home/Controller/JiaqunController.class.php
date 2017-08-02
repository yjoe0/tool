<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class JiaqunController extends Controller {

    public function index() {
        // layout(false);
        // // $param['money'] = I('post.money',1);
        // // $param['body'] = I('post.body','车票');
        // $param['attach'] = I('get.id');
        // $this->assign( $param );
        // $this->display();
        $url = 'http://oq3qcztzi.bkt.clouddn.com/jq.html?';
        $e = time()+120;
        $SecretKey = '9f5_zkorQIinacIq5TxRfHu8Ww4xkD621qBeqZXa';
        $accessKey = 'PzywGWlZKQZKCns11pEvRI6RDacj1zOio_bdTfyb';
        $url = $url.'e='.$e;
        $sign = hash_hmac("sha1", $url, $SecretKey,true);  
        $signature = $this->base64UrlEncode($sign);  
        $token = $accessKey.':'.$signature;
        header('Location:'.$url.'&token='.$token);
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
        $param['return_url'] = I('post.return_url','http://mp.opihome.me/');
        $param['money'] = I('post.money',1);
        $param['body'] = I('post.body',1);
        $param['attach'] = I('post.attach', '');
        $param['notify_url'] = I('post.notify_url','http://mp.opihome.me/payback.html');
        $param['number_p']  = $number_p;
        $this->assign( $param );
        $this->display();
    }

    public function payurl() {
        $url = 'http://oq3qcztzi.bkt.clouddn.com/pay'.I('get.fee',19).'.html?';
        $e = time()+120;
        $SecretKey = '9f5_zkorQIinacIq5TxRfHu8Ww4xkD621qBeqZXa';
        $accessKey = 'PzywGWlZKQZKCns11pEvRI6RDacj1zOio_bdTfyb';
        $url = $url.'e='.$e;
        $sign = hash_hmac("sha1", $url, $SecretKey,true);  
        $signature = $this->base64UrlEncode($sign);  
        $token = $accessKey.':'.$signature;
        echo 'callbackD("'.$url.'&token='.$token.'")';

    }

    private function base64UrlEncode($str)  
    {  
         $find = array('+', '/');  
         $replace = array('-', '_');  
         return str_replace($find, $replace, base64_encode($str));  
    }  
}