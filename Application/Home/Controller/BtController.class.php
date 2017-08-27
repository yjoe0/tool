<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class BtController extends Controller {
    public function index() {
        layout(false);
        $Bt = M('bt');
        $page = I('get.page','1');
        $CacheCount = S('CacheCount');
        if ($CacheCount) {
            $count = $CacheCount;
        } else {
            $count  = $Bt->count();
            S('CacheCount', $count,3600);
        }
        
        $data = $Bt->order('id')->page($page,'25')->select();
        $pages = ceil($count/25);
        $pages = $pages > ($page+7) ? ($page+7) : $pages;

        $this->assign('types', $this->getTypes() );
        $this->assign('Cachetags', $this->getTags() );
        $this->assign('datas', $data);
        $this->assign('pages', $pages );
        $this->assign('page', $page);
        $this->assign('ext', 'p/');
        $this->display('Bt_index');
    }

    public function detail() {
        layout(false);
        $Bt = M('bt');
        $condition['fid'] = I('path.2');
        $data = $Bt->where($condition)->find();
        $key = rand(1,9);

        if ($data) {
            $this->assign('datas', $data);
            $Bt->where($condition)->setInc('download');

            $s = str_split($data['magnet'].substr(0, 70));
            $ss = [];
            foreach ($s as $k => $value) {
                $ss[] = ord($value)+ $key;
            }
            $data['magnet'] = implode(',', $ss);
            $this->assign('types', $this->getTypes() );
            $this->assign('Cachetags', $this->getTags() );
            $this->assign('datas', $data);
            $this->assign('key', $key);
            $this->display();
        }
    }
    public function type() {
        layout(false);
        $Bt = M('bt');
        $page = I('path.3','1');
        $condition['type'] = I('path.2');
        $count      = $Bt->where($condition)->count();

        $data = $Bt->where($condition)->order('id')->page($page,'25')->select();

        $pages = ceil($count/25);
        $pages = $pages > ($page+7) ? ($page+7) : $pages;
        $this->assign('types', $this->getTypes() );
        $this->assign('Cachetags', $this->getTags() );
        $this->assign('datas', $data);
        $this->assign('pages', $pages );
        $this->assign('page', $page);
        $this->assign('ext', 'type/'.$condition['type'].'/');

        $this->display('Bt_index');
    }
    public function tag() {
        layout(false);
        $Bt = M('bt');
        $page = I('path.3','1');
        $condition['tags'] = array('like',"%".I('path.2')."%" );
        $count      = $Bt->where($condition)->count();

        $data = $Bt->where($condition)->order('id')->page($page,'25')->select();

        $pages = ceil($count/25);
        $pages = $pages > ($page+7) ? ($page+7) : $pages;
        $this->assign('types', $this->getTypes() );
        $this->assign('Cachetags', $this->getTags() );
        $this->assign('datas', $data);
        $this->assign('pages', $pages );
        $this->assign('page', $page);
        $this->assign('ext', 'tag/'.I('path.2').'/');
        $this->display('Bt_index');
    }

    private function getTypes() {
        $CacheTypes = S('CacheTypes');
        if ($CacheTypes) {
            return $CacheTypes;
        } else {
            $Bt = M('bt');
            $data = $Bt->field('type')->group('type')->select();
            S('CacheTypes', $data, 3600);
            return $data;
        }
        
    }

    private function getTags() {
        $Cachetags = S('Cachetags');

        if ($Cachetags) {
            return $Cachetags;
        } else {
            $Bt = M('bt');
            $page = rand(1,S('CacheCount')-100);
            $data = $Bt->field('tags')->group('tags')->limit($page,100)->select();
            $data = implode(',', array_column($data,'tags'));
            $datas = explode(',', $data);
            $datas = array_unique($datas);
            S('Cachetags', $datas, 3600);
            return $datas;
        }
        
    }

}