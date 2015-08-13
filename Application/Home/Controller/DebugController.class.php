<?php
namespace Home\Controller;
use Think\Controller;
class DebugController extends Controller{
    //put your code here
//    public function importmedia(){
//        $_M = M('Archives');
//        $list = $_M->where(array('typeid'=>12))->order('id')->select();
//        if($list){
//            $_Media = M('Media');
//            foreach($list as $v){
//                $_Media->add(array(
//                    'title' => $v['title'],
//                    'publish_time' => $v['pubdate'],
//                    'source' => $v['source'],
//                    'status' => 1
//                ));
//            }
//        }
//    }
//    public function joinnews(){
//        $_M = M('News');
//        $list = M('Industry')->select();
//        if($list){
//            foreach ($list as $vo){
//                unset($vo['id']);
//                $vo['cid'] = 3;
//                $_M->add($vo);
//            }
//        }
//    }
//    public function importnews(){
//        $_Archives = M('Archives');
//        $list = $_Archives->where(array('typeid'=>49))->order('id')->select();
//        if($list){
//            $_M = M('News');
//            foreach($list as $v){
//                $_M->add(array(
//                    'title' => $v['title'],
//                    'publish_time' => $v['pubdate'],
//                    'status' => 1
//                ));
//            }
//        }
//    }
//    public function updatenewscontent(){
//        $_Content = M('Addonarticle');
//        $list = $_Content->select();
//        if($list){
//            $_M = M('News');
//            foreach($list as $v){
//                $_M->save(array(
//                    'id' => $v['aid'],
//                    'content' => $v['body']
//                ));
//            }
//        }
//    }
}

?>
