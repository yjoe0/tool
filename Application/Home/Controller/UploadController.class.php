<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class UploadController extends Controller {

    public function index() {
        layout(false);
        $this->display();
    }
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg', 'mp4');// 设置附件上传类型
        $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
        // 上传单个文件 
        $chunk = I('post.chunk');
        $chunks = I('post.chunks');
        if ( $chunks ) {
            $upload->saveName = 'tmp'.$chunk.'.part';
        }
        $info   =   $upload->uploadOne($_FILES['file']);
        if(!$info) {// 上传错误提示错误信息
            // $this->error($upload->getError());
            $res = ['status' => false, 'msg' =>$upload->getError()];
        }else{// 上传成功 获取上传文件信息
             $res = ['status' => true, 'url' =>'./Uploads/'.$info['savepath'].$info['savename']];
        }
        if ( $chunks ) {
            if ( ($chunks - $chunk) == 1 ) {
                $path = $this->mergeFile($chunks, $info['savepath'], I('post.name'), $info['ext']);
                $res = ['status' => true, 'url' =>$path];
            }
        }
        echo json_encode($res);
    }

    private function mergeFile($chunks, $path, $targetFile, $ext) {
        $num = 0; 
        $path = './Uploads/'.$path;
        $fileName = $path.$targetFile;
        $file = fopen($fileName, 'wb'); 
        while ($num <= $chunks) { 
            $cacheFile = $path .'/tmp'. $num++ . '.part.'.$ext; 
            if (file_exists($cacheFile)) { 
            $cfile = fopen($cacheFile, 'rb'); 
            $content = fread($cfile, filesize($cacheFile)); 
            fclose($cfile); 
            fwrite($file, $content); 
            @unlink($cacheFile);
            } 
        } 
        fclose($file); 
        return $fileName;
    }

}