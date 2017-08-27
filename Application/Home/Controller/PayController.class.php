<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class PayController extends Controller {

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

        $data['mchno'] = C('mchno');
        $data['outTradeNo'] = date("ymdHi").substr(md5(time().print_r($_SERVER,1)), 0, 22); 
        $data['money'] = (intval( I('post.money', '1') )*100);
        $data['body'] = I('post.body', '车票');
        $data['nonceStr'] = date('ymdHi');
        $data['notifyUrl'] = 'http://mp.opihome.me/pay/payback.html';
        $data['returnUrl'] = urlencode('http://nczdk.cn/jq.php');
        $data['attach'] = I('post.attach','');
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

        echo "SUCCESS";
        die();
        $status     = I('post.status');
        $outTradeNo = I('post.outTradeNo');
        $tradeNo    = I('post.tradeNo');
        $money      = I('post.money');
        $nonceStr   = I('post.nonceStr');
        $attach   = I('post.attach');
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
        \Think\Log::record(date("[Y-m-d H:i:s]"). $log."\n");

        if ($singure != strtolower(I('post.sign')) ) {
            echo "FAIL";
            exit();
        }

        $params = array(
            "outTradeNo" => $outTradeNo,//商户订单号,必须唯一,格式必须是 appid_ 开头
            "money" => $money,//价格
            "paytime" => $paytime, //支付时间
            "pid" => $attach,//pid
            'tradeNo' => $tradeNo,
            'message' => $message
        );
        $Pay = M('pay');
        $Pay->create($params, Model::MODEL_INSERT);
        $result = $Pay->add();
        if (!$result) {
            \Think\Log::record('PAY SQL:'.$result);
        } 
        echo 'SUCCESS';
    }

}
