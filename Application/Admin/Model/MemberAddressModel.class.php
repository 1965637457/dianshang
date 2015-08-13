<?php
namespace Admin\Model;
class MemberAddressModel extends CommonModel {

    public function insert($data=''){
        if(false === $this->create($data)){
            return false;
        }
        if($this->member_id < 1){
            $this->error = '请刷新后再试！';
            return false;
        }
        if($this->is_default == 1){
            $this->execute("UPDATE __TABLE__ SET is_default = 0 WHERE member_id = " . $this->member_id);
        }
        return $this->add();
    }
    public function update($data=''){
        if(false === $this->create($data)){
            return false;
        }
        if($this->member_id < 1){
            $this->error = '请刷新后再试！';
            return false;
        }
        if($this->is_default == 1){
            $this->execute("UPDATE __TABLE__ SET is_default = 0 WHERE member_id = {$this->member_id} AND id != {$this->id}");
        }
        return $this->save();
    }
}