<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class TiebaController extends Controller {
    public function index() {
        $this->display();
    }

    public function addUser() {
        $condition['usermail'] = I('post.usermail');
        if ($condition['usermail'] == "") {
            $this->error('数据错误，请返回重新填写');
        }
        $User = M("tieba"); 
        $check = $User->where($condition)->find();

        $tbcookie = str_replace('&quot;', '', I('post.tbcookie'));
        if ($tbcookie == "") {
            $this->error('数据错误，请返回重新填写');
        }
        if ( $check ) {
            $data['tbcookie'] = $tbcookie;
            $result = $User->where($condition)->save($data);
        } else {
            $data['tbcookie'] = $tbcookie;
            $data['usermail'] = $condition['usermail'];
            $User->create($data, Model::MODEL_INSERT);
            $result = $User->add();
        }

        if (!$result) {
            $this->error($result);
        } else {
            sendMail($condition['usermail'],'贴吧签到激活链接',$this->getEncryptUrl($condition['usermail']));
            $this->success('信息已添加，请检查邮箱并激活',U('/'));
        }
    }

    public function activate() {
        $usermail = base64_decode(I('get.info'));
        $end = I('get.end');
        if (time() > $end) {
            $this->error('链接已失效',U('/'));
        } else {
            $User = M("tieba");
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
        $url = 'http://tool.xiaoshenghuo.win/tieba/activate?info='.$basecodeUrl.'&end='.$end;
        return $url;
    }

}