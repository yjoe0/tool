<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class ChatController extends Controller {

    public function index() {
        layout(false);
        $this->display();
    }
}