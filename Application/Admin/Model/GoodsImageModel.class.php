<?php
namespace Admin\Model;
class GoodsImageModel extends CommonModel{
    //put your code here
    protected $_fileFields = 'origin_image,thumb_image,big_image,small_image';
    
    public function insert($data=''){
        if(false === $this->create($data)){
            return false;
        }
        $this->sort = 100;
        $setting = array(
            'savePath'=> CONTROLLER_NAME.'/',
            'mimes' => 'image/jpeg,image/png',
            'maxSize' => 2000000,
            'exts' => 'jpg,jpeg,png',
        );
        $file = $this->_upload('upload_file', $setting);
        if(!$file){
            return false;
        }
        $origin_name = '/Uploads/'.$file['savepath'].$file['savename'];
        $thumb_name = '/Uploads/'.$file['savepath'].$file['savename'].'_thumb.'.$file['ext'];
        $big_name = '/Uploads/'.$file['savepath'].$file['savename'].'_big.'.$file['ext'];
        $small_name = '/Uploads/'.$file['savepath'].$file['savename'].'_small.'.$file['ext'];
        //生成缩略图
        $_Image = new \Think\Image();
        $_Image->open('.'.$origin_name);
        $width = $_Image->width();
        $height = $width;//目前测试，以1：1宽高
        $_Image->thumb($width, $height, \Think\Image::IMAGE_THUMB_FILLED)->save('.'.$origin_name);
        $_Image->thumb(300, 300, \Think\Image::IMAGE_THUMB_FILLED)->save('.'.$thumb_name);
        $_Image->thumb(200, 200, \Think\Image::IMAGE_THUMB_FILLED)->save('.'.$big_name);
        $_Image->thumb(100, 100, \Think\Image::IMAGE_THUMB_FILLED)->save('.'.$small_name);
        $this->origin_image = $origin_name;
        $this->thumb_image = $thumb_name;
        $this->big_image = $big_name;
        $this->small_image = $small_name;
        $result = $this->add();
        if($result){
            return array(
                'id' => $result,
                'src' => $thumb_name
            );
        }else{
            return false;
        }
    }
}

?>
