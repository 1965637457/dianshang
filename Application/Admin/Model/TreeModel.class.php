<?php

namespace Admin\Model;
class TreeModel extends CommonModel{
    //put your code here
    public function insert(){
        return parent::_insertTree();
    }
    
    public function update() {
        return parent::_updateTree();
    }
}

?>
