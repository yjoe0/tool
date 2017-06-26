<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class TiebaController extends Controller {
    public function index() {
        echo "string";
        // $this->display();
    }

    public function addUser() {
        $condition['usermail'] = I('post.usermail');
        $User = M("tieba"); // 实例化User对象
        $check = $User->where($condition)->find();

        if ( $check ) {
            $data['tbcookie'] = I('post.tbcookie');
            $result = $User->where($condition)->save($data);
        } else {
            $data['tbcookie'] = I('post.tbcookie');
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
            $result = $User->where($condition)->setField('activate',1);
            if ($result) {
                $this->success('已激活');
            } else {
                 $this->error('链接已失效,请重新填写信息。',U('/'));
            }
        }
    }
    private function getEncryptUrl($t) {
        $basecodeUrl = base64_encode($t);
        $end = time()+43200;
        $url = 'http://tjgou.do/tieba/activate?info='.$basecodeUrl.'&end='.$end;
        return $url;
    }

}