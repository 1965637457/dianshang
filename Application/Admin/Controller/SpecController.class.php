<?php
namespace Admin\Controller;
class SpecController extends CommonController {
    
    protected $_aOrderBy = 'id';
    //添加后回到编辑页，继续添加值信息
    protected function _trigger_insert($id){
        redirect(U('edit',array('id'=>$id)));
    }
    //编辑时，读取值信息
    protected function _trigger_edit(&$data){
        $data['_list'] = M('SpecValue')->where(array('spec_id'=>$data['id']))->order('sort,id')->select();
    }
    //更新
    protected function _trigger_update(){
        //更新值排序
        $spec_values = I('post.spec_values',array());
        if(!empty($spec_values)){
            $_SpecValue = M('SpecValue');
            foreach($spec_values as $sort => $id){
                $_SpecValue->save(array(
                    'id' => $id,
                    'sort' => $sort
                ));
            }
        }
    }
    //删除数据后，删除值信息
    protected function _trigger_delete($ids){
        D('SpecValue')->where(array('spec_id'=>array('in',$ids)))->delete();
    }
    
}