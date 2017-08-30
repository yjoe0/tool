<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class ChatController extends Controller {

    public function index() {
        layout(false);
        $params['pid'] = I('get.pid',0);
        $this->assign($params);
        $this->display();
    }

    public function topic() {

        $topicId = I('get.topic','');
        if ($topicId == '') {
            $this->error('该频道不存在','');
        }
        if( $topicId != '56ba16ee38f22202' && cookie('hadpaid') <100) {
            print_r('expression');
            header('Location:/chat/check/'.$topicId);
        }
        layout(false);

        $topics = array('56ba16ee38f22202' => '5984905fd3a02f2c591095f5', //opi@1
                        'afaf9bc3f05b3ed6' => '589069a0c4d184904a84bcd9',
                        '076363d71a6906aa' => '59828406d3a02f2c591094b8', //gmail opi@3
                        'edb583598b483fbc' => '59828406d3a02f2c591094b8', //gmail opi@4
                 );
        $topicName = array('56ba16ee38f22202' => '闲聊', //opi@1
                        'afaf9bc3f05b3ed6' => '资源',
                        '076363d71a6906aa' => '福利', //gmail opi@3
                        'edb583598b483fbc' => '开车', //gmail opi@4
                 );
        $key = $topics[$topicId];
        if (!$key) {
            $this->error('该频道不存在','');
        }
        if ( cookie('number_p') != '' ) {
            $number_p = cookie('number_p') + rand(-1,2);
            cookie('number_p', $number_p);
        } else {
            $number_p = rand(200, 600);
            cookie('number_p', $number_p);
        }

        $hour = floor(date('H')/2);
        $topicStr = date('md');
        $topicStr = $topicId.'opi@'.$topicStr.$hour;
        $topic = substr(md5($topicStr),2,20);
        $params['topic'] = $topic;
        $params['key']   = $key;
        $params['topicName']   = $topicName[$topicId];
        $params['number_p']  = $number_p;
        $this->assign($params);
        $this->display();
    }

    public function alias() {
        layout(false);
        $alias = I('get.alias','');
        if (!$alias) {
            $this->error('没有用户','');
        }
        $params['alias'] = $alias;
        $this->assign($params);
        $this->display();
    }

    public function check() {
        layout(false);
        $topicId = I('get.topicId');
        $pid = I('get.pid',0);

        if( cookie('hadpaid')> 100 ) {
            $this->redirect('/');
        }
        
        if ( cookie('number_p') != '' ) {
            $number_p = cookie('number_p') + rand(0,2);
            cookie('number_p', $number_p);
        } else {
            $number_p = rand(200, 600);
            cookie('number_p', $number_p);
        }
        $param['topicId'] = $topicId;
        $param['pid'] = $pid;
        $param['number_p']  = $number_p;
        $this->assign($param);
        $this->display();
    }

    public function checkin() {
        $condition['ccid'] = I('get.ccid','');
        $condition['command'] = I('get.command','');
        $topicId = I('get.topicId','0');
        $Pay = M('chatpay');
        $check = $Pay->where($condition)->find();

        if ( !$check ) {
            $this->error('请检查口令以及是否在常用设备或浏览器打开');
        }
        if ( $check['money'] > 900) {
            cookie('hadpaid',$check['money'],'expire=3600');
            if ($topicId == 'afaf9bc3f05b3ed6') {
                header('Location:/chat/history/');
            } else {
                header('Location:/chat/topic/'.$topicId);
            }
            
        } else {
            $this->error('抱歉，你没有权限进入该频道');
        }
    }

    public function history() {
        layout(false);
        if( cookie('hadpaid') < 100 ) {
            header('Location:/chat/check/afaf9bc3f05b3ed6/0');
        } else {
            $Pay = M('chathistory');
            $datas = $Pay->limit(10)->order('id desc')->select();
            $this->assign('datas', $datas);
            $this->display();
        }
    }
    public function share() {
        layout(false);
        $Pay = M('chathistory');
        $datas = $Pay->limit(10)->order('id desc')->select();
        $this->assign('datas', $datas);
        $this->display();
    }

    public function getPayUrl() {

        $url_s = 'http://beibaolvyou.cn/jeesite/gate/getPayUrl';
        $data['mchno'] = C('mchno');
        $data['password'] = C('mchno_pwd');
        $data['sign'] = md5( C('mchno').C('mchno_pwd').C('mchno_key') );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_s);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
        $res = json_decode($result);
        if ($res->status) {
            return $res->payUrl;
        } else {
            return false;
        }
    }

    public function pay() {

        $pay_url = $this->getPayUrl();
        if ( !$pay_url ) {
            $this->error('抱歉，支付失败',0);
        }

        // if ($topicId = '076363d71a6906aa') {
        //     $money = 29;
        // } elseif ($topicId = 'edb583598b483fbc') {
        //     $money = 39;
        // }elseif ($topicId = 'afaf9bc3f05b3ed6') {
        //     $money = 29;
        // } else {
        //     $money = 19;
        // }
        $money = 10;
        $data['mchno'] = C('mchno');
        $data['outTradeNo'] = date("ymdHi").substr(md5(time().print_r($_SERVER,1)), 0, 22); 
        $data['money'] = (intval( $money )*100);
        $data['body'] = I('post.body', '车票');
        $data['nonceStr'] = I('get.ccid','0');
        $data['notifyUrl'] = 'http://188.166.1.45/chat/payback.html';
        $data['returnUrl'] = urlencode('http://mp.opihome.me/chat/');
        $data['attach'] = I('get.command','0').'|'.I('get.pid','0');
        $data['payTime'] = date("Y-m-d H:m:s");


        $signature =  $data['mchno'].$data['outTradeNo'].$data['money'].$data['nonceStr'].C('mchno_key');
        $data['sign'] = md5($signature);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $pay_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($curl);
        $Headers  =  curl_getinfo($curl);
        curl_close($curl);
        header('Location:'.$Headers["redirect_url"]);
    }

    public function payBack() {

        $status     = I('post.status');
        $outTradeNo = I('post.outTradeNo');
        $tradeNo    = I('post.tradeNo');
        $money      = I('post.money');
        $nonceStr   = I('post.nonceStr');
        $attachs   = explode('|', I('post.attach'));
        $message   = I('post.message');
        $paytime   = I('post.payTime');

        if ($status != 1) {
            echo 'FAIL';
            exit();
        }

        $singure =  md5( $status.$outTradeNo.$tradeNo.$money.$nonceStr.C('mchno_key') );
        $log = '';
        foreach($_POST as $key => $value) {
            $log = $log.' , '.$key.'=>'.$value;
        }
        $log = $log.' , Mysign: =>'.$singure;
        \Think\Log::record('pay'.date("[Y-m-d H:i:s]"). $log."\n");

        if ($singure != strtolower(I('post.sign')) ) {
            echo "FAIL";
            exit();
        }

        $params = array(
            "outTradeNo" => $outTradeNo,//商户订单号,必须唯一,格式必须是 appid_ 开头
            "money" => $money,//价格
            "paytime" => $paytime, //支付时间
            "pid" => $attachs[1],//pid
            'tradeNo' => $tradeNo,
            'message' => $message,
            'ccid' => $nonceStr,
            'command' => $attachs[0]
        );
        $Pay = M('chatpay');
        $Pay->create($params, Model::MODEL_INSERT);
        $result = $Pay->add();
        if (!$result) {
            \Think\Log::record('PAY.SQL'.$result.'\n');
        } 
        echo 'SUCCESS';
    }

}