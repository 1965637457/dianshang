<?php
namespace Admin\Controller;
class TypeController extends CommonController {

    public function index(){
        $_M = M(CONTROLLER_NAME);
        $count = $_M->count();
        if($count){
            $_P = new \Think\Page($count);
            $list = $_M->query("
                SELECT t.*, (SELECT GROUP_CONCAT(a.attribute SEPARATOR ' | ') FROM __ATTRIBUTE__ a, __TYPE_ATTRIBUTE__ ta WHERE ta.attribute_id = a.id AND ta.type_id = t.id ) as attribute_names
                FROM __TABLE__ t
                ORDER BY t.id
                LIMIT {$_P->firstRow} , {$_P->listRows}
            ");
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    protected function _trigger_edit(&$data){
        $type_id = $data['id'];
        $data['_attributes'] = M('TypeAttribute')->query("
            SELECT a.*, (SELECT GROUP_CONCAT(av.attribute_value SEPARATOR ' | ') FROM __ATTRIBUTE_VALUE__ av WHERE av.attribute_id = a.id ORDER BY av.sort, av.id) as attribute_values, ta.sort
            FROM __TABLE__ ta, __ATTRIBUTE__ a
            WHERE ta.type_id = {$type_id} AND ta.attribute_id = a.id
            ORDER BY ta.sort
        ");
    }
    protected function _trigger_update(){
        $type_id = I('post.id', 0, 'intval');
        //处理关联属性
        $attributes = I('post.attributes', array());
        $_TypeAttribute = M('TypeAttribute');
        $_TypeAttribute->where(array('type_id'=>$type_id))->delete();
        if(!empty($attributes)){
            foreach ($attributes as $sort => $attribute_id){
                $_TypeAttribute->add(array(
                    'type_id' => $type_id,
                    'attribute_id' => $attribute_id,
                    'sort' => $sort
                ));
            }
        }
    }
    public function chooseattribute(){
        $list = M('Attribute')->query("
            SELECT a.*, (SELECT GROUP_CONCAT(av.attribute_value SEPARATOR ' | ') FROM __ATTRIBUTE_VALUE__ av WHERE av.attribute_id = a.id ORDER BY av.sort, av.id) as attribute_values
            FROM __TABLE__ a
            ORDER BY a.id
        ");
        $this->assign('list', $list);
        
        $this->display();
    }
}