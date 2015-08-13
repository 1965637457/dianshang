<?php
namespace Admin\Controller;
class AttributeController extends CommonController {
    
    public function index(){
        $_M = M(CONTROLLER_NAME);
        $count = $_M->count();
        if($count){
            $_P = new \Think\Page($count);
            $list = $_M->query("
                SELECT a.*, (SELECT GROUP_CONCAT(av.attribute_value SEPARATOR ' | ') FROM __ATTRIBUTE_VALUE__ av WHERE av.attribute_id = a.id ORDER BY av.sort, av.id) as attribute_values
                FROM __TABLE__ a
                ORDER BY a.id
                LIMIT {$_P->firstRow} , {$_P->listRows}
            ");
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    //添加后回到编辑页，继续添加值信息
    protected function _trigger_insert($id){
        redirect(U('edit',array('id'=>$id)));
    }
    //编辑时，读取值信息
    protected function _trigger_edit(&$data){
        $data['_list'] = M('AttributeValue')->where(array('attribute_id'=>$data['id']))->order('sort,id')->select();
    }
    //更新
    protected function _trigger_update(){
        //更新值排序
        $values = I('post.values',array());
        if(!empty($values)){
            $_Value = M('AttributeValue');
            $i = 0;
            foreach($values as $value){
                $value['sort'] = $i;
                $_Value->save($value);
                $i++;
            }
        }
    }
    //删除数据后，删除值信息
    protected function _trigger_delete($ids){
        D('AttributeValue')->where(array('attribute_id'=>array('in',$ids)))->delete();
    }
}