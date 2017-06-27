<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class WebMonitorController extends Controller {
    public function index() {
        // echo "string";
        $this->display();
    }

    public function addUser() {
        $condition['usermail'] = I('post.usermail');
        $User = M("webmonitor"); 
        $check = $User->where($condition)->find();

        if ( $check ) {
            $data['userurl'] = I('post.userurl');
            $data['userpattern'] = I('post.userpattern');
            $result = $User->where($condition)->save($data);
        } else {
            $data['userurl'] = I('post.userurl');
            $data['userpattern'] = I('post.userpattern');
            $data['usermail'] = $condition['usermail'];
            $User->create($data, Model::MODEL_INSERT);
            $result = $User->add();
        }

        if (!$result) {
            $this->error($result);
        } else {
            sendMail($condition['usermail'],'网页检测激活链接',$this->getEncryptUrl($condition['usermail']));
            $this->success('信息已添加，请检查邮箱并激活',U('/'));
        }
    }

    public function activate() {
        $usermail = base64_decode(I('get.info'));
        $end = I('get.end');
        if (time() > $end) {
            $this->error('链接已失效',U('/'));
        } else {
            $User = M("webmonitor");
            $condition['usermail'] = $usermail;
            $User->where($condition)->setField('activate',1);
            $result = $User->where($condition)->getField('activate');
            if ($result == '1') {
                $this->success('已激活');
            } else {
                 $this->error('链接已失效,请重新填写信息。',U('/'));
            }
        }
    }
    private function getEncryptUrl($t) {
        $basecodeUrl = base64_encode($t);
        $end = time()+43200;
        $url = 'http://tool.xiaoshenghuo.win/webMonitor/activate?info='.$basecodeUrl.'&end='.$end;
        return $url;
    }

}