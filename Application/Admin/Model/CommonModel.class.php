<?php
namespace Admin\Model;
use Think\Model;
use Think\Upload;
class CommonModel extends Model{

    /**
     * 表中存放图片或文件等的字段，多个用逗号分隔
     * 用于删除数据时，删除相关的文件
     * @var string 
     */
    protected $_fileFields = '';
    protected $_tmpFiles = array();
    protected $_rootPath = '/Uploads/';
    protected $_hasSeoName = false;
    protected $_seoNameType = '';

    public function insert($data=''){
        if(false === $this->create($data)){
            return false;
        }
        if($this->_fileFields !== ''){
            $setting = array('savePath'=> CONTROLLER_NAME.'/','autoSub'=>false);
            $fields = explode(',', $this->_fileFields);
            foreach($fields as $field){
                $file = $this->_upload('upload_'.$field, $setting);
                if($file){
                    $this->$field = $this->_rootPath.$file['savepath'].$file['savename'];
                }
            }
        }
        return $this->add();
    }
    
    protected function _insertTree(){
        $pid = I('post.pid',0,'intval');
        if($pid === 0){
            $_POST['route'] = 0;
            $_POST['grade'] = 1;
        }else{
            $parent = $this->find($pid);
            $_POST['grade'] = $parent['grade'] + 1;
            $_POST['route'] = $parent['route'].','.$parent['id'];
        }
        return self::insert();
    }

    public function update($data=''){
        if(false === $this->create($data)){
            return false;
        }
        if($this->_fileFields !== ''){
            $setting = array('savePath'=> CONTROLLER_NAME.'/','autoSub'=>false);
            $fields = explode(',', $this->_fileFields);
            foreach($fields as $field){
                $file = $this->_upload('upload_'.$field, $setting);
                if($file){
                    !empty($this->$field) && unlink('.'.$this->$field);
                    $this->$field = $this->_rootPath.$file['savepath'].$file['savename'];
                }
            }
        }
        return $this->save();
    }
    
    protected function _updateTree(){
        if($_POST['old_pid']!=$_POST['pid']){
            $parent = $this->find($_POST['pid']);
            if($parent){
                $_POST['grade'] = $parent['grade'] + 1;
                $_POST['route'] = $parent['route'].','.$parent['id'];
            }else{
                $_POST['pid'] = 0;
                $_POST['grade'] = 1;
                $_POST['route'] = 0;
            }
        }
        $result = self::update();
        if(false !== $result){
            /*更换了父类，则更新所有子类的层级和父路径*/
            if($_POST['old_pid']!=$_POST['pid']){
                self::_updateChildRoute($_POST['id'], $_POST['grade'], $_POST['route']);
            }
        }
        return $result;
    }
    
    /**
     *  更新所有子类的层级和父路径
     * 
     * @param type $id 父节点ID
     * @param type $grade 父节点层级
     * @param type $route 父节点路径
     */
    protected function _updateChildRoute($id,$grade,$route){
        $pk = $this->getPk();
        $list = $this->query("
            SELECT c.{$pk},c.grade,c.route,(select count(cc.{$pk}) from __TABLE__ cc where cc.pid=c.{$pk}) as children
            FROM __TABLE__ c
            WHERE c.pid = {$id}
        ");
        if($list){
            foreach($list as $vo){
                $children = $vo['children'];
                unset($vo['children']);
                $vo['grade'] = $grade + 1;
                $vo['route'] = $route . ',' . $id;
                $this->save($vo);
                if($children>0){
                    self::_updateChildRoute($vo[$pk],$vo['grade'],$vo['route']);
                }
            }
        }
    }
    /**
     * 数据添加或更新时，处理伪静态路径
     * @param string $seo_name
     * @return string
     */
    protected function _handleSeoName($seo_name){
        if(empty($seo_name) || is_null($seo_name)){
            return '';
        }
        $id = I('post.'.$this->getPk(), 0, 'intval');
        $exist = $this->where(array('seo_name'=>$seo_name))->find();
        if(($id == 0 && $exist) || ($id && $exist && $exist[$this->getPk()]!=$id)){
            $seo_name .= '-html';
        }
        return $seo_name;
    }
    /**
     * 数据添加时，处理伪静态路径
     * 全站伪静态，暂不启用
     * @param type $data
     * @return boolean
     */
    protected function _insertSeoName($data){
        if($this->_hasSeoName){
            $object_id = $data[$this->getPk()];
            $seo_name = I('post.seo_name', '');
            $type = $this->_seoNameType;
            if(empty($seo_name) || empty($type)){
                return true;
            }
            $_SeoNames = M('SeoNames');
            $exist = $_SeoNames->where(array('seo_name'=>$seo_name, 'type'=>$type))->find();
            if($exist && $exist['object_id']!=$object_id){
                $seo_name .= '#'.rand_string(6);
            }
            $_SeoNames->add(array(
                'seo_name' => $seo_name,
                'object_id' => $object_id,
                'type' => $type
            ));
        }
    }
    /**
     * 数据添加时，处理伪静态路径
     * 全站伪静态，暂不启用
     * @param type $data
     * @return boolean
     */
    protected function _updateSeoName($data){
        if($this->_hasSeoName){
            $object_id = $data[$this->getPk()];
            $seo_name = I('post.seo_name', '');
            $type = $this->_seoNameType;
            if(empty($seo_name) || empty($type)){
                return true;
            }
            
        }
    }
    /**
     * 删除伪静态路径
     * 全站伪静态，暂不启用
     * @param type $data
     */
    protected function _removeSeoName($data){
        if($this->_hasSeoName){
            $object_id = $data[$this->getPk()];
            $type = $this->_seoNameType;
            if(empty($type) || empty($object_id)){
                return true;
            }
            $_SeoNames = M('SeoNames');
            $_SeoNames->where(array('object_id'=>$object_id,'type'=>$type))->delete();
        }
    }
    /**
     * 获取数据的伪静态路径
     * 全站伪静态，暂不启用
     * @param type $data
     * @return boolean
     */
    protected function _getSeoName(&$data){
        if($this->_hasSeoName){
            $object_id = $data[$this->getPk()];
            $type = $this->_seoNameType;
            if(empty($type) || empty($object_id)){
                return true;
            }
            $_SeoNames = M('SeoNames');
            $result = $_SeoNames->where(array('object_id'=>$object_id,'type'=>$type))->find();
            if($result){
                $data['seo_name'] = $result['seo_name'];
            }
        }
    }
    protected function _after_find(&$data, $options) {
        $this->_getSeoName($data);
    }
    protected function _after_insert($data, $options) {
        $this->_insertSeoName($data);
    }
    protected function _after_update($data, $options) {
        $this->_updateSeoName($data);
    }
    /**
     * 删除前的回调方法
     * @param array $options 
     * array('where'=>array('id'=>array('in','1,2,3')),'table'=>'***','model'=>'***')
     */
    protected function _before_delete($options) {
        if($this->_fileFields != ''){
            $this->_tmpFiles = $this->field($this->_fileFields)->where($options['where'])->select();
        }
    }
    /**
     * 删除后的回调方法
     * @param array $data
     * @param array $options 
     * $data: array('id'=>array('in','1,2,3'))
     * $options: array('where'=>array('id'=>array('in','1,2,3')),'table'=>'***','model'=>'***')
     */
    protected function _after_delete($data, $options) {
        $this->_removeSeoName($data);
        if($this->_fileFields != ''){
            $this->_unlink_file();
        }
    }
    /**
     * 去除图片
     * @param type $where
     * @param type $fields 
     */
    protected function _unlink_file(){
        if(!empty($this->_tmpFiles)){
            $list = $this->_tmpFiles;
            $fields = explode(',', $this->_fileFields);
            foreach($list as $vo){
                foreach ($fields as $field){
                    unlink('.'.$vo[$field]);
                }
            }
        }
    }
    
    /**
     * 上传单个文件
     * array(
     *  'name','type','size','key','extension','savepath','savename','hash'
     * )
     * 
     * @param type $upload_field
     * @param type $save_path
     * @return array 
     */
    protected function _upload($upload_field='upload_file',$setting=array()){
        if( !empty($_FILES[$upload_field]['name']) ){
            $_Upload = new Upload($setting);
            $info = $_Upload->uploadOne($_FILES[$upload_field]);
            if($info){
                return $info;
            }else{
                $this->error = $_Upload->getError();
            }
        }
        return false;
    }
    /**
     * 逗号串起
     * @param type $data
     * @return string
     */
    protected function _commaImplode($data){
        if(is_null($data)){
            return '';
        }
        $data = !is_array($data) ? array($data) : $data;
        return implode(',', $data);
    }
    protected function _isChecked($data){
        return is_null($data) ? 0 : 1;
    }
}

?>
