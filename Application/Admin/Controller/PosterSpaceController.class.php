<?php
namespace Admin\Controller;
class PosterSpaceController extends CommonController {
    
    protected $_aOrderBy = 'sort desc, id';
    
    //添加后回到编辑页，继续添加值信息
    protected function _trigger_insert($id){
        redirect(U('edit',array('id'=>$id)));
    }
    //编辑时，读取值信息
    protected function _trigger_edit(&$data){
        $data['_list'] = M('Poster')->where(array('space_id'=>$data['id']))->order('sort,id')->select();
    }
    protected function _trigger_update(){
        //更新值排序
        $posters = I('post.posters',array());
        if(!empty($posters)){
            $_Poster = M('Poster');
            foreach($posters as $sort => $id){
                $_Poster->save(array(
                    'id' => $id,
                    'sort' => $sort
                ));
            }
        }
    }
    protected function _trigger_delete($ids){
        $_M = D('Poster');
        $_M->where(array('space_id'=>array('in',$ids)))->delete();
    }
}